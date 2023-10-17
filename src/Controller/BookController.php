<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/test', name: 'app_book_test', methods: ['GET'])]
    public function test(BookRepository $bookRepository,EntityManagerInterface $em)
    {
        //dd($bookRepository->showAllBooksByAuthor(1));
        //dd($bookRepository->showAllBooksByAuthor3(20,'2023-01-01'));
        $bookRepository->updateBooksCategoryByAuthorEmail("author1@ts.tn","Tesst");
        //$em->flush();
        dd("ss");
    }

    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/j', name: 'app_book_indexj', methods: ['GET'])]
    public function indexj(BookRepository $bookRepository): Response
    {
        return $this->render('book/index2.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor2(),
        ]);
    }

    #[Route('/j2', name: 'app_book_indexj2', methods: ['GET'])]
    public function indexj2(BookRepository $bookRepository): Response
    {
        return $this->render('book/index3.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor3(35,'2023-01-01'),
        ]);
    }

    #[Route('/j3', name: 'app_book_indexj3', methods: ['GET'])]
    public function indexj3(BookRepository $bookRepository): Response
    {
        return $this->render('book/index3.html.twig', [
            'books' => $bookRepository->showAllBooksByAuthor3(20,'2023-01-01'),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
