<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Category;
use App\Entity\Article;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    
    public function menu(){
        
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        
        return $this->render(
                'category/menu.html.twig',
                [
                    'categories' => $categories
                ]
        );
    }
    /**
     * @Route("/{id}")
     */
    public function index(/*$id*/Category $category)
    {
        
        /*$repository = $this->getDoctrine()->getRepository(Category::class);
        $articles = $repository->find($id);*/
        
        
        $repository = $this->getDoctrine()->getRepository(Article::class);
        //$articles = $repository->findBy(['category' => $category]);
        $articles = $repository->findLatest(2, $category);
        
        return $this->render(
                'category/index.html.twig',
                [
                    'category' => $category,
                    'articles' => $articles
                ]
        );
    }
}
