<?php

namespace App\Repository;

use App\Entity\SearchData;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*******/

    /**
     * Affiche Toutes les sorties sauf les sorties archivées
     *
     */
    public function RechercherToutesLesSorties()
    {

        //id 5 doit être état 'archivé'
        $query = $this
            ->createQueryBuilder('s')
            ->where('s.etat != 7');

        return $query->getQuery()->getResult();
    }

//    /**
//     * Affiche les sorties selon les filtres
//     *
//     * @param SearchData $search
//     * @param Utilisateur $utilisateur
//     *
//     */
    public function findSearch(SearchData $search, Utilisateur $utilisateur)
    {
/*
 * permet de rechercher les sorties en fonction de la recherche (archives exclus)
 */
        //id 7 doit être état 'archivé'
        $query = $this
            ->createQueryBuilder('s')
            -> where('s.etat != 7');
//            ->select('s.campus')
//            ->join('s.campus', 'c');

        if (!empty($search->getNom())) {
            $query = $query
                ->andWhere('s.nom LIKE :nom')
                ->setParameter('nom', '%'.($search->getNom()).'%');
        }

        if (!empty($search->getCampus())) {
            $query = $query
                ->andWhere('s.siteOrganisateur = :campus')
                ->setParameter('campus', $search->getCampus());
        }

        if (!empty($search->getDateDebut())) {
            $query = $query
                ->andWhere('s.debutSortie >= :debutSortie')
                ->setParameter('debutSortie', $search->getDateDebut());
        }

        if (!empty($search->getDateFin())) {
            $query = $query
                ->andWhere('s.debutSortie <= :finSortie')
                ->setParameter('finSortie', $search->getDateFin());
        }

        if (!empty($search->organisateur)) {
            $query = $query
                ->andWhere('s.organisateurs = :utilisateur')
                ->setParameter('utilisateur', $utilisateur);
        }

        if (!empty($search->inscrit)) {
            $query = $query
                ->andWhere(':utilisateur MEMBER OF s.participants')
                ->setParameter('utilisateur', $utilisateur);
        }

        if (!empty($search->NonInscrit)) {
            $query = $query
                ->andWhere(':utilisateur NOT MEMBER OF s.participants')
                ->setParameter('utilisateur', $utilisateur);
        }

        if (!empty($search->sortiePassee)) {
            $query = $query
                ->andWhere('s.etat = :etat')
                ->setParameter('etat', 5);
        }

        return $query->getQuery()->getResult();

    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
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

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
