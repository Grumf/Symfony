<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity(fields="name", message="Il existe déjà une catégorie de ce nom")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    public function getId() {
        return $this->id;
    }

    /**
     * @ORM\column(length=20, unique=true)
     * @var string
     * 
     * Validation
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(max=20, maxMessage="le nom ne doit pas dépasser {{ limit }} caractères")
     * 
     */
    private $name;
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Méthode magique appelée quand on tente d'acceder à l'objet
     * comme chaîne de caractère par exemple avec un echo
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

}
