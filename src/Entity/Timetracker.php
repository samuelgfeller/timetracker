<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="App\Repository\TimetrackerRepository")
 */
class Timetracker {
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @var Company
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="logs")
	 * @ORM\JoinColumn(name="company_id", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $company_id;
	/**
	 * @var Contact
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="logs")
	 * @ORM\JoinColumn(name="contact_id", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $contact_id;
	/**
	 * @var Service
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="logs")
	 * @ORM\JoinColumn(name="service_id", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $service_id;
	
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="von", type="datetime", nullable=false)
	 */
	private $von;
	
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="bis", type="datetime", nullable=true)
	 */
	private $bis;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=45, nullable=true)
	 */
	private $comment;
	
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
	 * @return Company
	 */
	public function getCompanyId() {
		return $this->company_id;
	}
	
	/**
	 * @param Company $company_id
	 */
	public function setCompanyId(Company $company_id): void {
		$this->company_id = $company_id;
	}
	
	/**
	 * @return Contact
	 */
	public function getContactId() {
		return $this->contact_id;
	}
	
	/**
	 * @param Contact $contact_id
	 */
	public function setContactId($contact_id): void {
		$this->contact_id = $contact_id;
	}
	
	/**
	 * @return Service
	 */
	public function getServiceId() {
		return $this->service_id;
	}
	
	/**
	 * @param Service $service_id
	 */
	public function setServiceId($service_id): void {
		$this->service_id = $service_id;
	}
	/**
	 * @return mixed
	 */
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 * @param mixed $comment
	 */
	public function setComment($comment): void {
		$this->comment = $comment;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getBis(): \DateTime {
		return $this->bis;
	}
	
	/**
	 * @param \DateTime $bis
	 */
	public function setBis(\DateTime $bis): void {
		$this->bis = $bis;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getVon(): \DateTime {
		return $this->von;
	}
	
	/**
	 * @param \DateTime $von
	 */
	public function setVon(\DateTime $von): void {
		$this->von = $von;
	}
	
}
