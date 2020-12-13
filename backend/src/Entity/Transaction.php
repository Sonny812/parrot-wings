<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Account\AbstractAccount;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Transaction
 *
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @ORM\Id()
     *
     * @Groups({"show_transaction", "list_transaction"})
     */
    private ?int $id;

    /**
     * @var \App\Entity\Account\AbstractAccount
     *
     * @ORM\ManyToOne(targetEntity=AbstractAccount::class, cascade={"persist"})
     *
     * @Groups({"show_transaction", "list_transaction"})
     */
    private AbstractAccount $from;

    /**
     * @var \App\Entity\Account\AbstractAccount
     *
     * @ORM\ManyToOne(targetEntity=AbstractAccount::class, cascade={"persist"})
     *
     * @Groups({"show_transaction", "list_transaction"})
     */
    private AbstractAccount $to;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"show_transaction", "list_transaction"})
     */
    private int $amount;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     *
     * @Groups({"show_transaction", "list_transaction"})
     */
    private DateTimeImmutable $createdAt;

    /**
     * Transaction constructor.
     *
     * @param \App\Entity\Account\AbstractAccount $from
     * @param \App\Entity\Account\AbstractAccount $to
     * @param int                                 $amount
     */
    public function __construct(AbstractAccount $from, AbstractAccount $to, int $amount)
    {
        $this->from      = $from;
        $this->to        = $to;
        $this->amount    = $amount;
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\Account\AbstractAccount
     */
    public function getFrom(): AbstractAccount
    {
        return $this->from;
    }

    /**
     * @return \App\Entity\Account\AbstractAccount
     */
    public function getTo(): AbstractAccount
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
