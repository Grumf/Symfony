<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/category")
*/
class CategoryController extends Controller
{
    /**
    * @Route("/")
    */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        
        return $this->render(
                'admin/category/index.html.twig',
                [
                    'categories' => $categories
                ]
        );
    }
    
    /**
     * @Route("/edit/{id}", defaults={"id": null})
     */    
    public function edit(Request $request, $id) {
        
        $em = $this->getDoctrine()->getManager();
        
        if (is_null($id)){
        $category = new Category();
        } else {
            $category = $em->getRepository(Category::class)->find($id);
        }
        
        // Création du formulaire lié à la categorie
        $form = $this->createForm(CategoryType::class, $category);
        
        // Le formulaire traite la requête HTTP
        $form->handleRequest($request);
        
        // Si le formulaire est envoyé
        if ($form->isSubmitted()) {
            // S'il n'y a pas d'erreurs de validation du formulaire
            if ($form->isValid()) {
                // Prépare l'enregistrement en bdd
                $em->persist($category);
                // fait l'enregistrement en bdd
                $em->flush();
                
                // Ajout du message flash
                $this->addFlash('success', 'la catégorie à été enregistrée');
                // Redirection vers la liste
                return $this->redirectToRoute('app_admin_category_index');
                
            }
        }
        
        return $this->render(
            'admin/category/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
    
    /**
     * @Route("/delete/{id}")
     * @param int $id
     */
    public function delete($id){
        $em = $this->getDoctrine()->getManager();
        $category = $em->find(Category::class, $id);
        
        // Suppression de la categorie en bdd
        $em->remove($category);
        $em->flush();
        
        // Ajout d'un message
        $this->addFlash('sucess', 'La catégorie à été supprimée');
        // redirection vers la liste
        $this->redirectToRoute('app_admin_category_index');
    }
}
