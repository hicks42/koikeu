<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavController extends AbstractController
{
    /**
     * @Route("/nav", name="nav")]
     */
    public function navbar(CategoryRepository $categoryRepository): Response
    {
        $categories= $categoryRepository->findBy([], ['name' => 'DESC']);
        return $this->render('layout/partials/_nav.html.twig', compact('categories'));
    }

    /**
     * @Route("/carousel", name="carousel")]
     */
    public function carousel(CategoryRepository $categoryRepository): Response
    {
        $categories= $categoryRepository->findBy([], ['name' => 'DESC']);
        // dd($categories);
        return $this->render('layout/partials/_carousel_item.html.twig', compact('categories'));
    }

    /**
     * @Route("/", name="homepage")]
     */
    public function homepage(): Response
    {
        return $this->render('layout/homepage.html.twig');
    }
}
