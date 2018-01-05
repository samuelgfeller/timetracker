<?php

namespace App\Repository;

use App\Entity\Timetracker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TimetrackerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Timetracker::class);
    }
	
	/**
	 * @param $contact
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function findActiveByContact($contact) {
		return $this->createQueryBuilder('t')
			->where('t.contact_id= :contact')
			->andWhere('t.bis IS NULL')
			->setParameter('contact', $contact)
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult()
			;
    }
/*    public function findBySomething($value){
        return $this->createQueryBuilder('t')
            ->where('t.something = :value')->setParameter('value', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/
    
}
