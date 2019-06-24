<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * @return Role[] Returns an array of Role objects
     */
    public function findRoles(User $user)
    {
        $roles = $user->getRoles();
        $user->roles = [];
        foreach ($roles as $value) {
            $role = $this->findOneBy(['id' => $value]);
            $user->setRoles([$role->getId() => $role->getName()]);
        }
        
        return $user;
    }

    /*
    public function findOneBySomeField($value): ?Role
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
