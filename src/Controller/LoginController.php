<?php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Agents;
use App\Form\LoginFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Bridge\Google\Transport;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use App\Repository\AgentsRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Cookie;







class LoginController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
     
    }

    #[Route('/login', name: 'login', methods: ['GET','POST'])]
    public function login(Request $request, UrlGeneratorInterface $urlGenerator, FlashBagInterface $flashBag): Response
    {
       
        $form = $this->createForm(LoginFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $email = $data['Email'];
        $password = $data['Password'];

        $entityManager = $this->managerRegistry->getManager();

        if ($email === 'admin@oaca.com' && $password === 'admin') {
            $session = $request->getSession();
            $session->set('user_role', 'Admin');
            return new RedirectResponse($urlGenerator->generate('app_dashboard'), Response::HTTP_SEE_OTHER);
       
        }

            $user = $entityManager->getRepository(Agents::class)->findOneBy(['email' => $email]);
            $email= $user->getEmail();
            $pass = $user->getPassword();
            
          
            if (!$email) {
                $flashBag->add('error', 'The email is wrong.');  
            }

            else {
                $session = $request->getSession();
                $session->set('id_agent', $user->getId());
                $id_agent=$user->getId();
                return new RedirectResponse($urlGenerator->generate('profilag',['id_agent' => $id_agent]), Response::HTTP_SEE_OTHER);
            }
        
          
    }

    

    return $this->render('login/login.html.twig', [
        'form' => $form->createView(),
        
    ]);
    }

    
      
}
