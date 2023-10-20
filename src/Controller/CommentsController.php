<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Books;
use App\Form\CommentsType;
use App\Repository\BooksRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/comments/{id<\d+>}', name: 'app_comments')]
    public function index(Request $request, CommentsRepository $comment,EntityManagerInterface $entityManager,int $id): Response
    {
         $addForm= new Comments();
        // $commentForm= $this->createForm(CommentsType::class, new Comments());
        $commentForm= $this->createForm(CommentsType::class, $addForm);
        $fetchId = $entityManager->getRepository(Books::class)->find($id);
        if (!$fetchId) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }          
            
             $bookName= $fetchId->getBookTitle();
              $addForm->setBook($fetchId);

            $commentForm->handleRequest($request);
            if($commentForm->isSubmitted() && $commentForm->isValid() ){
                  $getData = $commentForm->getData();
                  $comment->save($getData, true);

                // add a flash
                $this->addFlash('success', 'Thank you for your valuable feedback');
                // Redirect
                return $this->redirectToRoute('app_home');
            }


            // $addForm= new Comments();
            // $form= $this->createFormBuilder($addForm)
            //     ->add('Description')
            //     ->add('submit' , SubmitType::class , ['label'=> 'save'])
            //     ->getForm();
            // $addForm->setBook($fetchId);
            // $form->handleRequest($request);
            // if($form->isSubmitted() && $form->isValid() ){
            //       $getData = $form->getData();
            //       $comment->save($getData, true);

            //     // add a flash
            //     $this->addFlash('success', 'form has been submitted');
            //     // Redirect
            //     return $this->redirectToRoute('app_home');
            // }

        return $this->render('comments/index.html.twig', [
            'form' => $commentForm,
            'bookName'=>$bookName
        ]);
    }
    // list of all comments on every book
    #[Route('/viewcomments', name: 'app_view_comments')]
    public function viewComments(CommentsRepository $commentRepo, EntityManagerInterface $entityManager){
        $fetchComments= $entityManager->getRepository(Comments::class)->findAll();
        return $this->render('comments/viewComments.html.twig', [
            'message' => 'View all comments',
            'comments'=> $fetchComments
        ]);
        
    }

    //view comments on a particular book
    #[Route('/bookreview/{id}', name: 'app_book_review')]
    public function viewBookReview(CommentsRepository $commentRepo, EntityManagerInterface $entityManager, int $id){
        $fetchData = $entityManager->getRepository(Comments::class)->findBy(['book' => $id]);
        if (!$fetchData) {
            throw $this->createNotFoundException(
                'No reviews found for id '.$id
            );
        }          

        return $this->render('comments/bookReview.html.twig', [
            'message' => 'Reviews on this book',
            'review' => $fetchData,
            'bookId'=> $id
        ]);
        
    }

    #[Route('/deletereview{id}', name: 'app_review_delete')]
        public function deleteComments(Request $request, EntityManagerInterface $entityManager,int $id): Response

        {
        $fetchReview = $entityManager->getRepository(Comments::class)->find($id);
        if (!$fetchReview) {
            throw $this->createNotFoundException(
                'No review found for id '.$id
            );
        }
        $entityManager->remove($fetchReview);
        $entityManager->flush();
        $this->addFlash('success', 'Comment has been successfully deleted');
        return $this->redirectToRoute('app_booklist');
        
    }



}
