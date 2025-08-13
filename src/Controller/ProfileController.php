<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,
    EntityManagerInterface $em,
    UserPasswordHasherInterface $passwordHasher,
    SluggerInterface $slugger): Response
    {
       /** @var User $user */
       $user = $this->getUser(); // ✅ Usuario actual

       if (!$user) {
           throw $this->createAccessDeniedException('Debes iniciar sesión para editar tu perfil.');
       }
       $form = $this->createForm(ProfileType::class, $user);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           // Manejar contraseña
           $plainPassword = $form->get('password')->getData();
           if ($plainPassword) {
               $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
               $user->setPassword($hashedPassword);
           }

           // Manejar logo si se sube
           $logoFile = $form->get('logo')->getData();
           if ($logoFile) {
               $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
               $safeFilename = $slugger->slug($originalFilename);
               $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

               try {
                   $logoFile->move(
                       $this->getParameter('profile_images_directory'), // Definir en services.yaml
                       $newFilename
                   );
               } catch (FileException $e) {
                   $this->addFlash('error', 'Error al subir el logo');
               }

               $user->setLogo($newFilename);
           }

           $em->persist($user);
           $em->flush();

           $this->addFlash('success', 'Perfil actualizado correctamente');

           return $this->redirectToRoute('app_profile');
       }

       return $this->render('user/profile/edit.html.twig', [
           'form' => $form->createView(),
       ]);
    }
}
