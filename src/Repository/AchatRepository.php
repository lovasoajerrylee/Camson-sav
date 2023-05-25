<?php

namespace App\Repository;

use App\Entity\Achat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Achat>
 *
 * @method Achat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achat[]    findAll()
 * @method Achat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achat::class);
    }

    public function add(Achat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Achat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Achat[] Returns an array of Achat objects
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

//    public function findOneBySomeField($value): ?Achat
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function getTousAchat(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT cg2023_achat.id,client_id,produit_id,date,nom_client,prenom_client,tel_fixe,tel_portable1,tel_portable2,ref_client,etat,date_paiment,email,nom_produit FROM cg2023_achat INNER JOIN cg2023_client ON cg2023_achat.client_id = cg2023_client.id 
            INNER JOIN cg2023_produit ON cg2023_achat.produit_id = cg2023_produit.id
        ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function getAchatById($id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM cg2023_achat INNER JOIN cg2023_client ON cg2023_achat.client_id = cg2023_client.id 
            INNER JOIN cg2023_produit ON cg2023_achat.produit_id = cg2023_produit.id where cg2023_achat.id = '.$id.'
        ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetch();
    }

    public function getAllPanier($client): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT cg2023_panier.id,cg2023_produit.nom_produit, cg2023_panier.quantite,cg2023_panier.prix_unitaire,cg2023_panier.sous_total FROM `cg2023_panier` INNER JOIN cg2023_client ON cg2023_panier.client_id = cg2023_client.id INNER JOIN cg2023_produit ON cg2023_panier.produit_id = cg2023_produit.id WHERE client_id = '$client'";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function transfertPanier($client)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "INSERT INTO cg2023_achat (client_id, produit_id)
            SELECT client_id, produit_id FROM cg2023_panier WHERE client_id = '$client'";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return true;
    }

    
    public function viderPanier($client)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "DELETE FROM cg2023_panier WHERE client_id = '$client'";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return true;
    }


}
