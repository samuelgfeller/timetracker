<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	/**
	 * @ORM\Column(name="name", type="string", length=50)
	 * @Assert\NotBlank()
	 */
	private $name;
	/**
	 * @ORM\Column(name="address", type="string", length=100)
	 */
	private $address;
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Ort", inversedBy="contacts")
	 * @ORM\JoinColumn(name="ort_id", nullable=true)
	 */
	private $ort_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="contacts")
	 * @ORM\JoinColumn(name="company_id", nullable=true)
	 */
	private $company;
	
	/**
	 * @ORM\Column(name="taetigkeit", type="string", length=100, nullable=true)
	 */
	private $taetigkeit;
	
	/**
	 * @var Timetracker
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Timetracker", mappedBy="contact")
	 */
	private $logs;
	
	public function __toString() {
		return '' . $this->getName();
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
	
	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * @param mixed $address
	 */
	public function setAddress($address): void {
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
	public function setOrtId($ort_id): void {
		$this->ort_id = $ort_id;
	}
	
	/**
	 * @return mixed
	 */
	public function getCompany() {
		return $this->company;
	}
	
	/**
	 * @param mixed $company
	 */
	public function setCompany($company): void {
		$this->company = $company;
	}
	
	/**
	 * @return mixed
	 */
	public function getTaetigkeit() {
		return $this->taetigkeit;
	}
	
	/**
	 * @param mixed $taetigkeit
	 */
	public function setTaetigkeit($taetigkeit): void {
		$this->taetigkeit = $taetigkeit;
	}
	
	
}
