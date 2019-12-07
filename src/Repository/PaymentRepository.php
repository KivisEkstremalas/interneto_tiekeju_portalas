<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
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

    /**
     * @return Payment[]
     */
    public function findByClient($client)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.client = :val')
            ->setParameter('val', $client)
            ->getQuery()
            ->getResult();
    }

    public function findById($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function totalPaymentsByClient($client)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.client = :val')
            ->andWhere('p.paid = true')
            ->setParameter('val', $client)
            ->select('SUM(p.amount) as amount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalPaymentsByProvider($provider)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :val')
            ->andWhere('p.paid = true')
            ->setParameter('val', $provider)
            ->select('SUM(p.amount) as amount')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Payment[]
     */
    public function findByProvider($provider)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.provider = :val')
            ->setParameter('val', $provider)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Payment[] Returns an array of Payment objects
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
    public function findOneBySomeField($value): ?Payment
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
