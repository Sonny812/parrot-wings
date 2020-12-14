<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\DTO\UserFilterDTO;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createDefaultQueryBuilder(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('user')
            ->join('user.account', 'account');
    }

    /**
     * @param \App\DTO\UserFilterDTO|null $userFilterDTO
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryForFilteredList(?UserFilterDTO $userFilterDTO): QueryBuilder
    {
        $qb = $this->createDefaultQueryBuilder();

        $this->applyFilter($qb, $userFilterDTO);

        return $qb;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $searchQuery
     *
     * @return $this
     */
    public function applySearchQuery(QueryBuilder $qb, string $searchQuery): self
    {
        $qb
            ->andWhere(
                $qb->expr()->orX(
                    'user.email LIKE :search_query',
                    'user.username LIKE :search_query'
                )
            )
            ->setParameter(':search_query', "%$searchQuery%");

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder  $qb
     * @param \App\DTO\UserFilterDTO|null $userFilterDTO
     *
     * @return \App\Repository\UserRepository
     */
    private function applyFilter(QueryBuilder $qb, ?UserFilterDTO $userFilterDTO): self
    {
        if (null === $userFilterDTO) {
            return $this;
        }

        $balanceRangeDTO = $userFilterDTO->getBalanceRangeDTO();

        if (null !== $balanceRangeDTO) {
            $min = $balanceRangeDTO->getMin();
            if (null !== $min) {
                $qb
                    ->andWhere('account.balance >= :min_balance')
                    ->setParameter('min_balance', $min);
            }

            $max = $balanceRangeDTO->getMax();
            if (null !== $max) {
                $qb
                    ->andWhere('account.balance <= :max_balance')
                    ->setParameter('max_balance', $max);
            }
        }

        return $this;
    }
}
