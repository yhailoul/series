<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

//  $qb ->andWhere('s.vote<10')
//  $qb->andWhere('s.popularity>1000')
    $qb->leftJoin('s.seasons', 'seasons');
    $qb->addSelect('seasons');
    $qb->addOrderBy('s.popularity','DESC');


    $query=$qb->getQuery();
    $query->setMaxResults(50);
    $offset=($page-1)*50;
    $query->setFirstResult($offset);
    //permet de gérer la pagination sur les jointures
    $paginator=new Paginator($query);
    return $paginator;
}

}
