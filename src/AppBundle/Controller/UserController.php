<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 24/01/19
 * Time: 09:46
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\User;


class UserController extends Controller
{

    /**
     * @Route("/users", name="users", methods={"GET"})
     */
    public function usersAction(Request $request)
    {
        $users = $this->getDoctrine()->getRepository(user::class)->findAll();
        foreach ($users as $user) {
            $usersOk[] = [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'date' => $user->getDate(),
                'telephone' => $user->getTelephone(),
            ];
        }
        return new JsonResponse($usersOk);
    }


    /**
     * @Route("/adduser", name="user", methods={"POST"})
     */
    public function addUsersAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $variable= json_decode($request->getContent(), true);
        $nom = $variable['nom'];
        $prenom = $variable['prenom'];
        $email = $variable['email'];
        $password = $variable['password'];
        $date = $variable['date'];
        $telephone = $variable['telephone'];

        $user->setNom($nom)
            ->setPrenom($prenom)
            ->setEmail($email)
            ->setPassword($password)
            ->setDate($date)
            ->setTelephone($telephone);


        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('User ajouté : '.$user>getId());
    }

    /**
     * @Route("/getuser/{id}", name="getuser", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(user::class)
            ->find($id);

        $usersOk[] = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'date' => $user->getDate(),
            'telephone' => $user->getTelephone(),
        ];
        return new JsonResponse($usersOk);
    }

    /**
     * @Route("/deleteuser/{id}", name="deleteuser", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deleteUserAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(user::class)
            ->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response("remove ok pour l'id :".$id);
    }


    //Route pour modifier une ligne dans notre base user à partir de son id
    /**
     * @Route("/updateuser/{id}", name="updateuser", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function updateUserAction($id,Request $request)
    {
        //Données rentrées dans la $variable
        $variable=json_decode($request->getContent(),true);
        $entityManager = $this->getDoctrine()->getManager();

        //Je récupère mon user à partir de mon id
        $user = $this->getDoctrine()
            ->getRepository(user::class)
            ->find($id);

        //Je modifie mon user à partir des données rentrées dans $variable
        $user->setNom($variable['nom']);
        $user->setPrenom($variable['prenom']);
        $user->setEmail($variable['email']);
        $user->setPassword($variable['password']);
        $user->setDate($variable['date']);
        $user->setTelephone($variable['telephone']);

        $entityManager->flush();

        return new Response("update ok pour l'id :".$id);
    }

}