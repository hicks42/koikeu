<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\throwException;

class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $em, Cart $cart, $stripeSK, $reference)
    {
        $stripe_products = [];
        $YOUR_DOMAIN = 'http://localhost:8080';

        $order = $em->getRepository(Order::class)->findOneByReference($reference);
        if (empty($order)) {
            return $this->render('bundles/Stripe/cancel.html.twig', []);
        } else {
            foreach ($order->getOrderDetails()->getValues() as $produit) {
                $produit_object = $em->getRepository(Produit::class)->findOneByName($produit->getProduct());
                $stripe_products[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $produit->getPrice(),
                        'product_data' => [
                            'name' => $produit->getProduct(),
                            'images' => [$YOUR_DOMAIN . '/images/products/' . $produit_object->getImageName()],
                        ],
                    ],
                    'quantity' => $produit->getQuantity(),
                ];
            }
            $stripe_products[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $order->getCarrierPrice(),
                    'product_data' => [
                        'name' => $order->getCarrierName(),
                        'images' => [$YOUR_DOMAIN],
                    ],
                ],
                'quantity' => 1,
            ];

            // header('Content-Type: application/json');
            Stripe::setApiKey($stripeSK);

            $checkout_session = Session::create([
                // 'payment_method_types' => ['card'],
                'line_items' => [$stripe_products],
                'mode' => 'payment',
                // 'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
                // 'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),

                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',

            ]);

            // dd($checkout_session->id);

            $order->setStripeSessionId($checkout_session->id);
            // dd($order); #works
            $em->flush();

            // header("HTTP/1.1 303 See Other");
            // header("Location: " . $checkout_session->url);
            // slim return $response->withHeader('Location', $session->url)->withStatus(303);

            return $this->redirect($checkout_session->url, 303);
        }
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
