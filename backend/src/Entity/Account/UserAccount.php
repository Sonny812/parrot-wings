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
 * User account
 *
 * @ORM\Entity()
 */
class UserAccount extends AbstractAccount
{
    /**
     * @inheritDoc
     */
    public function canHaveNegativeBalance(): bool
    {
        return false;
    }
}
