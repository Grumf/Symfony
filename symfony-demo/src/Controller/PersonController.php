<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * toutes les de ce controlleur sont préfixées par /person
 * @Route("/person")
 */

class PersonController extends Controller
{
    /**
     * Le chemin complet de la route est person/list
     * @Route("/list")
     */
    public function index()
    {
        // Gestionnaire d'entité de doctrine
        $em = $this->getDoctrine()->getManager();
        // Repository de la class Person
        $personRepository = $em->getRepository(Person::class);
        
        // Le repository permet de requêter la bdd
        // ici, un SELECT * FROM person
        $persons = $personRepository->findAll();
        //dump($persons);
        
        return $this->render('person/index.html.twig', [
            'persons' => $persons,
        ]);
    }
    
    /**
     * L'id doit être un nombre (\d+ en expression régulière)
     * @Route("/{id}", requirements={"id": "\d+"})
     * @param int $id
     */
    public function detail($id){
        
        $em = $this->getDoctrine()->getManager();
        $personRepository = $em->getRepository(Person::class);
        
        // Retourne un objet Person par la clé primaire
        // Ou null s'il n'y a pas de résultats
        $person = $personRepository->find($id);
        
        // S'il n'y as pas de personne avec l'id reçu dans l'url
        // on jette un 404
        if (is_null($person)){
            throw new NotFoundHttpException();
        }
        
        return $this->render(
                'person/detail.html.twig',
                [
                  'person' => $person
                ]);
    }
    /**
     * @Route("/search")
     */
    public function search(Request $request){
        
        $persons = null;
        
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $personRepository = $em->getRepository(Person::class);
            
            $persons = $personRepository->findOneBy([
                'email' => $request->request->get('email')
            ]);
        }
        
        return $this->render(
                'person/search.html.twig',
                [
                    'persons' => $persons
                ]
        );
        
    }
    
    /**
     * @Route("/search_by_lastname")
     */
    public function searchByLastname(Request $request){
        
        $persons = [];
        
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $personRepository = $em->getRepository(Person::class);
            
            $persons = $personRepository->findBy([
                'lastname' => $request->request->get('lastname')
            ]);
        }
        
        return $this->render(
                'person/search_by_lastname.html.twig',
                [
                    'persons' => $persons
                ]
        );
        
    }
    
}
