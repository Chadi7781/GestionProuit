<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class ApiController extends  AbstractController
{


    /**
     * @Route("/api/addprod", name="addprod")
     */
    public function addProduct(Request $request) {

        $em = $this->getDoctrine()->getManager();


        // $category_id = $request->get("category_id");
        $Nom = $request->get("Nom");
        $prix = $request->get("prix");
        $description = $request->get("description");
        $coleur = $request->get("coleur");
        $dateCreation = new \DateTime(urldecode($request->get("dateCreation")));


        // $category = $em->getRepository(Category::class)->find($category_id);
        $product = new Product();
        $product->setNom($Nom);
        $product->setPrix($prix);
        $product->setDescription($description);
        $product->setColeur($coleur);
        $product->setDateCreation($dateCreation);

        $product->setImage($request->get("image"));
        if($request->files->get("image") !=null) {
            $file = $request->files->get("image");
            $fileName = $file->getClientOriginalName();

            $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image

            $file->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


            $product->setImage($filename);



        }
        $em->persist($product);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($product);

        return new JsonResponse($formatted);





    }

    /**
     * @Route("/api/modifprod", name="modifprod")
     */
    public function updateProduct(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $idprod = $request->get("id");
        $prod= $em->getRepository(Product::class)->find($idprod);

        $Nom = $request->get("Nom");
        $prix = $request->get("prix");
        $description = $request->get("description");
        $coleur = $request->get("coleur");
        $dateCreation = new \DateTime(urldecode($request->get("dateCreation")));


        // $category = $em->getRepository(Category::class)->find($category_id);


        $prod->setNom($Nom);
        $prod->setPrix($prix);
        $prod->setDescription($description);
        $prod->setColeur($coleur);
        $prod->setDateCreation($dateCreation);

        $prod->setImage($request->get("image"));
        if($request->files->get("image") !=null) {
            $file = $request->files->get("image");
            $fileName = $file->getClientOriginalName();

            $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image

            $file->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


            $prod->setImage($filename);



        }
        $em->persist($prod);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

      //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Product update with success");





    }

    /**
     * @Route("/api/deleteprod/{id}", name="deleteprod")
     */
    public function deleteProd($id) {
        $em= $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->find($id);
        $em->remove($prod);
        $em->flush();
        return new JsonResponse("Product deleted .");

    }

    /**
     * @Route("/api/affichProd", name="affichProd")
     */
    public function afficheProd() {
        $em= $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Product::class)->findAll();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        //JOIN ERROR
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            if(method_exists($object, 'getId')){
                return $object->getId();
            }
        });


          $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($prod);


        return new JsonResponse($formatted);


    }









}