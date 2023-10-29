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
     public function showAllAuthorByUsername(){
        return $this->createQueryBuilder(alias:'a')
        ->where(predicates: 'a.username LIKE :username')
        ->setParameter(key: 'username', value: 'A%')
        ->getQuery()
        ->getResult(); 
    }
    public function ListBook($id){
        return $this->createQueryBuilder('a')
        ->join('a.Book', 'b')
        ->addSelect('b')
        ->where('b.id: id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult();
    }
    

    public function orderbyusername(){
        return $this->createQueryBuilder('a')
        ->orderBy('a.username','Asc')
        ->getQuery()
        ->getResult();
  
      }
      public function searchByalphabet(){
          return $this->createQueryBuilder('a')
          ->where('a.username LIKE : name')
          ->setParameter('name','a%')
          ->getQuery()
          ->getResult();
    
      }
      public function searchlist(){
          return $this->createQueryBuilder('a')
          ->where('a.username LIKE : name')
          -> andwhere('a.email LIKE:email')
          ->setParameters(['name'=>'a%','email' =>'%@%'])
          ->getQuery()
          ->getResult();
  
    
      }
  
      public function searchbyid($id){
          return $this->createQueryBuilder('a')
          ->join('a.books','b')
          ->addSelect('b')
          ->where('b.author=:id')
          ->setParameter('id',$id)
          ->getQuery()
          ->getResult();
         
    
      }
  
  
      public function searchbyusername($username){
          return $this->createQueryBuilder('a')
          ->where('a.username=:name')
          ->setParameter('name',$username)
          ->getQuery()
          ->getResult();
      }
      public function minmax($min,$max){
        $em=$this->getEntityManager('a');
        return $em->createQuery('SELECT a from App\Entity\Author a where a.nbrlivre BETWEEN  ?1 and :max')
        ->setParameters(['1'=>$min,'max'=>$max])
        ->getResult();
      }

      
      public function findAuthorsOrderedByEmail()
      {
          return $this->createQueryBuilder('a')
              ->orderBy('a.email', 'ASC')
              ->getQuery()
              ->getResult();
      }
       
  }
     

