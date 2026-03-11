<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }
public function findBestSeries( int $page=1){
        //dql
//        $dql="
//        SELECT s FROM App\Entity\Serie AS s
//        WHERE s.vote > 8 AND s.popularity>1000
//        ORDER BY s.popularity DESC ";
//
//        $query=$this->getEntityManager()->createQuery($dql);
//
//        return $query->getResult();

        //query builder
    $qb=$this->createQueryBuilder("s");
    $qb
//        ->andWhere('s.vote<10')
//    ->andWhere('s.popularity>1000')
    ->addOrderBy('s.popularity','DESC');

    $query=$qb->getQuery();
    $query->setMaxResults(50);
    $offset=($page-1)*50;
    $query->setFirstResult($offset);
    return $query->getResult();
}
}
