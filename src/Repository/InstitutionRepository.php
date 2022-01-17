<?php

namespace App\Repository;

use App\Entity\Institution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Institution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Institution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Institution[]    findAll()
 * @method Institution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Institution::class);
    }

    /**
        * @return Institution[]
    */
    public function findInsti($date)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT i.id, i.name, i.country FROM App\Entity\Institution i INNER JOIN App\Entity\Donation d WHERE d.date >= :date AND i.id = d.idinsti'
        )->setParameter('date', $date);
        return $query->getResult();
    }

}
