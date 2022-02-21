<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/list_categories", name="list_categories")
     */
    public function index(): Response
    {

        $res = $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
        return $this->render('client/categorie/listCategorie.html.twig',array('categories'=>$res));
    }


    /**
     * @Route("/add_category", name="add")
     */
    public function addCategory(\Symfony\Component\HttpFoundation\Request  $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $Category = new Category();
        $form= $this->createForm(CategoryType::class,$Category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            //upload image
            $uploadFile = $form['image']->getData(); // valeur ta3 image (ely how name ta3ha)
            $filename = md5(uniqid()) . '.' .$uploadFile->guessExtension();//crypté image

            $uploadFile->move($this->getParameter('kernel.project_dir').'/public/uploads/categorie_image',$filename);


            $Category->setImage($filename);



            $date = new \DateTime('@'.strtotime('now'));
            /*
             * Add Category
             */
            $em->persist($Category);
            /*
             * Commit
             */
            $em->flush();

            return  $this->redirectToRoute('Category_list');

        }

        return $this->render('Category/addCategory.html.twig',array("f"=>$form->createView()));

    }
    /**
     * @Route("/modifier_category/{id}", name="modification")
     */
    public function modifierCategory(\Symfony\Component\HttpFoundation\Request $req, $id) {
        $em= $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class,$prod);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('Category_list');

        }

        return $this->render('Category/modifier_Category.html.twig',array("f"=>$form->createView()));


    }

    /**
     * @Route("/supprimer_category/{id}", name="suppression")
     */
    public function  supprimerCategory($id) {
        $em= $this->getDoctrine()->getManager();
        $i = $em->getRepository(Category::class)->find($id);

        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute("Category_list");

    }
    /**
     * @Route("/detail_category/{id}", name="detail")
     */
    public function detailCategory(\Symfony\Component\HttpFoundation\Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Category::class)->find($id);


        return $this->render('Category/detail_Category.html.twig', array(
            'id' => $prod->getId(),
            'name' => $prod->getNom(),
            'prix' => $prod->getPrix(),
            'description' => $prod->getDescription(),
            'color' => $prod->getColeur(),
            'image'=>$prod->getImage()
        ));
    }

    // SHOW PRODUCTS BY CATEGORY ID BECAUES JJOINTURE:

    /**
     * @Route("/show_products_cat/{id}", name="show_products")
     */
    public function showProducts(\Symfony\Component\HttpFoundation\Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Category::class)->find($id);//voiture

       // var_dump($categorie); die();

        $product = $em->getRepository(Product::class)->findBy(['category'=>$categorie]);

         /*foreach ($product as $p) {

         }
         */







        return $this->render('client/categorie/showProductsByCat.html.twig', array('products'=>$product,'namecar'=>$categorie->getName()

        ));
    }
}
