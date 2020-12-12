<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private int $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
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
    abstract public function canHaveNegativeBalance(): bool;

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
     */
    public function decreaseBalance(int $amount): void
    {
        $this->balance -= $amount;
    }
}
