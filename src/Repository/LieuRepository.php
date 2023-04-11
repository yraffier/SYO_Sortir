<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lieu>
 *
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    public function save(Lieu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lieu $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Verifie si le lieu existe en base de donnée
     *
     * @param Lieu $lieu
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function verificationDeDoublonLieu(Lieu $lieu){
        return (boolean)$this->createQueryBuilder('l')

            ->andWhere('l.nom = :nom AND l.rue = :rue AND l.latitude = :latitude AND l.longitude = :longitude')
            ->setParameter('nom', $lieu->getNom())
            ->setParameter('rue', $lieu->getRue())
            ->setParameter('latitude', $lieu->getLatitude())
            ->setParameter('longitude', $lieu->getLongitude())
            ->getQuery()
            ->getOneOrNullResult();
    }

//    public function verificationDeDoublon(Lieu $lieu){
//        return (boolean)$this->createQueryBuilder('l')
//            ->andWhere('l.latitude = :latitude AND l.longitude = :longitude')
//            ->setParameter('latitude', $lieu->getLatitude())
//            ->setParameter('longitude', $lieu->getLongitude())
//            ->getQuery()
//            ->getOneOrNullResult();
//    }

//    /**
//     * @return Lieu[] Returns an array of Lieu objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Lieu
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

//    public  function findLieuxByIdVille(Lieu $lieu){
//        $entityManager = $this->getEntityManager();
//
//        $query = $entityManager->createQuery(
//            'SELECT rue, code_postal
//                FROM App\Entity\Lieu l
//                JOIN ville v on v.id = l.v_id
//                WHERE  v.id = v_id'
//        )->setParameter('lieu',  );
//
//        return $query->getOneOrNullResult();
//    }

    public  function findLieuxByIdVille(int $lieu_ville_id){
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT rue, code_postal
                FROM App\Entity\Lieu l
                JOIN ville v on v.id = l.v_id 
                WHERE  v.id = v_id'
        )->setParameter('lieu', $lieu_ville_id);

        return $query->getOneOrNullResult();
    }
}
