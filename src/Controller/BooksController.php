<?php

namespace App\Controller;

use App\Entity\Books;
use App\Entity\Registration;
use App\Form\BookType;
use App\Form\RegistrationType;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class BooksController extends AbstractController
{

    #[Route('/addbooks', name: 'app_addbooks')]
    public function addBook(Request $request, BooksRepository $book): Response
    {
            $bookForm= $this->createForm(BookType::class, new Books());
            $user = $this->getUser();
            // dd($user->getFullName());
            $authorName= $user->getFullName();
            
            $bookForm->handleRequest($request);
            if($bookForm->isSubmitted() && $bookForm->isValid()){
                  $getData = $bookForm->getData();
                  $book->save($getData, true);
                // add a flash
                $this->addFlash('success', 'Your book has been published');
                // Redirect
                return $this->redirectToRoute('app_home');
            }

            return $this->render('books/index.html.twig', [
                'authorName' => $authorName,
                'form' => $bookForm
            ]);
    }

    #[Route('/booklist', name: 'app_booklist')]
    public function bookList(Request $request, BooksRepository $book): Response
    {
            $booklist= $book->findAll();
            return $this->render('books/booklist.html.twig', [
                'booklist' => $booklist
            ]);
        
    }

    #[Route('/booklist/{id}/editbook', name: 'app_editbook')]
    public function editBook(Books $book ,Request $request, BooksRepository $books): Response
    { 
            $loadBook= $this->createFormBuilder($book)
                ->add('book_title')
                ->add('genre', ChoiceType::class,
                [
                'attr' => ['placeholder' => 'Please select your book genre'],
                'choices' => [
                    'Fiction' => 'Fiction',
                    'Non-Fiction' => 'Non-Fiction',
                    'Adventure ' => 'Adventure',
                    'Thriller'  => 'Triller' ,
                    'Horror'   => 'Horror' ,
                    'Others'  => 'Others',
                ],
                ]
                )
                ->add('author')
                ->add('submit' , SubmitType::class , ['label'=> 'Update book'])
                ->getForm();

            $loadBook->handleRequest($request);
            if($loadBook->isSubmitted() && $loadBook->isValid()){
                  $book = $loadBook->getData();
                  $books->save($book, true);
                $this->addFlash('success', 'book has been updated');
                return $this->redirectToRoute('app_booklist');
            }
 
            return $this->render(
             'books/bookEdit.html.twig',
             [
                 'form' => $loadBook
             ]
             );
            }

    #[Route('/booklist/deletebook/{id<\d+>}', name: 'app_book_delete')]
    public function bookDelete(Request $request, EntityManagerInterface $entityManager,int $id): Response

    {
        $fetchBook = $entityManager->getRepository(Books::class)->find($id);
        if (!$fetchBook) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }
        $entityManager->remove($fetchBook);
        $entityManager->flush();
        $this->addFlash('success', 'One book has been successfully deleted');
        return $this->redirectToRoute('app_booklist');
    }
}
