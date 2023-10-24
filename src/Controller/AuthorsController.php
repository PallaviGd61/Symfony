<?php

namespace App\Controller;

use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorsController extends AbstractController
{
    // #[Route('/authors', name: 'app_authors')]
    // public function index(): Response
    // {
    //     return $this->render('authors/index.html.twig', [
    //         'controller_name' => 'AuthorsController',
    //     ]);
    // }

    // #[Route('/viewbooks', name: 'app_view_books')]
    // public function viewBook(Request $request, BooksRepository $books): Response
    // {
        
    //     return $this->render('authors/index.html.twig', [
    //         'controller_name' => 'AuthorsController',
    //     ]);
    // }

}
