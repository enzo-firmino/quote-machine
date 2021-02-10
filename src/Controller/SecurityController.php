<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/user/{id}", name="user_profil", methods={"GET"})
     * @param User $user
     * @return Response
     */
    public function profil(User $user) : Response
    {
        $queryBuilder = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->createQueryBuilder('q');

        $queryBuilder = $queryBuilder->where('q.auteur = :auteur')
                ->setParameter('auteur', $user);

         return $this->render('user/index.html.twig', ['user' => $user, 'quotes' => $queryBuilder->getQuery()->getResult(),]);
    }

    /**
     * @Route("/user", name="user_own_profil")
     * @return Response
     */
    public function ownProfil()
    {
        $user = $this->getUser();
        $queryBuilder = $this->getDoctrine()
            ->getRepository(Quote::class)
            ->createQueryBuilder('q');

        $queryBuilder = $queryBuilder->where('q.auteur = :auteur')
            ->setParameter('auteur', $user);

        return $this->render('user/index.html.twig', ['user' => $user, 'quotes' => $queryBuilder->getQuery()->getResult(),]);
    }
}
