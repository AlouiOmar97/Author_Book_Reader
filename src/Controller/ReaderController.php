<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\ReaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reader')]
class ReaderController extends AbstractController
{
    #[Route('/test', name: 'app_reader_test', methods: ['GET'])]
    public function test(ReaderRepository $readerRepository): Response
    {
       dd($readerRepository->showAllReadersByUsername());
    }

    #[Route('/', name: 'app_reader_index', methods: ['GET'])]
    public function index(ReaderRepository $readerRepository): Response
    {
        return $this->render('reader/index.html.twig', [
            'readers' => $readerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reader_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reader = new Reader();
        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reader);
            $entityManager->flush();

            return $this->redirectToRoute('app_reader_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reader/new.html.twig', [
            'reader' => $reader,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reader_show', methods: ['GET'])]
    public function show(Reader $reader): Response
    {
        return $this->render('reader/show.html.twig', [
            'reader' => $reader,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reader_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reader $reader, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReaderType::class, $reader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reader_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reader/edit.html.twig', [
            'reader' => $reader,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reader_delete', methods: ['POST'])]
    public function delete(Request $request, Reader $reader, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reader->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reader);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reader_index', [], Response::HTTP_SEE_OTHER);
    }
}
