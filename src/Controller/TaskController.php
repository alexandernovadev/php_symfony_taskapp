<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    public function index()
    {

        // Traer el entity manager
        $em = $this->getDoctrine()->getManager();
        $taks_repo =  $this->getDoctrine()->getRepository(Task::class);

        $tasks = $taks_repo->findBy([], ['id'=> 'DESC']);

        // foreach($tasks as $task)
        // {
        //     echo $task->getUser()->getEmail()."->".$task->getTitle()."<br>";
        // }


        // $user_repo = $this->getDoctrine()->getRepository(User::class);

        // $users = $user_repo->findAll();

        // foreach($users as $user)
        // {
        //     echo "<h1>{$user->getName()}  {$user->getSurname()}</h1>";

        //     foreach($user->getTask() as $task)
        //     {
        //         echo "<p>".$task->getTitle()."</p>"."<br>";
        //     }
        // }

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    public function detail(Task $task)
    {
        if(!$task)
        {
            return $this->redirectToRoute('task');
        }

        return $this->render('task/detail.html.twig',[
            'task'=> $task
        ]);
    }

    public function edit_view(Task $task)
    {
        if(!$task)
        {
            return $this->redirectToRoute('task');
        }

        return $this->render('task/edit.html.twig',[
            'task'=> $task
        ]);
    }

    public function editar(Request $request,UserInterface $user)
    {
        // var_dump($request->query); die();
        // Esto toca hacerlo con un formbluider, pero yo me ahorre
        // o no me gustaba como se hace, asi que le hize con una request
        // saque los parametros y breve, la vaina es la VALIDACION
        $ide =  $request->query->get('id_task');  
        $titulo = $request->query->get('titulo');
        $content = $request->query->get('content');
        $priority = $request->query->get('priority'); 
        $horas = $request->query->get('horas');

        $em = $this->getDoctrine()->getManager();
        // Hacer validacion que me da pereza 

        $task = $em->getRepository(Task::class)->find($ide);

        $task->setTitle($titulo);
        $task->setContent($content);
        $task->setUser($user);
        $task->setCreatedAt(new \DateTime('now'));
        $task->setPriority($priority);
        $task->setHours($horas);
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('task');
    }

    public function crear_view()
    {
        return $this->render('task/crear.html.twig');
    }

    public function crear(Request $request,UserInterface $user)
    {
        
        // var_dump($user); die();
        $titulo = $request->query->get('titulo');
        $content = $request->query->get('content');
        $priority = $request->query->get('priority');
        $horas = $request->query->get('horas');

        // Hacer validacion que me da pereza 


        $task = new Task();

        $task->setTitle($titulo);
        $task->setContent($content);
        $task->setUser($user);
        $task->setCreatedAt(new \DateTime('now'));
        $task->setPriority($priority);
        $task->setHours($horas);
        $em = $this->getDoctrine()->getManager();

        $em->persist($task);
        $em->flush();


        return $this->redirectToRoute('task');
    }

    public function delete($id)
    {
        // var_dump($id); die();
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('task');
    }   

    public function mistareas(UserInterface $user)
    {
        $tasks = $user->getTask();

        return $this->render('task/mistask.html.twig',[
            'tasks'=> $tasks
        ]);
    }
}
