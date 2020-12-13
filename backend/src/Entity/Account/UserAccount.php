<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Account;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * User account
 *
 * @ORM\Entity(repositoryClass="App\Repository\Account\UserAccountRepository")
 */
class UserAccount extends AbstractAccount
{
    /**
     * @var \App\Entity\User
     *
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="account")
     */
    private User $user;

    /**
     * @inheritDoc
     */
    public function canHaveNegativeBalance(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return sprintf("%s (%s)", $this->user->getUsername(), $this->user->getEmail());
    }
}
