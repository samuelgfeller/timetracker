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
	private $company;
	/**
	 * @var Contact
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="logs")
	 * @ORM\JoinColumn(name="contact_id", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $contact;
	/**
	 * @var Service
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="logs")
	 * @ORM\JoinColumn(name="service_id", nullable=false)
	 * @Assert\NotBlank()
	 */
	private $service;
	
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
	 * @var
	 */
	private $totalTime;
	
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
	public function getCompany() {
		return $this->company;
	}
	
	/**
	 * @param Company $company
	 */
	public function setCompany(Company $company): void {
		$this->company = $company;
	}
	
	/**
	 * @return Contact
	 */
	public function getContact() {
		return $this->contact;
	}
	
	/**
	 * @param Contact $contact
	 */
	public function setContact($contact): void {
		$this->contact = $contact;
	}
	
	/**
	 * @return Service
	 */
	public function getService() {
		return $this->service;
	}
	
	/**
	 * @param Service $service
	 */
	public function setService($service): void {
		$this->service = $service;
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
	 * @return mixed
	 */
	public function getBis() {
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
	public function getVon()/*: \DateTime*/ {
		return $this->von;
	}
	
	/**
	 * @param \DateTime $von
	 */
	public function setVon(\DateTime $von): void {
		$this->von = $von;
	}
	
	/**
	 * @return object
	 */
	public function getTotalTime() {
		return $this->totalTime;
	}
	
	/**
	 * @param object $totalTime
	 */
	public function setTotalTime($totalTime) {
		$this->totalTime = $totalTime;
	}
	
}