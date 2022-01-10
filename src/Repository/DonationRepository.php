<?php

namespace App\Repository;

use App\Entity\Donation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Donation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donation[]    findAll()
 * @method Donation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donation::class);
    }

    /**
      * @return Donation[]
     */

    public function findDonations($iduser)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT i.name, d.id, d.amount, d.date FROM App\Entity\Institution i INNER JOIN App\Entity\Donation d WHERE d.idinsti = i.id AND d.iduser = :iduser'
        )->setParameter('iduser', $iduser);
        return $query->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Donation
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
