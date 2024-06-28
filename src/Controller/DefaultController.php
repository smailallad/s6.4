<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Entity\GroupeUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home_')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/app', name: 'app_home')]
    public function home(): Response
    {
        //dump("entrer");
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(
            LoginType::class,
            ['action' => $this->generateUrl('app_login')]
        );

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'form' => $form->createView()
        ]);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // controller can be blank: it will never be called!
        dump('logout');
        return $this->redirectToRoute('app_home');
    }

    #[Route('/user/add', name: 'app_user_add')]

    public function addUser(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {

        $group = new GroupeUser();
        $group->setName("Admin");
        $group->setRoles(["ROLE_ADMIN"]);
        $em->persist($group);
        $em->flush();

        $user = new User();
        $user->setEmail("admin@gmail.com");
        $user->setGroupeUser($group);
        $plaintextPassword = "admin";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();
        //=====
        $group = new GroupeUser();
        $group->setName("user");
        $group->setRoles(["ROLE_USER"]);
        $em->persist($group);
        $em->flush();
        $user = new User();
        $user->setEmail("user@gmail.com");
        $user->setGroupeUser($group);
        $plaintextPassword = "user";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();
        //=====
        //=====
        $group = new GroupeUser();
        $group->setName("Super Admin");
        $group->setRoles(["ROLE_SUPER_ADMIN"]);
        $em->persist($group);
        $em->flush();
        $user = new User();
        $user->setEmail("super_admin@gmail.com");
        $user->setGroupeUser($group);
        $plaintextPassword = "superadmin";

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();
        //=====
        return $this->redirectToRoute('app_home');


        // ...
    }
}
