<?php

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    public function findByProviderForDate($provider, $dateFrom, $dateTill)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :provider')
            ->andWhere('p.date >= :dateFrom')
            ->andWhere('p.date < :dateTill')
            ->setParameter('provider', $provider)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTill', $dateTill)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByProvider($provider)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :val')
            ->setParameter('val', $provider)
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function totalProviderPurchaseAmount($provider)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :val')
            ->setParameter('val', $provider)
            ->select('SUM(p.amount) as amount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function monthlyProviderPurchaseAmount($provider, $year, $month)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :val')
            ->andWhere('YEAR(p.date) = :vy')
            ->andWhere('MONTH(p.date) = :vm')
            ->setParameter('val', $provider)
            ->setParameter('vy', $year)
            ->setParameter('vm', $month)
            ->getQuery()
            ->getSingleScalarResult();
    }
    // /**
    //  * @return Purchase[] Returns an array of Purchase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Purchase
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
