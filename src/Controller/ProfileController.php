<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher,
    ): Response
    {
       /** @var User $user */
       $user = $this->getUser(); 

       if (!$user) {
           throw $this->createAccessDeniedException('Debes iniciar sesión para editar tu perfil.');
       }

       $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $user->setRoles($data->getRoles());
            $imageFile = $request->files->get('profileImage');

            if ($imageFile) {
                // Aquí puedes eliminar la imagen anterior si existe
                if ($user->getLogo()) {
                    $oldImagePath = $this->getParameter('profile_images_directory') . '/' . $user->getLogo();
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Aquí puedes subir la nueva imagen y actualizar el campo en el usuario
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('profile_images_directory'), $newFilename);
                $user->setLogo($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
       }

       
    
}
