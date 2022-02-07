<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use DateTimeImmutable;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/commande", name="commande")
     */
    public function index(Cart $cart, Request $request, EntityManagerInterface $em)
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_add_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/order.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="commande_recap", methods={"POST"})
     */
    public function recap(Cart $cart, Request $request, EntityManagerInterface $em, $stripeSK)
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTimeImmutable();
            $carrier = $form->get('carrier')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstName() . ' ' . $delivery->getLastName();
            $delivery_content .= '<br/>' . $delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>' . $delivery->getCompany();
            }

            $delivery_content .= '<br/>' . $delivery->getAddress();
            $delivery_content .= '<br/>' . $delivery->getCodePostal() . ' ' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();

            $order = new Order();
            $reference = $date->format('dmy') . '_' . uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery_content);
            $order->setStatus(0);

            $this->em->persist($order);

            // dd($order->getorderDetails()->getValues());

            foreach ($cart->getFull() as $produit) {
                $orderDetails = new OrderDetails();
                $orderDetails->setTheOrder($order);
                $orderDetails->setProduct($produit['produit']->getName());
                $orderDetails->setQuantity($produit['quantity']);
                $orderDetails->setprice($produit['produit']->getPrice());
                $orderDetails->setTotal($produit['produit']->getPrice() * $produit['quantity']);

                $this->em->persist($orderDetails);
            }

            $this->em->flush();

            return $this->render('order/order_add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carrier,
                'delivery' => $delivery_content,
                'reference' => $order->getReference(),
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
