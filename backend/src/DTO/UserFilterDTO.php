<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

use App\DTO\Common\RangeDTO;
use Symfony\Component\Validator\Constraints as Assert;

class UserFilterDTO
{
    /**
     * @var \App\DTO\Common\RangeDTO|null
     *
     * @Assert\Valid()
     */
    private ?RangeDTO $balanceRangeDTO;

    /**
     * UserFilterDTO constructor.
     *
     * @param \App\DTO\Common\RangeDTO|null $balanceRangeDTO
     */
    public function __construct(?RangeDTO $balanceRangeDTO)
    {
        $this->balanceRangeDTO = $balanceRangeDTO;
    }

    /**
     * @param array $filter
     *
     * @return $this
     */
    public static function createFromFilterArray(array $filter): self
    {
        return new self(RangeDTO::createFromBoundsArray($filter['balance'] ?? []));
    }

    /**
     * @return \App\DTO\Common\RangeDTO|null
     */
    public function getBalanceRangeDTO(): ?RangeDTO
    {
        return $this->balanceRangeDTO;
    }
}
