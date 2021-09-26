<?php namespace App\Service\Cart;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {


    private const CART_NAME = "SHOP";

    private $session;

    private $product;

    public function __construct(SessionInterface $session, ProductRepository $pr)
    {
        $this->session = $session;
        $this->product = $pr;
    }


    public function add(int $id)
    {
        $cart=  $this->session->get(self::CART_NAME, []);

        if(! empty($cart[$id]) ) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set(self::CART_NAME, $cart);
    }

    public function remove(int $id) {

        $cart = $this->session->get(self::CART_NAME,[]);

        if(!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set(self::CART_NAME , $cart);
    }

    
    function getFullCart() : array {
        $cart =  $this->session->get(self::CART_NAME, []);
        $cartWithData = [];

        foreach($cart as $id => $quantity) {
              $cartWithData[] = [
                  'product'  => $this->product->find($id),
                  'quantity' => $quantity
              ];

        }
        return $cartWithData;
    }
	
	
    public function getTotal() : float
     {
        $total = 0;

        foreach($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
            
        } 
        return $total;
    }
	
	
	public function numberOfProductsInCart()
	{
		return count($this->getFullCart());
	}


}