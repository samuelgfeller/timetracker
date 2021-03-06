<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contact::class);
    }
	public function getContactQuery() {
		$queryBuilder=$this->createQueryBuilder('contact'); //alias
		return $queryBuilder
			->orderBy('contact.name','ASC') //sort , order by
			->getQuery();
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
