<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }








    //using query builder
    public function rechercheAvance($str) {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT P
                FROM App\Entity\Product P
                WHERE P.Nom LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();

    }


    //using DQL
    public function traiter($id) {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder(); // dql

        $etat = $query->update('App\Entity\Product','p')
                      ->set('p.etat','?1')
                      ->where('p.id= ?2')
                    ->setParameter(1,true)
                    ->setParameter(2,$id)
                    ->getQuery()->getResult();

   }


   //METIER 3
    public function  getProduitsEvaluees() {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder(); // dql
        $query->select('p.id ,p.note')
            ->from('App\Entity\Product','p')
            ->orderBy('p.note','DESC')
            ->setMaxResults(5);
        $res = $query->getQuery();
        return $res->execute();
    }
}
