<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrtRepository")
 */
class Ort {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank()
     */
    private $ort;
    /**
     * @ORM\Column(type="integer")
     *  @Assert\NotBlank()
     */
    private $PLZ;
    

    /**
     * @ORM\OneToMany(targetEntity="Company", mappedBy="ort_id")
     */
    private $companies;
    
	public function __toString() {
		return $this->getPLZ().' '.$this->getOrt();
	}
    public function getId() {
        return $this->id;
    }
    
    public function getOrt() {
        return $this->ort;
    }
    
    public function setOrt($ort) {
        $this->ort = $ort;
    }
    
    public function getPLZ() {
        return $this->PLZ;
    }
    
    public function setPLZ($PLZ) {
        $this->PLZ = $PLZ;
    }
    
    
}
