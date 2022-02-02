<?php

namespace App\Classe;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
  public function __construct(SessionInterface $session, EntityManagerInterface $em)
  {
    $this->session = $session;
    $this->em = $em;
  }

  public function add($id)
  {
    $cart = $this->session->get('cart', []);

    if (!empty($cart[$id])) {
      $cart[$id]++;
    } else {
      $cart[$id] = 1;
    }

    $this->session->set('cart', $cart);
  }

  public function decrease($id)
  {
    $cart = $this->session->get('cart', []);

    if ($cart[$id] > 1) {
      $cart[$id]--;
    } else {
      unset($cart[$id]);
    }

    $this->session->set('cart', $cart);
  }

  public function get()
  {
    return $this->session->get('cart');
  }

  public function getFull()
  {
    // dd($this->get());
    $cartComplete = [];
    if ($this->get()) {
      foreach ($this->get() as $id => $quantity) {
        $product_object = $this->em->getRepository(Produit::class)->findOneById($id);

        if (!$product_object) {
          $this->delete($id);
          continue;
        }

        $cartComplete[] = [
          'produit' => $product_object,
          'quantity' => $quantity
        ];
      }
    }
    return $cartComplete;
  }

  public function remove($id)
  {
    $cart = $this->session->get('cart', []);

    unset($cart[$id]);

    $this->session->set('cart', $cart);
  }

  public function delete()
  {
    $this->session->remove('cart');
  }
}
