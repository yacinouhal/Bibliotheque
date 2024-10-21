<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('/Category', name: 'app_Category')]
    public function listCategory(CategoryRepository $repo)
    {       
            return $this->render("/Category/showlistC.html.twig",[
            'Categorys'=> $repo->findAll()
            ]);
    }

    #[Route('/Category/details/{id}',name : 'app_details_Category')]
    public function CategoryDetails(Category $Categ)
    {
        return $this->render('/Category/showdetailsC.html.twig',[
            'Category' => $Categ
        ]);
    }


    #[Route('/Category/add', name: 'app_add_Category', methods:['POST','GET','PATCH'])]
    public function createCategory(Request $request,ManagerRegistry $manager)
    {
        $Category = new Category;
        $form = $this->createForm(CategoryType::class,$Category);

        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
             
            $em = $manager->getManager();
            $em->persist($Category);
            $em->flush();

            return $this->redirectToRoute('app_Category');
        }

       return $this->render('/Category/createC.html.twig',[
        'monFormulaire' => $form->createView()
       ]);
    }


    #[Route('/Category/edit/{id}',name: 'app_edit_Category')]
    public function editCategory(ManagerRegistry $manager ,Request $request, Category $categ): Response
    {
        $form = $this->createForm(CategoryType::class,$categ);

        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $em = $manager->getManager();
            $em->flush();

            return $this->redirectToRoute('app_Category');
        }

        return $this->render('/Category/editC.html.twig', [
            'Category' => $categ,
            'form' => $form->createView()
        ]);
    }

    #[Route('/Category/delete/{id}',name: 'app_delete_Category',methods:['POST','GET'])]
    public function deleteCategory(ManagerRegistry $manager,Category $categ): Response
    {
       $em = $manager->getManager();
       $em->remove($categ);
       $em->flush();

       return $this->redirectToRoute('app_Category');
    }
}
