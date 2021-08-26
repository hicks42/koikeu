<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('products/index.html.twig', compact('produits'));
    }
    
    /**
     * @Route("/produits/create", name="app_produits_create", methods={"GET|POST"})
     * @Security("is_granted('ROLE_USER') and user.isVerified()")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
    {
        $produit = new Produit;
    
        $form = $this->createForm(ProduitType::class, $produit);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()){
            $produit->setUser($this->getUser());
            $em->persist($produit);
            $em->flush();
    
            $this->addFlash('success', 'Le nouveau produit a été enregistré');
    
            return $this->redirectToRoute('app_produits_show', ['id' => $produit->getId()]);
        }
        
        return $this->render('products/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produits/{id<\d+>}", name="app_produits_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('products/show.html.twig', compact('produit'));
    }

    /**
     * @Route("/produits/{id<\d+>}/edit", name="app_produits_edit", methods={"GET","PUT"})
     * @Security("is_granted('ROLE_USER') and user.isVerified()")
     */
    public function edit(Request $request, EntityManagerInterface $em, Produit $produit): Response
    {
        $this->denyAccessUnlessGranted('PRODUIT_MANAGE', $produit);

        $form = $this->createForm(ProduitType::class, $produit, [
            'method' => 'PUT'
        ]);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
        $em->flush();
        
        $this->addFlash('success', 'Le produit a été modifié');

        return $this->redirectToRoute('app_home');
        }
    
        return $this->render('products/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produits/{id<\d+>}/delete", name="app_produits_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER') and user.isVerified()")
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('produit_deletion_' . $produit->getId(), $request->request->get('csrf_token'))) {
            $em->remove($produit);
            $em->flush();

            $this->addFlash('info', 'Element deleted');
        }
        return $this->redirectToRoute('app_home');
    }

}
