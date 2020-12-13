<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

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
}
