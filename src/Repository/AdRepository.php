<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param string $name
     * @return Ad[]
     */
    public function getAdByUser($user){
        $queryBuilder = $this->find('u')
            ->setMaxResults(10);

        $queryBuilder = $this->createQueryBuilder('a')
        ->where('a.user LIKE :user')
        ->setParameter('user', $user);

        return $queryBuilder->getQuery()->getArrayResult();
    }

    /**
     * @param string $title
     * @param string $zip
     * @param string $price
     * @return Ad[]
     */
    public function searchAd($title, $zip, $price){

        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.title LIKE :title')->setParameter('title', '%'.$title.'%')
            ->orWhere('a.zip LIKE :zip')->setParameter('zip', '%'.$zip.'%')
            ->orWhere('a.price LIKE :price')->setParameter('price', '%'.$price.'%');

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
