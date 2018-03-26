<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/")
     */
    public function index() {
            
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();
    
        return $this->render(
                '/admin/article/index.html.twig',
                [
                    'articles' => $articles
                ]
        );
    }
    
    /**
     * @Route("/edit/{id}", defaults={"id": null})
     */
    public function edit( Request $request, $id) {

    $em = $this->getDoctrine()->getManager();
    $originalImage = null;
        
        if (is_null($id)) { // Création
            $article = new Article();
            // L'auteur de l'article est l'utilisateur connecté
            $article->setAuthor($this->getUser());
        } else { // Modification
            $article = $em->find(Article::class, $id);
            
            if(!is_null($article->getImage())){
                $originalImage = $article->getImage();
                $imagePath = $this->getParameter('upload_dir').'/'. $originalImage;
                $article->setImage(new File($imagePath));
            }
        }
        
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if($form->isValid()){
                $image = $article->getImage();
                
                // S'il y a une image uploadée
                if(!is_null($image)) {
                    $filename = uniqid() . '.' . $image->guessExtension();
                    
                    // Equivalent de move_uploaded_file()
                    $image->move(
                        $this->getParameter('upload_dir'),
                        $filename
                        );
                      
                    $article->setImage($filename);
                    
                    // suppression de l'ancienne image en modification
                    if (!is_null($originalImage)){
                        unlink($this->getParameter('upload_dir'). '/' . $originalImage);
                    }
                } else {
                    // getData sur une checkbox = true si cochée, false sinon
                    if ($form->has('remove_image') && $form->get('remove_image')->getData()) {
                        $article->setImage(null);
                        unlink($this->getParameter('upload_dir'). '/' . $originalImage);
                        
                    }
                }
                
                $em->persist($article);
                $em->flush();
                
                $this->addFlash('success', "L'article à été enregistré");
                
                return $this->redirectToRoute('app_admin_article_index');
                
            } else {
                $this->addFlash('error', 'T\'es nul wesh');
            }
        }
        
        return $this->render(
                '/admin/article/edit.html.twig',
                [
                    'form' => $form->createView()
                ]);
    }
    
    /**
     * @Route("/delete/{id}")
     * @param int $id
     */
    public function delete($id){
        
        $em = $this->getDoctrine()->getManager();
        $article = $em->find(Article::class, $id);
        
        // Suppression de la categorie en bdd
        $em->remove($article);
        $em->flush();
        
        // Ajout d'un message
        $this->addFlash('sucess', 'L\'article à été supprimé');
        // redirection vers la liste
        return $this->redirectToRoute('app_admin_article_index');
        
    }


}
