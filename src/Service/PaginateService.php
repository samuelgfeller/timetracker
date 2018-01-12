<?php

namespace App\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginateService{
	public function getPagesCount(Query $query, $pageSize = 15) { //CHANGE
		$paginator=new Paginator($query);
		$paginator->setUseOutputWalkers(false);
		return ceil($paginator->count() / $pageSize);
	}
	
	public function paginate(Query $query , ?int $currentPage = 1, ?int $pagesize=15) {//change
		if ($pagesize<1){
			$pagesize=10; //change
		}
		if ($currentPage<1){
			$currentPage=1;
		}
		$paginator = new Paginator($query);
		
		return $paginator
			->getQuery()
			->setFirstResult($pagesize * ($currentPage -1))
			->setMaxResults($pagesize)
			->getResult();
	}
}
