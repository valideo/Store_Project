<?php

namespace StoreBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{

	public function findArray($array)
	{
		$Qb = $this->createQueryBuilder('u')
			->Select('u')
			->Where('u.id IN (:array)')
			->setParameter('array', $array);
			return $Qb->getQuery()->getResult();
	}


}
