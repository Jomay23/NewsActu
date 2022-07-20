<?php 

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

       /**
        * @Route("/inscription", name= "user_register", methods={"GET|POST"})
        */
        public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
{     
        #1 instanciation de class
       $user = new User();

    #2 creation du form
    $form = $this->createForm(RegisterFormType::class, $user )
    ->handleRequest($request);


    #4 si le form est soumis et valide 
    if($form->isSubmitted() && $form->isValid()) {
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());


        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', "vous vous êtes inscrit avec succès !");
        return $this->redirectToRoute('app_login');
       
    }

    #3 on retourne la vue du formulaire

    return $this->render("user/register.html.twig", [
        'form' =>$form->createView()
    ]);
    
}


}