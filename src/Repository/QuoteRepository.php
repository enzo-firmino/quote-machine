<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Quote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quote::class);
    }

    public function random(Category $category = null)
    {
        $repo = $this->_em->getRepository(Quote::class);

        if (null != $category) {
            $res = $repo->createQueryBuilder('q')
                ->where('q.category = :category')
                ->setParameter('category', 'Kaamelott')
                ->getQuery()
                ->getResult();
        } else {
            $res = $repo->createQueryBuilder('q')
                ->getQuery()
                ->getResult();
        }

        shuffle($res);
        if (null == $res) {
            return null;
        } else {
            return $res[0];
        }
    }
}
