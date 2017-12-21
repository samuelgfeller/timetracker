<?php

namespace App\Repository;

use App\Entity\Ort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrtRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ort::class);
    }
	
	public function getOrtQuery() {
		$queryBuilder=$this->createQueryBuilder('o');
		return $queryBuilder
			->orderBy('o.PLZ','ASC')
			->getQuery();
	}
	
	/*
	public function findBySomething($value)
	{
		return $this->createQueryBuilder('o')
			->where('o.something = :value')->setParameter('value', $value)
			->orderBy('o.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/
}
