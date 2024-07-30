<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function remove(User $user)
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
