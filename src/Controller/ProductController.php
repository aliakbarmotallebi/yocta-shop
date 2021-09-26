<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("dashboard/product", name="product.")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(ProductRepository $product): Response
    {
        $products = $product->findAll();
		
        return $this->render('dashboard/product/index.html.twig', [
            'products' => $products,
        ]);
    
    }

    /**
     * @Route("/create", name="create")
     */

    public function create(Request $request)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $image = $request->files->get('product')['image'];
            if($image){
                $newFilename = md5(uniqid()).'.'.$image->guessClientExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {}
            }

            $product->setImage($newFilename);
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', "Successfully Create new product");

            return $this->redirect($this->generateUrl('product.index'));
        }

        return $this->render('dashboard/product/create.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /** 
     * @Route("/delete/{id}", name="destory")
    */
    public function destory($id, ProductRepository $pr)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $pr->find($id);
        $title = $product->getTitle();
        $em->remove($product);
        $em->flush();

        $this->addFlash('danger', "Remove Product {$title}");

        return $this->redirect($this->generateUrl('product.index'));

    }
}
