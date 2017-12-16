<?php

namespace TicketBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class SectorRepository
 */
class SectorRepository extends EntityRepository
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
            ->select('COUNT(s)');

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
            ->select('s.id, s.name, s.visible, s.active');

        // join
        $qb->leftJoin('s.scheme', 'ss')
            ->leftJoin('ss.hall', 'h')
            ->leftJoin('h.complex', 'c')
                ;

        // search
        if (!empty($search)) {
            $qb->andWhere('s.name = :search')
                ->setParameter('search', $search);
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('s.name', $sortDirection);
                break;
            case 1:
                $qb->orderBy('s.visible', $sortDirection);
                break;
            case 1:
                $qb->orderBy('s.active', $sortDirection);
                break;
        }

        // group by
        $qb->groupBy('h.id');

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('TicketBundle:Sector')
            ->createQueryBuilder('s');

        return $qb;
    }
}