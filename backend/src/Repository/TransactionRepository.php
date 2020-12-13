<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Account\AbstractAccount;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
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

    public function createQueryBuilderForAccount(AbstractAccount $account): QueryBuilder
    {
        $qb = $this->createDefaultQueryBuilder();

        $qb->where(
            $qb->expr()->orX(
                'transaction.from = :account',
                'transaction.to = :account'
            )
        )->setParameter('account', $account);

        return $qb;
    }
}
