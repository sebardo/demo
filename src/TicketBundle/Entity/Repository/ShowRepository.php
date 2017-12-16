<?php

namespace TicketBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class ShowRepository
 */
class ShowRepository extends EntityRepository
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
            ->select('s.id, s.date, s.description, COUNT(t) nItems');

        // join
        $qb->leftJoin('s.event', 'e')
            ->leftJoin('s.tickets', 't')
                ;

        // search
        if (!empty($search)) {
            $qb->andWhere('s.name = :search')
                ->setParameter('search', $search);
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('s.date', $sortDirection);
                break;
            case 1:
                $qb->orderBy('s.description', $sortDirection);
                break;
        }

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('TicketBundle:Show')
            ->createQueryBuilder('s');

        return $qb;
    }
}