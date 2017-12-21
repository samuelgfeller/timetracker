<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name")
     */
    private $name;
    /**
     * @ORM\Column(name="address")
     */
    private $address;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ort", inversedBy="companies")
     * @ORM\JoinColumn(name="ort_id", nullable=true)
     */
    private $ort_id;
    
    
    
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * @return mixed
     */
    public function getAddress() {
        return $this->address;
    }
    
    /**
     * @param mixed $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }
    
    /**
     * @return mixed
     */
    public function getOrtId() {
        return $this->ort_id;
    }
    
    /**
     * @param mixed $ort_id
     */
    public function setOrtId($ort_id) {
        $this->ort_id = $ort_id;
    }
    
    
    
}
