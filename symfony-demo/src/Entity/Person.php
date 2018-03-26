<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * par défaut, le champ est un VARCHAR(255) NOT NULL
     * @var string
     * @ORM\Column()
     */
    
    private $firstname;

    /**
     * par défaut, le champ est un VARCHAR(255) NOT NULL
     * @var string
     * @ORM\Column()
     */
    
    private $lastname;
    
    /**
     * @var string
     * @ORM\Column(unique=true)
     */
    
    private $email;
            
    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    
    private $birthdate;
    
    public function getId() {
        return $this->id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setBirthdate(\DateTime $birthdate) {
        $this->birthdate = $birthdate;
        return $this;
    }
    
    
    public function getFullname(){
        return $this->firstname . ' ' . $this->lastname;
    }


}
