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

class RestListDTO
{
    public const SORT_DIRECTIONS = ['ASC', 'DESC'];

    /**
     * @var int
     *
     * @Assert\PositiveOrZero()
     */
    private int $start;

    /**
     * @var int
     *
     * @Assert\GreaterThan("start")
     */
    private int $end;

    /**
     * @var string
     *
     * @Assert\Choice(choices=self::SORT_DIRECTIONS)
     */
    private string $sortDirection;

    /**
     * @var string
     */
    private string $sortField;

    /**
     * @var string|null
     */
    private ?string $searchQuery;

    /**
     * RestListDTO constructor.
     *
     * @param int         $start
     * @param int         $end
     * @param string      $sortDirection
     * @param string      $sortField
     * @param string|null $searchQuery
     */
    public function __construct(int $start, int $end, string $sortDirection, string $sortField, ?string $searchQuery)
    {
        $this->start         = $start;
        $this->end           = $end;
        $this->sortDirection = $sortDirection;
        $this->sortField     = $sortField;
        $this->searchQuery   = $searchQuery;
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
        );
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getEnd(): int
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    /**
     * @return string
     */
    public function getSortField(): string
    {
        return $this->sortField;
    }

    /**
     * @return string|null
     */
    public function getSearchQuery(): ?string
    {
        return $this->searchQuery;
    }
}
