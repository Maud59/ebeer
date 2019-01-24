<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 24/01/19
 * Time: 09:50
 */


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Beer;
use AppBundle\Entity\Category;


class BeersController extends Controller
{

    /**
     * @Route("/beers", name="beers", methods={"GET"})
     */
    public function beerAction()
    {
        $beers=$this->getDoctrine()->getRepository(Beer::class)->findAll();
        foreach($beers as $beer){
            if($beer->getCategory()){
                $category =$beer->getCategory()->getName();
            }
            else{
                $category='';
            }

            $beersOk[]=[
                'id'=>$beer->getId(),
                'name'=>$beer->getName(),
                'prix'=>$beer->getPrix(),
                'category'=>$category,
                'degree'=>$beer->getDegree(),
                'brasseur'=>$beer->getBrasseur(),
                'image'=>$beer->getImage(),
                'description'=>$beer->getDescription(),
                'prix'=>$beer->getPrix(),
                'volume'=>$beer->getVolume(),
                'prixlitre'=>$beer->getPrixlitre()
            ];
        }
        return new JsonResponse($beersOk);
    }

    /**
     * @Route("/addBeer", name="addBeer", methods={"POST"})
     */
    public function addBeerAction(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $variable= json_decode($request->getContent(), true);
        $beer = new Beer();
        $name = $variable['name'];
        $degree = $variable['degree'];
        $brasseur = $variable['brasseur'];
        $image = $variable['image'];
        $description = $variable['description'];
        $prix = $variable['prix'];
        $volume = $variable['volume'];
        $prixlitre = $variable['prixlitre'];
        $categoryId = $variable['category'];

        $category = new Category($this->getDoctrine()->getRepository(Category::class)->find($categoryId));

        $beer->setName($name)
            ->setDegree($degree)
            ->setBrasseur($brasseur)
            ->setImage($image)
            ->setDescription($description)
            ->setPrix($prix)
            ->setVolume($volume)
            ->setPrixlitre($prixlitre)
            ->setCategory($category);

            $category =$beer->getCategory()->getName();

            $beer->setCategory($category);

            $entityManager->persist($beer);
            $entityManager->flush();

        return new Response('Beer ok, id : '.$beer->getId().'Beer ok, id : '.$beer->getCategory());

    }

    /**
     * @Route("/getbeer/{id}", name="getbeer", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function getBeerAction($id)
    {
        $beer = $this->getDoctrine()
            ->getRepository(beer::class)
            ->find($id);

        if($beer->getCategory()){
            $category =$beer->getCategory()->getName();
        }
        else{
            $category='';
        }

        $beerOk[]=[
            'id'=>$beer->getId(),
            'name'=>$beer->getName(),
            'prix'=>$beer->getPrix(),
            'category'=>$category,
            'degree'=>$beer->getDegree(),
            'brasseur'=>$beer->getBrasseur(),
            'image'=>$beer->getImage(),
            'description'=>$beer->getDescription(),
            'prix'=>$beer->getPrix(),
            'volume'=>$beer->getVolume(),
            'prixlitre'=>$beer->getPrixlitre()
        ];
        return new JsonResponse($beerOk);
    }

    /**
     * @Route("/deletebeer/{id}", name="deletebeer", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deleteBeerAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $beer = $this->getDoctrine()
            ->getRepository(beer::class)
            ->find($id);

        $entityManager->remove($beer);
        $entityManager->flush();

        return new Response("remove ok pour l'id :".$id);
    }


    //Route pour modifier une ligne dans notre base user à partir de son id
    /**
     * @Route("/updatebeer/{id}", name="updatebeer", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function updateBeerAction($id,Request $request)
    {
        //Données rentrées dans la $variable
        $variable=json_decode($request->getContent(),true);
        $entityManager = $this->getDoctrine()->getManager();

        $beer = $this->getDoctrine()
            ->getRepository(beer::class)
            ->find($id);

        $beer->setName($variable['name']);
        $beer->setDegree($variable['degree']);
        $beer->setBrasseur($variable['brasseur']);
        $beer->setImage($variable['image']);
        $beer->setDescription($variable['description']);
        $beer->setPrix($variable['prix']);
        $beer->setVolume($variable['volume']);
        $beer->setPrixlitre($variable['prixlitre']);


        $entityManager->flush();

        return new Response("update ok pour l'id :".$id);
    }
}