<?php

namespace App\Controller;

use App\Service\MessageGenerator;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(ProductRepository $product): Response
    {
		$products = $product->findAll();
        
        // $this->get("flasher")->addSuccess('Data has been saved successfully!');
		
        return $this->render('main/index.html.twig', [
            'products' => $products,
        ]);
    }
}
