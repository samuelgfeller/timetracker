<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
	
	/**
	 * @ORM\Column(name="name" , type="string", length=100, nullable=false)
     *  @Assert\NotBlank()
     */
    private $name;
	
	/**
	 * @var Timetracker
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Timetracker", mappedBy="service_id")
	 */
	private $logs;
	
	public function __toString() {
		return $this->getName().'';
	}
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id
	 */
	public function setId($id): void {
		$this->id = $id;
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
	public function setName($name): void {
		$this->name = $name;
	}
 
 

}
