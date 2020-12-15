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

class TransactionRestListDTO extends RestListDTO
{
    /**
     * @var \App\DTO\TransactionFilterDTO|null
     *
     * @Assert\Valid()
     */
    private ?TransactionFilterDTO $transactionFilterDTO;

    /**
     * @return array
     */
    public static function getSortFields(): array
    {
        return array_merge(parent::getSortFields(), ['createdAt', 'amount']);
    }

    /**
     * TransactionRestListDTO constructor.
     *
     * @param int                                $start
     * @param int                                $end
     * @param string                             $sortDirection
     * @param string                             $sortField
     * @param \App\DTO\TransactionFilterDTO|null $transactionFilterDTO
     */
    public function __construct(int $start, int $end, string $sortDirection, string $sortField, ?TransactionFilterDTO $transactionFilterDTO)
    {
        parent::__construct($start, $end, $sortDirection, $sortField);

        $this->transactionFilterDTO = $transactionFilterDTO;
    }

    /**
     * @param array $data
     *
     * @return \App\DTO\RestListDTO
     */
    public static function createFromRequestData(array $data): RestListDTO
    {
        $start  = $data['_start'] ?? 0;
        $end    = $data['_end'] ?? 10;
        $filter = $data['filter'] ?? null;

        return new self(
            (int)$start,
            (int)$end,
            $data['_order'] ?? 'ASC',
            $data['_sort'] ?? 'id',
            $filter ? TransactionFilterDTO::createFromFilterArray($filter) : null
        );
    }

    /**
     * @return \App\DTO\TransactionFilterDTO|null
     */
    public function getTransactionFilterDTO(): ?TransactionFilterDTO
    {
        return $this->transactionFilterDTO;
    }
}
