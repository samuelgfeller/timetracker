<?php

namespace App\Repository;

use App\Entity\Ort;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrtRepository extends ServiceEntityRepository {
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, Ort::class);
	}
	
	public function getOrtQuery() {
		$queryBuilder = $this->createQueryBuilder('o');
		return $queryBuilder
			->orderBy('o.PLZ', 'ASC')
			->getQuery();
	}
	
	/**
	 * @param $PLZ
	 * @return bool
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function checkIfExists($PLZ) {
		return $this->createQueryBuilder('t')
			->where('t.PLZ= :PLZ')
			->setParameter('PLZ', $PLZ)
			->getQuery()
			->getOneOrNullResult();
	}
	
	/**
	 * @param $input
	 * @return mixed
	 */
	public function getSearchResult($input) {
		$test=$input;
		$qb = $this->createQueryBuilder('u');
		$result = $qb->where(
			$qb->expr()->like('u.ort', ':input')
		)
			->orWhere(
				$qb->expr()->like('u.PLZ', ':input')
			)
			->setParameter('input', '%' .$input. '%')
			->getQuery()
			->getResult();
		return $result;
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
