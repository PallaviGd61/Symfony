<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(#[CurrentUser] ?Registration $user,Request $request, RegistrationRepository $post, UserPasswordHasherInterface $userPasswordHasher): Response
    {        

      if (($user) && !$this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_home');
      }
      else{
            $addForm= new Registration();
            $form= $this->createForm(RegistrationType::class, $addForm);
            $form->handleRequest($request);
            // dd($request->get('registration'));
            if($form->isSubmitted() && $form->isValid()){
                //  $data = $form->getData();
               
                  $addForm->setPassword(
                     $userPasswordHasher->hashPassword(
                            $addForm,
                            $form->get('password')->getData() ) );
                  if ($form->get('author')->getData()){
                      $addForm->setRoles(["ROLE_AUTHOR"]); }
                  else{
                      $addForm->setRoles([]);
                  }
                  $post->save($addForm, true);
                // check if the user is already logged in(admin)
                // add a flash and redirect
                if ($user) {
                  $this->addFlash('success', 'You added a new user');
                  return $this->redirectToRoute('app_userlist');
                } else{
                $this->addFlash('success', 'Your registration is successful');
                return $this->redirectToRoute('app_login');
                }
            }
 
            return $this->render(
             'registration/index.html.twig',
             [
                 'form' => $form
             ]
             );
      }
        
    }
}