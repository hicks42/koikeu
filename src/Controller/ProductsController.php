<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Category;
use App\Form\ProduitType;
use App\Entity\Attachment;
use App\Repository\UserRepository;
use Symfony\Component\WebLink\Link;
use App\Repository\ProduitRepository;
use App\Repository\CategoryRepository;
use App\Repository\AttachmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/produits")
 */
class ProductsController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_produits", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('products/index.html.twig', compact('produits'));
    }

    // /**
    //  * @Route("/{id<\d+>}", name="app_produits_show", methods={"GET"})
    //  */
    // public function show(Produit $produit): Response
    // {
    //     return $this->render('products/show.html.twig', compact('produit'));
    // }

    /**
     * @Route("/{slug}", name="app_produits_show", methods={"GET"})
     */
    public function show($slug, Request $request, CategoryRepository $catrepo, AttachmentRepository $attrepo): Response
    {
        // $this->addLink($request, new Link('preload', '/build/js_image_zoom.js')); !!!! unverified !!!!

        $produit = $this->em->getRepository(Produit::class)->findBySlug($slug);

        if(!$produit){
            return $this->redirectToRoute('app_home');
        }
        $produit = $produit[0];
        $prod_id = $produit->getId();
        $attachments = $attrepo->findBy(
            ['produit' => $prod_id]
        );

        return $this->render('products/show.html.twig', compact('produit', 'attachments'));
    }

    /**
     * @Route("/categorie/{slug}", name="app_produits_by_category", methods={"GET"})
     */
    public function index_by_category($slug, ProduitRepository $produitRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        // $category = $request->attributes->get('category');
        $category_array =  $this->em->getRepository(Category::class)->findBySlug($slug);
        $category = $category_array[0];
        // dd($category);
        $category_id = $category->getId();
        $produits = $produitRepository->findBy(['category' => $category_id], [
            'createdAt' => 'DESC',
        ]);
        return $this->render('products/index.html.twig', compact('produits', 'category', 'slug'));
    }

    /**************************************************************************
    /**************************************************************************
    /**************************************************************************

    /**
     * @Route("/create", name="app_produits_create", methods={"GET|POST"})
    * @Security("is_granted('ROLE_USER') and user.isVerified()")
    */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
    {
        $produit = new Produit;
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $produit->setUser($this->getUser());

            $attachments = $produit->getAttachments();
            foreach ($attachments as $key => $attachment) {
                $attachment->setProduit($produit);
                $attachments->set($key, $attachment);
            }

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
     * @Route("/{id<\d+>}/edit", name="app_produits_edit", methods={"GET","PUT"})
         * @Security("is_granted('ROLE_USER') and user.isVerified()")
         */
        public function edit(Request $request, EntityManagerInterface $em, Produit $produit): Response
        {
            // $this->denyAccessUnlessGranted('PRODUIT_MANAGE', $produit);

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
     * @Route("/{id<\d+>}/delete", name="app_produits_delete", methods={"DELETE"})
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
