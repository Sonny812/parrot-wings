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

class MakeTransactionDTO
{
    /**
     * @var int|null
     *
     * @Assert\NotBlank()
     * @Assert\PositiveOrZero()
     */
    private ?int $recipientId;

    /**
     * @var int|null
     *
     * @Assert\NotBlank()
     * @Assert\PositiveOrZero()
     */
    private ?int $amount;

    /**
     * MakeTransactionDTO constructor.
     *
     * @param int|null $recipientId
     * @param int|null $amount
     */
    public function __construct(?int $recipientId, ?int $amount)
    {
        $this->recipientId = $recipientId;
        $this->amount      = $amount;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function createFromRequestData(array $data): self
    {
        return new self(
            $data['recipientId'] ?? null,
            $data['amount'] ?? null
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
    public function getAmount(): ?int
    {
        return $this->amount;
    }
}
