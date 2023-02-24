<?php

namespace App\Repository;

use App\Entity\Magasin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Magasin>
 *
 * @method Magasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasin[]    findAll()
 * @method Magasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Magasin::class);
    }

    public function add(Magasin $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Magasin $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Magasin[] Returns an array of Magasin objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Magasin
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function searchMagasin($param): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT m
        FROM App\Entity\Magasin m
        WHERE m.nomMag LIKE :param OR m.nomSocial LIKE :param OR m.rue LIKE :param OR m.ville LIKE :param OR m.email LIKE :param OR m.nomRue LIKE :param OR m.phoneFix LIKE :param OR m.dette LIKE :param OR m.white LIKE :param'
            )
            ->setParameter('param', '%'.$param.'%');

        // returns an array of Product objects
        return $query->getResult();
    }
}
