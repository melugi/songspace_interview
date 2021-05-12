<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongType;
use App\Repository\SongRepository;
use App\Manager\SongFileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SongController extends AbstractController
{
    protected SongRepository $songRepository;
    protected SongFileManager $songFileManager;

    public function __construct(SongRepository $songRepository, SongFileManager $songFileManager)
    {
        $this->songRepository = $songRepository;
        $this->songFileManager = $songFileManager;
    }

    public function index(): Response
    {
        return $this->render('song/index.html.twig', [
            'songs' => $this->songRepository->findAll(),
        ]);
    }

    # TODO: This doesn't support file upload yet
    public function new(Request $request): Response
    {
        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $songFile = $form->get('file')->getData();

            if ($songFile) {
                $songFileName = $this->songFileManager->upload($songFile);
                $song->setSongFileName($songFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($song);
            $entityManager->flush();

            return $this->redirectToRoute('index_song');
        }

        return $this->render('song/new.html.twig', [
            'song' => $song,
            'form' => $form->createView(),
        ]);
    }

    public function show(string $id): Response
    {
        $song = $this->songRepository->find($id);
        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    public function edit(Request $request, string $id): Response
    {
        $song = $this->songRepository->find($id);
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('index_song');
        }

        return $this->render('song/edit.html.twig', [
            'song' => $song,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, string $id): Response
    {
        $song = $this->songRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$song->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($song);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index_song');
    }
}
