<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;



class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function listBook(BookRepository $repo): Response
    {
        return $this->render('book/book.html.twig', [
            'Books' => $repo->findAll()
        ]);
    }

    #[Route('book/details/{id}', name: 'app_details')]
    public function BookDetails(Book $B)
    {
        return $this->render("/book/showdetails.html.twig",[
            'book' =>$B
        ]);
    }

    #[Route('/book/add', name: 'app_add', methods:['POST','GET','PATCH'])]
    public function createbook(Request $request,ManagerRegistry $manager)
    {
        $book = new Book;
        $form = $this->createForm(BookType::class,$book);

        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
             
            $em = $manager->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('app_book');
        }

       return $this->render('/book/createB.html.twig',[
        'monFormulaire' => $form->createView()
       ]);
    }


    #[Route('/book/edit/{id}',name: 'app_edit',methods:['PUT','POST','GET'])]
    public function editbook(ManagerRegistry $manager ,Request $request, Book $Book): Response
    {
        $form = $this->createForm(BookType::class,$Book);

        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $em = $manager->getManager();
            $em->flush();

            return $this->redirectToRoute('app_book');
        }

        return $this->render('/book/editB.html.twig', [
            'book' => $Book,
            'form' => $form->createView()
        ]);
    }

    #[Route('/book/delete/{id}',name: 'app_delete')]
    public function deletebook(ManagerRegistry $manager,book $Book): Response
    {
       $em = $manager->getManager();
       $em->remove($Book);
       $em->flush();

       return $this->redirectToRoute('app_book');
    }
}
