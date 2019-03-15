<?php

namespace Contact\Controller;

use Contact\Form\ContactForm;
use Contact\Service\MailSender;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\ORMException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use ZendService\ReCaptcha\ReCaptcha;

class ContactController extends AbstractActionController
{
    /**
     * Service Manager
     * @var ServiceManager
     */
    private $serviceManager;
    /**
     * Entity Manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ServiceManager
     * @var EntityManager
     */
    public function __construct(ServiceManager $serviceManager, EntityManager $entityManager)
    {
        $this->serviceManager = $serviceManager;
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $config = $this->serviceManager->get('config');
        $form = new ContactForm();
        $recaptcha = new ReCaptcha($config['recaptcha']['siteKey'], $config['recaptcha']['secretKey']);
        $form->get('submit')->setValue('Send');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $result = $recaptcha->verify($_POST['g-recaptcha-response']);
                    if (!$result->isValid()) {
                        throw new \Exception('Recaptcha is not valid');
                    } else {

                        $data = $form->getData();
                        $contact = new \Contact\Entity\Contact();
                        $contact->setSubject($data['subject']);
                        $contact->setEmail($data['email']);
                        $contact->setMessage($data['message']);

                        try {
                            $this->entityManager->persist($contact);
                            $this->entityManager->flush();
                        } catch (ORMException $e) {
                            return new JsonModel([
                                'message' => $e->getMessage()
                            ]);
                        }
                        $mailSender = new MailSender($this->serviceManager);
                        $mailSender->sendMail($data['subject'], $data['email'], $data['message']);

                        return $this->redirect()->toRoute('contact');
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        return new ViewModel([
            'recaptcha' => $recaptcha,
            'form' => $form
        ]);
    }
}
