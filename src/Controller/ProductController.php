<?php

namespace App\Controller;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends AbstractController
{
    #[Route('/Product', name: 'app_Product')]
    public function listProduct(ProductRepository $repo): Response
    {
        return $this->render('Product/Product.html.twig', [
            'Products' => $repo->findAll()
        ]);
    }

    #[Route('Product/details/{id}', name: 'app_details_Product')]
    public function ProductDetails(Product $P)
    {
        return $this->render("/Product/showdetails.html.twig",[
            'Product' =>$P
        ]);
    }

    #[Route('/Product/add', name: 'app_add_Product', methods:['POST','GET','PATCH'])]
    public function createProduct(Request $request,ManagerRegistry $manager)
    {
        $Product = new Product;
        $form = $this->createForm(ProductType::class,$Product);

        $form = $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
             
            $em = $manager->getManager();
            $em->persist($Product);
            $em->flush();

            return $this->redirectToRoute('app_Product');
        }

       return $this->render('/Product/createP.html.twig',[
        'monFormulaire' => $form->createView()
       ]);
    }


    #[Route('/Product/edit/{id}',name: 'app_edit_Product',methods:['PUT','POST','GET'])]
    public function editProduct(ManagerRegistry $manager ,Request $request, Product $Product): Response
    {
        $form = $this->createForm(ProductType::class,$Product);

        $form = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $em = $manager->getManager();
            $em->flush();

            return $this->redirectToRoute('app_Product');
        }

        return $this->render('/Product/editP.html.twig', [
            'Product' => $Product,
            'form' => $form->createView()
        ]);
    }

    #[Route('/Product/delete/{id}',name: 'app_delete_Product')]
    public function deleteProduct(ManagerRegistry $manager,Product $Product): Response
    {
       $em = $manager->getManager();
       $em->remove($Product);
       $em->flush();

       return $this->redirectToRoute('app_Product');
    }
}
