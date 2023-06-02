<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    UserAuthenticatorInterface $userAuthenticator, 
    UsersAuthenticator $authenticator, 
    EntityManagerInterface $entityManager,
    SendMailService $mail,
    JWTService $jwt): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            
            // On génère le JWT du user

            // On créer le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // On créer le payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le Token
            $token = $jwt->generate($header,$payload,$this->getParameter('app.jwtsecret'),21600);


            // do anything else you need here, like send an email
            $mail->send(
                'noreply.edi@neyret-is.com',
                $user->getEmail(),
                'Activation de votre compte',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
                );


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        // On vérifie si le tocken est valid, n'a pas expiré et n'a pas été modifié
        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token,$this->getParameter('app.jwtsecret'))){

            // On récupère le payload
            $payload = $jwt->getPayload($token);

            // On récupère le user du tocken
            $user = $usersRepository->find($payload['user_id']);

            // On vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->getIsVerified()){
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success','Utilisateur activé');
                return $this->redirectToRoute('profile_index');
            }

        }
        // ici un problème se pose dans le token
        $this->addFlash('danger','Le token est invalid ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();
        if(!$user){
            $this->addFlash('danger','Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if($user->getIsVerified()){
            $this->addFlash('warning','Cet utilisateur est déja activé');
            return $this->redirectToRoute('profile_index');

        }

        // On génère le JWT du user

            // On créer le header
            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            // On créer le payload
            $payload = [
                'user_id' => $user->getId()
            ];

            // On génère le Token
            $token = $jwt->generate($header,$payload,$this->getParameter('app.jwtsecret'),21600);


            // do anything else you need here, like send an email
            $mail->send(
                'noreply.edi@neyret-is.com',
                $user->getEmail(),
                'Activation de votre compte',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
                );

                $this->addFlash('success','Email de vérification envoyé');
                return $this->redirectToRoute('profile_index');

    }

}
