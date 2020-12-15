<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\DTO\TransactionFilterDTO;
use App\Entity\Account\AbstractAccount;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class TransactionRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createDefaultQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('transaction');
    }

    /**
     * @param \App\DTO\TransactionFilterDTO|null  $transactionFilterDTO
     * @param \App\Entity\Account\AbstractAccount $account
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderForFilteredListForAccount(?TransactionFilterDTO $transactionFilterDTO, AbstractAccount $account): QueryBuilder
    {
        $qb = $this->createDefaultQueryBuilder();

        $this
            ->applyFilter($qb, $transactionFilterDTO)
            ->applyForAccount($qb, $account);

        return $qb;
    }

    /**
     * @param \App\DTO\TransactionFilterDTO|null $transactionFilterDTO
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderForFilteredList(?TransactionFilterDTO $transactionFilterDTO): QueryBuilder
    {
        $qb = $this->createDefaultQueryBuilder();

        $this->applyFilter($qb, $transactionFilterDTO);

        return $qb;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder         $qb
     * @param \App\DTO\TransactionFilterDTO|null $transactionFilterDTO
     *
     * @return $this
     */
    private function applyFilter(QueryBuilder $qb, ?TransactionFilterDTO $transactionFilterDTO): self
    {
        if (null === $transactionFilterDTO) {
            return $this;
        }

        $from = $transactionFilterDTO->getSenderId();

        if (null !== $from) {
            $qb
                ->andWhere('transaction.from = :from')
                ->setParameter('from', $from);
        }

        $to = $transactionFilterDTO->getRecipientId();

        if (null !== $to) {
            $qb
                ->andWhere('transaction.to = :to')
                ->setParameter('to', $to);
        }

        $amountRangeDTO = $transactionFilterDTO->getAmountRangeDTO();

        if (null !== $amountRangeDTO) {
            $min = $amountRangeDTO->getMin();
            if (null !== $min) {
                $qb
                    ->andWhere('transaction.amount >= :min_amount')
                    ->setParameter('min_amount', $min);
            }

            $max = $amountRangeDTO->getMax();
            if (null !== $max) {
                $qb
                    ->andWhere('transaction.amount <= :max_amount')
                    ->setParameter('max_amount', $max);
            }
        }

        $dateRangeDto = $transactionFilterDTO->getCreatedAtRangeDTO();

        if (null !== $dateRangeDto) {
            $min = $dateRangeDto->getMin();
            if (null !== $min) {
                $qb
                    ->andWhere('transaction.createdAt >= :date_min')
                    ->setParameter('date_min', $min);
            }

            $max = $dateRangeDto->getMax();
            if (null !== $max) {
                $qb
                    ->andWhere('transaction.create <= :date_max')
                    ->setParameter('date_max', $max);
            }
        }

        return $this;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder          $qb
     * @param \App\Entity\Account\AbstractAccount $account
     *
     * @return $this
     */
    private function applyForAccount(QueryBuilder $qb, AbstractAccount $account): self
    {
        $qb
            ->where(
                $qb->expr()->orX(
                    'transaction.from = :account',
                    'transaction.to = :account'
                )
            )->setParameter('account', $account);

        return $this;
    }
}
