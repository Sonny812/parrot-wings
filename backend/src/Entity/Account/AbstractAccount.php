<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Account;

use App\Exception\Account\NegativeBalanceException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Account
 *
 * @ORM\Entity()
 * @ORM\Table(name="account")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
abstract class AbstractAccount
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @ORM\Id()
     *
     * @Groups({"show_transaction", "list_transaction", "show_user", "list_user"})
     */
    private int $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"with_balance"})
     */
    private int $balance;

    /**
     * AbstractAccount constructor.
     */
    public function __construct()
    {
        $this->balance = 0;
    }

    /**
     * @return bool
     */
    abstract public function isNegativeBalanceAllowed(): bool;

    /**
     * @return string
     *
     * @Groups({"show_transaction", "list_transaction", "show_user", "list_user"})
     */
    abstract public function getDisplayName(): string;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $amount
     */
    public function increaseBalance(int $amount): void
    {
        $this->balance += $amount;
    }

    /**
     * @param int $amount
     *
     * @throws \App\Exception\Account\NegativeBalanceException
     */
    public function decreaseBalance(int $amount): void
    {
        $this->balance -= $amount;

        if ($this->balance < 0 && !$this->isNegativeBalanceAllowed()) {
            throw new NegativeBalanceException();
        }
    }
}
