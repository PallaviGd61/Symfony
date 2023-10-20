<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    #[Route('/userlist', name: 'app_userlist')]
    
    public function userList(RegistrationRepository $users): Response
    {
        $fetchUsers= $users->findAll();
        return $this->render('admin/index.html.twig', [
            'fetchUsers' => $fetchUsers
        ]);
    }

    #[Route('/userlist/{user}/edituser', name: 'app_edituser')]

    public function editUser(Registration $user ,Request $request, RegistrationRepository $users): Response
    { 
            $loadForm= $this->createFormBuilder($user)
                ->add('full_name')
                ->add('username')
                ->add('email')
                // ->add('password', PasswordType::class)
                ->add('submit' , SubmitType::class , ['label'=> 'Update details'])
                ->getForm();

            $loadForm->handleRequest($request);
            if($loadForm->isSubmitted() && $loadForm->isValid()){
                  $user = $loadForm->getData();
                  $users->save($user, true);
                $this->addFlash('success', 'user data has been updated,Login Again');
                return $this->redirectToRoute('app_userlist');
            }
 
            return $this->render(
             'admin/userEdit.html.twig',
             [
                 'form' => $loadForm
             ]
             );
    }

    #[Route('/userlist/userdelete/{id<\d+>}', name: 'app_user_delete')]
    public function deleteUser( Request $request, EntityManagerInterface $entityManager,int $id): Response
    {
        // $userId= $request->query->get('id');
        // // dd($userId);
        // $fetchUser= $users->find(Registration::class, $userId);
        // $users->remove($fetchUser);
        // $users->flush();
        // $this->addFlash('success', 'user has been successfully deleted');
        // return $this->redirectToRoute('app_userlist');
        $fetchUser = $entityManager->getRepository(Registration::class)->find($id);
        if (!$fetchUser) {
            throw $this->createNotFoundException(
                'No User found for id '.$id
            );
        }
        $entityManager->remove($fetchUser);
        $entityManager->flush();
        $this->addFlash('success', 'Uer has been successfully deleted');
        return $this->redirectToRoute('app_userlist');

    }


}
