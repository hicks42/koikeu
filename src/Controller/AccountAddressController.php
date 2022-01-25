<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/account/addresses", name="account_addresses")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig', []);
    }

    /**
     * @Route("/account/ajout-adresse", name="account_add_address")
     */
    public function add(Request $request)
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $address->setUser($this->getUser());
            $this->em->persist($address);
            $this->em->flush();
            return $this->redirectToRoute('account_addresses');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/modif-adresse/{id}", name="account_edit_address")
     */
    public function edit(Request $request, $id)
    {
        $address = $this->em->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_addresses');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $this->em->flush();
            return $this->redirectToRoute('account_addresses');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/suppression-adresse/{id}", name="account_delete_address")
     */
    public function delete($id)
    {
        $address = $this->em->getRepository(Address::class)->findOneById($id);

        if ($address || $address->getUser() == $this->getUser()) {
            $this->em->remove($address);
            $this->em->flush();
        }


        return $this->redirectToRoute('account_addresses');
    }
}
