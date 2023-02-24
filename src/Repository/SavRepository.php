<?php

namespace App\Repository;

use App\Entity\Sav;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sav>
 *
 * @method Sav|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sav|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sav[]    findAll()
 * @method Sav[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sav::class);
    }

    public function add(Sav $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sav $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Sav[] Returns an array of Sav objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Sav
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findSavBeteweenTwoDate(
        string $dateDebut,
        string $dateFin
    ): array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT s
        FROM App\Entity\Sav s
        WHERE s.created_at BETWEEN :dateDebut AND :dateFin'
            )
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin);

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findSavByDate($mois, $annee): array
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT s
         FROM App\Entity\Sav s
         WHERE s.created_at LIKE :mois AND s.created_at LIKE :annee'
            )
            ->setParameter('mois', '%-' . $mois . '-%')
            ->setParameter('annee', '%' . $annee . '-%');

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findSavEnCoursByDate($mois, $annee): array
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT s
         FROM App\Entity\Sav s
         WHERE s.created_at LIKE :mois AND s.created_at LIKE :annee AND s.etat LIKE :etat'
            )
            ->setParameter('mois', '%-' . $mois . '-%')
            ->setParameter('annee', '%' . $annee . '-%')
            ->setParameter('etat', '1');

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findSavEnAttenteByDate($mois, $annee): array
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT s
         FROM App\Entity\Sav s
         WHERE s.created_at LIKE :mois AND s.created_at LIKE :annee AND s.etat LIKE :etat'
            )
            ->setParameter('mois', '%-' . $mois . '-%')
            ->setParameter('annee', '%' . $annee . '-%')
            ->setParameter('etat', '0');

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findSavValiderByDate($mois, $annee): array
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT s
         FROM App\Entity\Sav s
         WHERE s.created_at LIKE :mois AND s.created_at LIKE :annee AND s.etat LIKE :etat'
            )
            ->setParameter('mois', '%-' . $mois . '-%')
            ->setParameter('annee', '%' . $annee . '-%')
            ->setParameter('etat', '2');

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findSavTerminerByDate($mois, $annee): array
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager
            ->createQuery(
                'SELECT s
         FROM App\Entity\Sav s
         WHERE s.created_at LIKE :mois AND s.created_at LIKE :annee AND s.etat LIKE :etat'
            )
            ->setParameter('mois', '%-' . $mois . '-%')
            ->setParameter('annee', '%' . $annee . '-%')
            ->setParameter('etat', '3');

        // returns an array of Product objects
        return $query->getResult();
    }
    
}
