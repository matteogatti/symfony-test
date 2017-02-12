<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Ricetta;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class RicettaRepository extends EntityRepository
{
    /**
     * @return int
     */
    public function countAll()
    {
        $ricette = $this->_em->getRepository(Ricetta::class)->createQueryBuilder('r')
            ->select('count(r.id)')
            ->getQuery()->getSingleScalarResult();

        return $ricette;
    }

    /**
     * @param array $filterArgs
     *
     * @return QueryBuilder
     */
    public function getPaginationQuery(array $filterArgs)
    {
        $paginationQuery = $this->_em->getRepository(Ricetta::class)->createQueryBuilder('r')
            ->orderBy('r.dataCreazione', 'DESC');

        foreach ($filterArgs as $key => $value) {
            if ($value != '' && $key != 'sort') {
                if ($key == 'ingrediente') {
                    $paginationQuery
                        ->join('r.ingredienti', 'i')
                        ->andWhere($paginationQuery->expr()->in('i.id', array($value)));
                } else {
                    $paginationQuery->andWhere('r.'.$key . ' LIKE :'.$key)->setParameter($key, '%' . $value . '%');
                }
            }
        }

        return $paginationQuery;
    }

    /**
     * @return array
     */
    public function findAllByArray()
    {
        return $this->_em->getRepository(Ricetta::class)
            ->createQueryBuilder('r')
            ->getQuery()
            ->getArrayResult();
    }
}
