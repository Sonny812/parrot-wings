<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Account;

use App\Entity\Account\AbstractAccount;
use App\Entity\Account\UserAccount;

/**
 * User account factory
 */
class UserAccountFactory implements AccountFactoryInterface
{
    /**
     * @return \App\Entity\Account\AbstractAccount
     */
    public function createAccount(): AbstractAccount
    {
        $account = new UserAccount();

        $account->increaseBalance(500);

        return $account;
    }
}
