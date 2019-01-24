<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 24/01/19
 * Time: 09:48
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Category;
use AppBundle\Entity\Beer;


class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category", methods={"GET"})
     */
    public function categoryAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        foreach ($categories as $category) {

            $categoryOk[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }
        return new JsonResponse($categoryOk);
    }

    /**
     * @Route("/addcategory", name="addcategory", methods={"POST"})
     */
    public function addCategoryAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = new Category();
        $variable= json_decode($request->getContent(), true);
        $name = $variable['name'];

        $category->setName($name);



        $entityManager->persist($category);
        $entityManager->flush();
        return new Response('Catégorie ajoutée : '.$category->getId());
    }

    /**
     * @Route("/getcategory/{id}", name="getcategory", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function getCategoryAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository(category::class)
            ->find($id);

        $categoryOk[] =
            'id' => $category->getId(),
            'nom' => $category->getName(),
        ];
        return new JsonResponse($categoryOk);
    }

    /**
     * @Route("/deletecategory/{id}", name="deletecategory", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deleteCategoryAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()
            ->getRepository(category::class)
            ->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        return new Response("remove ok pour l'id :".$id);
    }


    //Route pour modifier une ligne dans notre base user à partir de son id
    /**
     * @Route("/updatecategory/{id}", name="updatecategory", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function updateCategoryAction($id,Request $request)
    {
        //Données rentrées dans la $variable
        $variable=json_decode($request->getContent(),true);
        $entityManager = $this->getDoctrine()->getManager();

        //Je récupère mon user à partir de mon id
        $category = $this->getDoctrine()
            ->getRepository(category::class)
            ->find($id);

        //Je modifie mon user à partir des données rentrées dans $variable
        $category->setName($variable['name']);

        $entityManager->flush();

        return new Response("update ok pour l'id :".$id);
    }

    /**
     * @Route("/getbycategory/{id}", name="getbycategory", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function getByCategoryAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository(category::class)
            ->find($id);

        $categoryOk[] = [
            'id' => $category->getId(),
            'nom' => $category->getName(),
        ];
        return new JsonResponse($categoryOk);
    }
}