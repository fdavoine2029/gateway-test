<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\PreDec;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // On creer un nouveau produit
        $product = new Products();

        // On créer le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requete du formulaire
        $productForm->handleRequest($request);
        //dd($productForm);

        
        // On vérifie sir le formulaire est soumis et Valide
        if($productForm->isSubmitted() && $productForm->isValid()){
            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On arrondit le prix
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);
            // On stock
            $em->persist($product);
            $em->flush();
            $this->addFlash('success','Produit ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->renderForm('admin/products/add.html.twig',compact('productForm'));

    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Products $product,Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // On vérifie si l'utilisateur peut editer avec le voter

        $this->denyAccessUnlessGranted('PRODUCT_EDIT',$product);

        $prix = $product->getPrice() / 100;
        $product->setPrice($prix);

        $productForm = $this->createForm(ProductsFormType::class, $product);

        // On traite la requete du formulaire
        $productForm->handleRequest($request);
        //dd($productForm);

        
        // On vérifie sir le formulaire est soumis et Valide
        if($productForm->isSubmitted() && $productForm->isValid()){
            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On arrondit le prix
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);
            // On stock
            $em->persist($product);
            $em->flush();
            $this->addFlash('success','Produit modifié avec succès');

            // On redirige
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->renderForm('admin/products/edit.html.twig',compact('productForm'));

    }





    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_DELETE',$product);
        return $this->render('admin/products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }




}
