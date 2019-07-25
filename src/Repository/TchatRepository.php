<?php

namespace App\Repository;

use App\Entity\Tchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tchat[]    findAll()
 * @method Tchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TchatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tchat::class);
    }

    public function tchatList()
    {
        return $this->createQueryBuilder('t')
            // ->andWhere('t.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('t.id', 'desc')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Tchat
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
