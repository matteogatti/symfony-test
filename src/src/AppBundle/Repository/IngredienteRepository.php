<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Ingrediente;
use Doctrine\ORM\EntityRepository;

class IngredienteRepository extends EntityRepository
{
    /**
     * @return int
     */
    public function countAll()
    {
        $ingredienti = $this->_em->getRepository(Ingrediente::class)->createQueryBuilder('i')
            ->select('count(i.id)')
            ->getQuery()->getSingleScalarResult();

        return $ingredienti;
    }

    public function getPaginationQuery()
    {
        $countCategorie = $this->_em
            ->createQuery(
                'SELECT COUNT(i) FROM AppBundle:Ingrediente i'
            )
            ->getSingleScalarResult();

        $paginationDQL = "SELECT i FROM AppBundle:Ingrediente i ORDER BY i.dataCreazione DESC";

        $paginationQuery = $this->_em->createQuery($paginationDQL)
            ->setHint('knp_paginator.count', $countCategorie);

        return $paginationQuery;
    }

    /**
     * @return array
     */
    public function findAllByArray()
    {
        return $this->_em->getRepository(Ingrediente::class)
            ->createQueryBuilder('i')
            ->getQuery()
            ->getArrayResult();
    }
}
