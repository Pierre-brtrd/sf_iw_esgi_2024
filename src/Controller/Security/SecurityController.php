<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'error' => $authUtils->getLastAuthenticationError(),
            'lastUsername' => $authUtils->getLastUsername(),
        ]);
    }

    #[Route('/register', name: 'app.security.register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        // On instancie l'objet qu'on souhaite enregistrer
        $user = new User();

        // On crée le formulaire
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // On vérifie si le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // On hash le mot de passe (Seulement pour les users)
            $user
                ->setPassword(
                    $hasher->hashPassword($user, $form->get('password')->getData())
                );

            // On persist et flush
            $em->persist($user);
            $em->flush();

            // On défini un message flash et on redirige
            $this->addFlash('success', 'Votre compte a bien été créé !');

            return $this->redirectToRoute('app.security.login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form,
        ]);
    }
}
