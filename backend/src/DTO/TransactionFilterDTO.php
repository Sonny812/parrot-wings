<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

use App\DTO\Common\DateRangeDTO;
use App\DTO\Common\RangeDTO;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionFilterDTO
{
    /**
     * @var int|null
     */
    private ?int $recipientId;

    /**
     * @var int|null
     */
    private ?int $senderId;

    /**
     * @var \App\DTO\Common\DateRangeDTO|null
     *
     * @Assert\Valid()
     */
    private ?DateRangeDTO $createdAtRangeDTO;

    /**
     * @var \App\DTO\Common\RangeDTO|null
     *
     * @Assert\Valid()
     */
    private ?RangeDTO $amountRangeDTO;

    /**
     * TransactionFilterDTO constructor.
     *
     * @param int|null                          $recipientId
     * @param int|null                          $senderId
     * @param \App\DTO\Common\DateRangeDTO|null $createdAtRangeDTO
     * @param \App\DTO\Common\RangeDTO|null     $amountRangeDTO
     */
    public function __construct(?int $recipientId, ?int $senderId, ?DateRangeDTO $createdAtRangeDTO, ?RangeDTO $amountRangeDTO)
    {
        $this->recipientId       = $recipientId;
        $this->senderId          = $senderId;
        $this->createdAtRangeDTO = $createdAtRangeDTO;
        $this->amountRangeDTO    = $amountRangeDTO;
    }

    /**
     * @param array $filter
     *
     * @return $this
     */
    public static function createFromFilterArray(array $filter): self
    {
        $to     = $filter['to'] ?? null;
        $from   = $filter['from'] ?? null;
        $date   = $filter['date'] ?? null;
        $amount = $filter['amount'] ?? null;

        return new self(
            $to ? (int)$to : null,
            $from ? (int)$from : null,
            $date ? DateRangeDTO::createFromBoundsArray($date) : null,
            $amount ? RangeDTO::createFromBoundsArray($amount) : null
        );
    }

    /**
     * @return int|null
     */
    public function getRecipientId(): ?int
    {
        return $this->recipientId;
    }

    /**
     * @return int|null
     */
    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    /**
     * @return \App\DTO\Common\DateRangeDTO|null
     */
    public function getCreatedAtRangeDTO(): ?DateRangeDTO
    {
        return $this->createdAtRangeDTO;
    }

    /**
     * @return \App\DTO\Common\RangeDTO|null
     */
    public function getAmountRangeDTO(): ?RangeDTO
    {
        return $this->amountRangeDTO;
    }
}
