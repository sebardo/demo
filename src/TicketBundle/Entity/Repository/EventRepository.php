<?php

namespace TicketBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class EventRepository
 */
class EventRepository extends EntityRepository
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
            ->select('COUNT(e)');

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
            ->select('e.id, e.name, e.slug, e.description, p.name placeName, p.address, h.name hallName, s.name schemeName, e.visible, e.active');

        // join
        $qb->leftJoin('e.place', 'p')
           ->leftJoin('e.hall', 'h')
           ->leftJoin('e.scheme', 's')
                ;

        // search
        if (!empty($search)) {
            $qb->andWhere('e.name = :search')
                ->setParameter('search', $search);
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('e.name', $sortDirection);
                break;
            case 1:
                $qb->orderBy('e.visible', $sortDirection);
                break;
            case 1:
                $qb->orderBy('e.active', $sortDirection);
                break;
        }

        // group by
        $qb->groupBy('e.id');

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('TicketBundle:Event')
            ->createQueryBuilder('e');

        return $qb;
    }
}