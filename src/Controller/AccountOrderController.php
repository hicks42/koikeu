<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/compte/mes-commandes", name="account_order")
     */
    public function index(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findSuccessOrders($this->getUser());
        // dd($orders);
        return $this->render('account/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/compte/mes-commandes/{reference}", name="account_order_show")
     */
    public function show($reference): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order.html.twig', [
            'order' => $order,
            'reference' => $reference
        ]);
    }
}
