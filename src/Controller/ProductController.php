<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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
        $categories = $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();

        $form= $this->createForm(ProductType::class,$product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            //upload image
            $uploadFile = $form['image']->getData(); // valeur ta3 image (ely how name ta3ha)
            $filename = md5(uniqid()) . '.' .$uploadFile->guessExtension();//crypté image

            $uploadFile->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


            $product->setImage($filename);




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

        return $this->render('product/addProduct.html.twig',array("f"=>$form->createView(),"c"=>$categories));

    }
    /**
     * @Route("/modifier_produit/{id}", name="modification")
     */
    public function modifierProduit(\Symfony\Component\HttpFoundation\Request $request,$id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($id);

        $editForm= $this->createForm(ProductType::class, $product);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /*
           *  it means that the user has been set a new picture
           *  else :
           *  we let the old picture
           */

            $newLogoFile = $product->getImage();
            $fileName = md5(uniqid()) . '.' . $newLogoFile->guessExtension();

            $uploadFile = $editForm['image']->getData(); // valeur ta3 image (ely how name ta3ha)
            $filename = md5(uniqid()) . '.' .$uploadFile->guessExtension();//crypté image

            $uploadFile->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


            $product->setImage($filename);


            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/modifier_produit.html.twig',array("f"=>$editForm->createView()));

        }

    /**
     * @Route("/supprimer_produit/{id}", name="suppression")
     */
    public function  supprimerProduit($id) {
        $em= $this->getDoctrine()->getManager();
        $i = $em->getRepository(Product::class)->find($id);

        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute("product_list");

    }
    /**
     * @Route("/detail_produit/{id}", name="detail")
     */
    public function detailProduit(\Symfony\Component\HttpFoundation\Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->find($id);


        return $this->render('product/detail_produit.html.twig', array(
            'id' => $prod->getId(),
            'name' => $prod->getNom(),
            'prix' => $prod->getPrix(),
            'description' => $prod->getDescription(),
            'color' => $prod->getColeur(),
            'image'=>$prod->getImage(),
            'nameCat'=>$prod->getCategory()->getName()
        ));
    }

    //SEARCH

    /**
     * @Route("/ajax_search/", name="ajax_search")
     */
    public function chercherProduit(\Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');// ooofkdokfdfdf
        $products =  $em->getRepository(Product::class)->rechercheAvance($requestString);
        if(!$products) {
            $result['products']['error'] = "Product non trouvé :( ";
        } else {
            $result['products'] = $this->getRealEntities($products);
        }
        return new Response(json_encode($result));
    }





    // LES  attributs
    public function getRealEntities($products){
        foreach ($products as $products){
            $realEntities[$products->getId()] = [$products->getImage(),$products->getDateCreation(),$products->getNom(),$products->getPrix()];

        }
        return $realEntities;
    }


    //METIER TRAITER

    /**
     * @Route("/traiter/{id}", name="traiter")
     */
    public function traiterProduct($id) {

        $em = $this->getDoctrine()->getManager();
        $etat = $em->getRepository(Product::class)->traiter($id);
        $em->flush();
        return $this->redirectToRoute('product_list');
        }

    /**
     * @Route("/topfive", name="topfive")
     */

    public function  afficherTopfiveProduct() {
        $em = $this->getDoctrine()->getManager();
        $produitEvalues = $em->getRepository(Product::class)->getProduitsEvaluees();

        $note = 0;
        //count
        $i = 0;

        //tableau
        $j=0;

        foreach ($produitEvalues as $pe) {
            $note = $note + $pe["note"];
            $i++;


            $noteMoy = $note / $i;

            $noteMoy = round($noteMoy);

            $prod = $em->getRepository(Product::class)->findOneBy(array('id' => $pe['id']));

            $produitTop[$j] = $prod;

            $j++;
        }
        return $this->render('product/top.html.twig',array('id'=>$pe['id'],'note'=>$pe['note'],'topfive'=>$produitTop));

    }

}
