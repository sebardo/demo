<?php

namespace TicketBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class HallRepository
 */
class HallRepository extends EntityRepository
{
    /**
     * Count the total of rows
     *
     * @param int|null $actorId The actor ID
     *
     * @return int
     */
    public function countTotal($actorId = null)
    {
        $qb = $this->getQueryBuilder()
            ->select('COUNT(h)');

        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Find all rows filtered for DataTables
     *
     * @param string $search        The search string
     * @param int    $sortColumn    The column to sort by
     * @param string $sortDirection The direction to sort the column
     *
     * @return \Doctrine\ORM\Query
     */
    public function findAllForDataTables($search, $sortColumn, $sortDirection)
    {
        // select
        $qb = $this->getQueryBuilder()
            ->select('h.id, h.name, p.name placeName, h.visible, h.active');

        // join
        $qb->leftJoin('h.place', 'p')
                ;

        // search
        if (!empty($search)) {
            $qb->andWhere('h.name = :search')
                ->setParameter('search', $search);
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('h.name', $sortDirection);
                break;
            case 1:
                $qb->orderBy('h.visible', $sortDirection);
                break;
            case 1:
                $qb->orderBy('h.active', $sortDirection);
                break;
        }

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('TicketBundle:Hall')
            ->createQueryBuilder('h');

        return $qb;
    }
}