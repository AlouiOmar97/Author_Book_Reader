<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function showAllAuthorsByUsernameAndEmail1($username,$email)
    {
        return $this->createQueryBuilder('a')
            ->where('a.username LIKE :username')
            ->andWhere('a.email LIKE :email')
            ->setParameter('username', $username)
            ->setParameter('email',$email)
            ->getQuery()
            ->getResult()
        ;
    }

    public function showAllAuthorsByUsernameAndEmail2($username,$email)
    {
        return $this->createQueryBuilder('a')
            ->where('a.username LIKE :username')
            ->andWhere('a.email LIKE :email')
            ->setParameters(['username'=> $username,'email'=>$email])
            ->getQuery()
            ->getResult()
        ;
    }

    public function showAllAuthorsByUsernameAndEmail3($username,$email)
    {
        return $this->createQueryBuilder('a')
            ->where('a.username LIKE ?1')
            ->andWhere('a.email LIKE ?2')
            ->setParameter('1', $username)
            ->setParameter('2',$email)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
