<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="company")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     *  @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\Column(name="address", type="string", length=100, nullable=true)
     *  @Assert\NotBlank()
     */
    private $address;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ort", inversedBy="companies")
     * @ORM\JoinColumn(name="ort_id", nullable=true)
     */
    private $ort_id;
	
	/**
	 * @ORM\OneToMany(targetEntity="Contact", mappedBy="company_id")
	 */
	private $contacts;
	
	/**
	 * @var Timetracker
	 *
	 * @ORM\OneToMany(targetEntity="Timetracker", mappedBy="company_id")
	 */
	private $logs;
	
	public function __toString() {
		return ''.$this->getName();
	}
    
    
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
	
	/**
	 * @return Contact[]
	 */
	public function getContacts() {
		return $this->contacts;
	}
	
	public function getContactsForSelect()
	{
		$contacts = [];
		/** @var Contact $contact */
		foreach ($this->getContacts() as $contact) {
			$contacts[$contact->getId()] = $contact->getName();
		}
		
		return $contacts;
	}
	
	/**
	 * @param Contact[] $contacts
	 */
	public function setContacts($contacts): void {
		$this->contacts = $contacts;
	}
	
}
