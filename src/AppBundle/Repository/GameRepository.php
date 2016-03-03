<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends EntityRepository
{
    public function getCount()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function search($queryString, $array = false)
    {
        $qb = $this->createQueryBuilder('a')
        ->where('a.name LIKE :string')
        ->setParameter('string', "%". $queryString ."%")
        ->getQuery();

        if($array) {
            return $qb->getArrayResult();
        }

        return $qb->getResult();
    }

    public function searchInArray($array)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.name LIKE :data')
            ->setParameter('data', "%".$array[0]."%");

        $nbItem = count($array);
        for($i = 1; $i < $nbItem; $i++) {
            $qb->orWhere("a.name LIKE :id$i")
                ->setParameter("id$i", '%'.$array[$i]."%");
        }

        return $qb->getQuery()->getResult();
    }

    public function findByCategory($category, $limit)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->leftJoin('a.categories', 'c')
            ->addSelect('c');

        $qb = $qb->where('c.name = :c')
            ->setParameter('c', $category)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function findBySlug($slug)
    {
        $qb = $this->createQueryBuilder('a')
                ->where('a.slug = :slug')
                ->setParameter('slug', $slug)
                ->leftJoin('a.categories', 'c')
                ->addSelect('c')
                ->leftJoin('a.challenge', 'ch')
                ->addSelect('ch')
                ->leftJoin('a.owners', 'o')
                ->addSelect('o')
                ->leftJoin('a.comments', 'co')
                ->addSelect('co')
                ->getQuery()
                ->getSingleResult();

        return $qb;
    }
}
