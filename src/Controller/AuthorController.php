<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    #[Route('/Author', name: 'app_Author')]
    public function listAuthor(AuthorRepository $repo)
    {       
            return $this->render("/Author/showlist.html.twig",[
            'Authors'=> $repo->findAll()
            ]);
    }

    #[Route('/service/go', name: 'app_service_go',priority:1)]
    public function goToIndex ()
    {
        return $this->redirectToRoute('app_Home');
    }


    #[Route('/Author/add', name: 'app_add_author', methods:['POST','GET','PATCH'])]
    public function createAuthor(Request $request,ManagerRegistry $manager)
    {
        $Author = new Author;
        $form = $this->createForm(AuthorType::class,$Author);

        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
             
            $em = $manager->getManager();
            $em->persist($Author);
            $em->flush();

            return $this->redirectToRoute('app_Author');
        }

       return $this->render('/Author/createA.html.twig',[
        'monFormulaire' => $form->createView()
       ]);
    }


    #[Route('/Author/edit/{id}',name: 'app_edit',methods:['PUT','POST'])]
    public function editAuthor(ManagerRegistry $manager ,Request $request, Author $Auth): Response
    {
        $form = $this->createForm(AuthorType::class,$Auth);

        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $em = $manager->getManager();
            $em->flush();

            return $this->redirectToRoute('app_Author');
        }

        return $this->render('/Author/editA.html.twig', [
            'Author' => $Auth,
            'form' => $form->createView()
        ]);
    }

    #[Route('/Author/delete/{id}',name: 'app_delete')]
    public function deleteAuthor(ManagerRegistry $manager,Author $Auth): Response
    {
       $em = $manager->getManager();
       $em->remove($Auth);
       $em->flush();

       return $this->redirectToRoute('app_Author');
    }
}
