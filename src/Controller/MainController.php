<?php

namespace App\Controller;

use App\Entity\Todolist;
use App\Form\TodoType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class MainController extends AbstractController
{
    #[Route('/main', name: 'main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $data = $doctrine->getRepository(Todolist::class)->findAll();
        return $this->render('main/index.html.twig', [
            'list'=>$data
        ]);
    }

 
    #[Route('/create', name: 'create')]
    public function create(Request $request,ManagerRegistry $doctrine):Response{
        $todolist = new Todolist();
        $form = $this->createForm(TodoType::class, $todolist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($todolist);
            $em->flush();
            $this->addFlash('notice','Submitted');

            return $this->redirectToRoute('main');
        }

        return $this->render('main/create.html.twig', ['form' => $form->createView()
        ]);
    }
    

}
