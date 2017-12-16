<?php

namespace TicketBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class PlaceRepository
 */
class PlaceRepository extends EntityRepository
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
            ->select('COUNT(p)');

        if (!is_null($actorId)) {
            $qb->where('p.actor = :actor_id')
                ->setParameter('actor_id', $actorId);
        }

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
            ->select('p.id, p.name, p.description, a.id actorId, a.name actorName, a.lastname actorLastname, a.email actorEmail, p.address, p.address, p.city, p.visible, p.active');

        // join
        $qb->leftJoin('p.actor', 'a')
                ;

        // search
        if (!empty($search)) {
            $qb->andWhere('p.name = :search')
                ->setParameter('search', $search);
        }

        // sort by column
        switch($sortColumn) {
            case 0:
                $qb->orderBy('p.name', $sortDirection);
                break;
            case 1:
                $qb->orderBy('p.address', $sortDirection);
                break;
        }

        return $qb->getQuery();
    }

    private function getQueryBuilder()
    {
        $em = $this->getEntityManager();

        $qb = $em->getRepository('TicketBundle:Place')
            ->createQueryBuilder('p');

        return $qb;
    }
}