<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product_list")
     */
    public function index(): Response
    {

        $res = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();
        return $this->render('product/listProduct.html.twig',array('produits'=>$res));
    }


    /**
     * @Route("/addProduct", name="add")
     */
    public function addProduct(\Symfony\Component\HttpFoundation\Request  $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = new Product();
        $form= $this->createForm(ProductType::class,$product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime('@'.strtotime('now'));
            /*
             * Add product
             */
            $em->persist($product);
            /*
             * Commit
             */
            $em->flush();

           return  $this->redirectToRoute('product_list');

        }

        return $this->render('product/addProduct.html.twig',array("f"=>$form->createView()));

    }
}
