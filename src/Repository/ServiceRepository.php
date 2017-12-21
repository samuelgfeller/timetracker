<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Service::class);
    }
	public function getServiceQuery() {
		$queryBuilder=$this->createQueryBuilder('s'); //alias
		return $queryBuilder
			->orderBy('s.name','ASC') //sort , order by
			->getQuery();
	}
	
	/*
	public function findBySomething($value)
	{
		return $this->createQueryBuilder('s')
			->where('s.something = :value')->setParameter('value', $value)
			->orderBy('s.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/
}
