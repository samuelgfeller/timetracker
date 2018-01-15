<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }
	
	/**
	 * @return mixed
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getCompanyQuery() {
		$queryBuilder=$this->createQueryBuilder('c'); //alias
		return $queryBuilder
			->orderBy('c.name','ASC') //sort , order by
			->getQuery();

	}
	/**
	 * @param $name
	 * @return bool
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function checkIfExists($name) {
		return $this->createQueryBuilder('t')
			->where('t.name= :name')
			->setParameter('name', $name)
			->getQuery()
			->getOneOrNullResult()
		;

	}
	
	/**
	 * @param $input
	 * @return mixed
	 */
	public function getSearchResult($input) {
		$qb = $this->createQueryBuilder('u');
		$result = $qb->where(
			$qb->expr()->like('u.name', ':input')
		)
			->orWhere(
				$qb->expr()->like('u.id', ':input')
			)
			->setParameter('input', '%' .$input. '%')
			->getQuery()
			->getResult();
		return $result;
	}
		

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.something = :value')->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
