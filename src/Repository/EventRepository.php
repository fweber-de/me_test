<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param DateTime $startBy
     * @param string $order
     * @return float|int|mixed|string
     */
    public function findUpcoming(DateTime $startBy, string $order = 'ASC'): mixed
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.start_date >= :val')
            ->setParameter('val', $startBy)
            ->orderBy('e.start_date', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param string $order
     * @return float|int|mixed|string
     */
    public function findByTimeframe(DateTime $start, DateTime $end, string $order = 'ASC'): mixed
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.start_date >= :d1')
            ->andWhere('e.start_date <= :d2')
            ->setParameter('d1', $start)
            ->setParameter('d2', $end)
            ->orderBy('e.start_date', $order)
            ->getQuery()
            ->getResult()
        ;
    }
}
