<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use App\DTO\RestListDTO;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator
{
    /**
     * @param \App\DTO\RestListDTO                           $restListDTO
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query
     *
     * @return static
     */
    public static function createFromRestListDTO(RestListDTO $restListDTO, $query): self
    {
        $rootAlias = $query->getRootAliases()[0];

        $query
            ->addOrderBy(sprintf('%s.%s', $rootAlias, $restListDTO->getSortField()), $restListDTO->getSortDirection())
            ->setFirstResult($restListDTO->getStart())
            ->setMaxResults($restListDTO->getEnd() - $restListDTO->getStart());

        return new self($query);
    }
}
