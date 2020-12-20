<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\RegisterType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
   
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class , $user);
        // Vamos a crear un formulario, ( elformulariocreado, la esctructura de datos
        // que va a seguir el formulario )


        // Vamos a unir el formulario con la request(datos que envia en user)
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            // Para cifrar la contraseña vamos a utilizar los encoder de 
            // symfony, SYMFONY NO ES BUENO PARA FRONT es un asco, mucho rollo
            // para hacer algo tan sencillo
            
            
            // Nos vamos a config/security crear en encoder
            // despues traer el use de codeinterface
            
            $passencode = $encoder->encodePassword($user, $user->getPassword());
            
            $user->setPassword($passencode);
            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTime('now'));
            // var_dump($user);

            // Guadar usuario
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('task');

        }


        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);

        //$ form->createdView   envia la data del formulario
    }

    public function login(AuthenticationUtils $authenticationUtils)
    {
        // Se trae la la variable authenticationUtils que trae los 
        // datos del user si se logea o el error
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUserName
        ));
    }
}
