<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Find Reviews ordered by date ASC
     */
    public function findByLatest($order, $limit)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.user', 'u')
            ->innerJoin('r.event', 'e')
            ->addSelect('u')
            ->addSelect('e')
            ->orderBy('r.createdAt', $order)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find Reviews by a given event (setlistId)
     */
    public function findByEvent($order, $setlistId)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.user', 'u')
            ->innerJoin('r.event', 'e')
            ->addSelect('u')
            ->addSelect('e')
            ->andWhere('e.setlistId = :val')
            ->setParameter('val', $setlistId)
            ->orderBy('r.createdAt', $order)
            ->getQuery()
            ->getResult();
    }
}

