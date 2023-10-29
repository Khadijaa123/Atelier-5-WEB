<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

public function findBooksOrderedByAuthor()
{
    return $this->createQueryBuilder('b')
        ->leftJoin('b.author', 'a')
        ->orderBy('a.name', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findBooksBeforeYearWithAuthorMoreThan35Books()
{
    return $this->createQueryBuilder('b')
        ->where('b.publicationYear < :year')
        ->andWhere('b.author IN (
            SELECT a.id FROM App\Entity\Author a
            WHERE (SELECT COUNT(b2.id) FROM App\Entity\Book b2 WHERE b2.author = a) > 35
        )')
        ->setParameter('year', 2023)
        ->getQuery()
        ->getResult();
}



public function findAuthorsByBookCountRange($minBooks, $maxBooks)
{
    return $this->createQueryBuilder('a')
        ->select('a', 'COUNT(b) as bookCount')
        ->leftJoin('a.books', 'b')
        ->groupBy('a.id')
        ->having('bookCount >= :minBooks')
        ->having('bookCount <= :maxBooks')
        ->setParameter('minBooks', $minBooks)
        ->setParameter('maxBooks', $maxBooks)
        ->getQuery()
        ->getResult();
}
}
