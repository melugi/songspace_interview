<?php

namespace App\Controller;

use App\Entity\Catalog;
use App\Form\CatalogType;
use App\Repository\CatalogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogController extends AbstractController
{
    protected CatalogRepository $catalogRepository;

    public function __construct(CatalogRepository $catalogRepository)
    {
        $this->catalogRepository = $catalogRepository;
    }

    public function index(): Response
    {
        return $this->render('catalog/index.html.twig', [
            'catalogs' => $this->catalogRepository->findAll(),
        ]);
    }

    public function new(Request $request): Response
    {
        $catalog = new Catalog();
        $form = $this->createForm(CatalogType::class, $catalog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($catalog);
            $entityManager->flush();

            return $this->redirectToRoute('index_catalog');
        }

        return $this->render('catalog/new.html.twig', [
            'catalog' => $catalog,
            'form' => $form->createView(),
        ]);
    }

    public function show(string $id): Response
    {
        $catalog = $this->catalogRepository->find($id);
        return $this->render('catalog/show.html.twig', [
            'catalog' => $catalog,
        ]);
    }

    public function edit(Request $request, string $id): Response
    {
        $catalog = $this->catalogRepository->find($id);
        $form = $this->createForm(CatalogType::class, $catalog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('index_catalog');
        }

        return $this->render('catalog/edit.html.twig', [
            'catalog' => $catalog,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, string $id): Response
    {
        $catalog = $this->catalogRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$catalog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($catalog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('index_catalog');
    }
}
