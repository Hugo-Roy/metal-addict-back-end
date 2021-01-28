<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
    * @return Picture[] Returns an array of Picture objects
    */
    
    public function findByEvent($order, $event)
    {
        return $this->createQueryBuilder('p')
            ->where('p.event = :val')
            ->setParameter('val', $event)
            ->orderBy('p.createdAt', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUser($order, $user)
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :val')
            ->setParameter('val', $user)
            ->orderBy('p.createdAt', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByUserAndEvent($order, $user, $event)
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :val')
            ->setParameter('val', $user)
            ->andWhere('p.event = :evt')
            ->setParameter('evt', $event)
            ->orderBy('p.createdAt', $order)
            ->getQuery()
            ->getResult()
        ;
    }
}
