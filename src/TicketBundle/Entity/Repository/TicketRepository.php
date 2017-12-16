<?php

namespace TicketBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class TicketRepository
 */
class TicketRepository extends EntityRepository
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
            ->select('COUNT(t)');

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
            ->select("t.id, t.price, e.name eventName, p.name placeName, h.name hallName, sc.name schemeName, se.name sectorName, DATEFORMAT(so.date, '%Y-%m-%d %H:%i:%s') showDate, t.numbered");

        // join
        $qb->leftJoin('t.show', 'so')
            ->leftJoin('so.event', 'e')
            ->leftJoin('t.sector', 'se')
            ->leftJoin('se.scheme', 'sc')
            ->leftJoin('sc.hall', 'h')
            ->leftJoin('h.place', 'p')
            ;

        // search
        if (!empty($search)) {
            $qb->andWhere('e.name = :search')
                ->setParameter('search', $search);
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('so.date', $sortDirection);
                break;
            case 1:
                $qb->orderBy('t.price', $sortDirection);
                break;
        }

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('TicketBundle:Ticket')
            ->createQueryBuilder('t');

        return $qb;
    }
}