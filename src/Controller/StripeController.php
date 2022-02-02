<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session", name="stripe_create_session")
     */
    public function index(Cart $cart, $stripeSK)
    {
        $stripe_products = [];
        $YOUR_DOMAIN = 'http://localhost:8080';

        foreach ($cart->getFull() as $produit) {
            $stripe_products[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produit['produit']->getPrice(),
                    'product_data' => [
                        'name' => $produit['produit']->getName(),
                        'images' => [$YOUR_DOMAIN . '/images/products/' . $produit['produit']->getImageName()],
                    ],
                ],
                'quantity' => $produit['quantity'],
            ];
        }

        // header('Content-Type: application/json');
        Stripe::setApiKey($stripeSK);

        $checkout_session = Session::create([
            // 'payment_method_types' => ['card'],
            'line_items' => [$stripe_products],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
        //slim return $response->withHeader('Location', $session->url)->withStatus(303);

        return $this->redirect($checkout_session->url, 303);
    }

    /**
     * @Route("success_url", name="success_url")
     */
    public function successUrl(): Response
    {
        return $this->render('bundles/Stripe/success.html.twig', []);
    }

    /**
     * @Route("cancel_url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('bundles/Stripe/cancel.html.twig', []);
    }
}
