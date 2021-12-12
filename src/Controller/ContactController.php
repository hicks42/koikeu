<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\SendMailService;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, SendMailService $mail)
    {
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);
        $sujet = $contact->get('Sujet')->getData();
        $produit = $request->getSession()->getFlashBag('produit');

        if ($form->isSubmitted() && $form->isValid()) {
            $context = [
                'nom' => $contact->get('Nom')->getData(),
                'prenom' => $contact->get('Prenom')->getData(),
                'phone' => $contact->get('Phone')->getData(),
                'mail' => $contact->get('Email')->getData(),
                'sujet' => $contact->get('Sujet')->getData(),
                'message' => $contact->get('Message')->getData(),
                // 'produit' => $contact->get('Produit')->getData(),
            ];
            $mail->send(
                $contact->get('Email')->getData(),
                'fanny@koikeu.fr',
                // 'gerin.patrice@yahoo.fr',
                'Mail depuis le site koikeu.fr : '. $sujet ,
                'contact',
                $context
            );
            dd($produit);

            $this->addFlash('success', 'Votre mail a bien été envoyé');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
