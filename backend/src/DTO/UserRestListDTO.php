<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserRestListDTO extends RestListDTO
{
    /**
     * @var \App\DTO\UserFilterDTO|null
     *
     * @Assert\Valid()
     */
    private ?UserFilterDTO $userFilterDTO;

    /**
     * UserRestListDTO constructor.
     *
     * @param int                         $start
     * @param int                         $end
     * @param string                      $sortDirection
     * @param string                      $sortField
     * @param string|null                 $searchQuery
     * @param \App\DTO\UserFilterDTO|null $userFilterDTO
     */
    public function __construct(
        int $start,
        int $end,
        string $sortDirection,
        string $sortField,
        ?string $searchQuery,
        ?UserFilterDTO $userFilterDTO
    ) {
        parent::__construct($start, $end, $sortDirection, $sortField, $searchQuery);
        $this->userFilterDTO = $userFilterDTO;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function createFromRequestData(array $data): self
    {
        $start = $data['_start'] ?? 0;
        $end   = $data['_end'] ?? 10;

        return new self(
            (int)$start,
            (int)$end,
            $data['_order'] ?? 'ASC',
            $data['_sort'] ?? 'id',
            $data['q'] ?? null,
            UserFilterDTO::createFromFilterArray($data['filter'] ?? [])
        );
    }

    /**
     * @return array
     */
    public static function getSortFields(): array
    {
        return array_merge(parent::getSortFields(), ['username', 'email', 'account.balance']);
    }

    /**
     * @return \App\DTO\UserFilterDTO|null
     */
    public function getUserFilterDTO(): ?UserFilterDTO
    {
        return $this->userFilterDTO;
    }
}
