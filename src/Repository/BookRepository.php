<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function showAllBooksByAuthor($id)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author','a')
            ->addSelect('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function showAllBooksByAuthor2()
    {
        return $this->createQueryBuilder('b')
            ->join('b.author','a')
            ->addSelect('a')
            ->getQuery()
            ->getResult()
        ;
    }

    public function showAllBooksByAuthor3($nbooks,$year)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author','a')
            ->addSelect('a')
            ->where('a.nb_books > :nbooks')
            ->andWhere("b.publicationDate < :year")
            ->setParameter('nbooks',$nbooks)
            ->setParameter('year',$year)
            ->getQuery()
            ->getResult()
        ;
    }

    public function showAllBooksByAuthor4(EntityManagerInterface $em)
    {   // $em=new EntityManagerInterface();
        $qb= $em->createQueryBuilder();
      // $queryBuilder = $this->getManager()->createQueryBuilder();
       //$qb=$this->createQueryBuilder('b');
        return $this->createQueryBuilder('b')
            ->update('App\Entity\Book','b')
            ->set('b.category',':cat')
            //->innerJoin('b.author','a')
            //->addSelect('a')
            //->where('a.username = :username ')
           /* ->where($qb->expr()->in(
                'b.author',
                $qb->select('a')
                ->from('App\Entity\Author','a')
                ->where('a.username = :username')
            ))*/
            ->getQuery()
            ->getSQL()
        ;
    }

    public function updateBooksCategoryByAuthorEmail($authorEmail, $newCategory)
    {
        // Step 1: Fetch the entities that need to be updated
        $books = $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->where('a.email = :email')
            ->setParameter('email', $authorEmail)
            ->getQuery()
            ->getResult();

        // Step 2: Apply the updates
        foreach ($books as $book) {
            $book->setCategory($newCategory);
        }

        // Flush the changes
        $this->getEntityManager()->flush();
    }


//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
