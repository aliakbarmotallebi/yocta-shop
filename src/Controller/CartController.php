<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="cart.index")
     */
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }

        /**
     * @Route("/add/{id}", name="cart.add")
     */
    public function add($id, CartService $cartService) 
    {
      $cartService->add($id);
          
      return $this->redirectToRoute('main');
   }

   /**
    *  @Route("/remove/{id}", name="cart.remove")
    */

   public function remove($id, CartService $cartService) 
   {
       $cartService->remove($id);
      
       return $this->redirectToRoute("cart.index");

   }
}
