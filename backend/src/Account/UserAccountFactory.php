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
use App\Entity\Account\ServiceAccount;
use App\Entity\Account\UserAccount;
use App\Repository\Account\ServiceAccountRepository;
use App\Transaction\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * User account factory
 */
class UserAccountFactory implements AccountFactoryInterface
{
    private const WELCOME_BONUS_AMOUNT = 500;

    private EntityManagerInterface $em;

    private TransactionManager $transactionManager;

    /**
     * UserAccountFactory constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \App\Transaction\TransactionManager  $transactionManager
     */
    public function __construct(EntityManagerInterface $em, TransactionManager $transactionManager)
    {
        $this->em                 = $em;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return \App\Entity\Account\AbstractAccount
     */
    public function createAccount(): AbstractAccount
    {
        $account = new UserAccount();

        $this->giveWelcomeBonus($account);

        return $account;
    }

    /**
     * @param \App\Entity\Account\AbstractAccount $account
     */
    private function giveWelcomeBonus(AbstractAccount $account): void
    {
        $welcomeBonusAccount = $this->getServiceAccountRepository()->findOneBy(['tag' => 'welcome_bonus']);

        // bonus will not be added if service account does not exist
        if (null === $welcomeBonusAccount) {
            return;
        }

        $this->transactionManager->makeTransaction(
            $welcomeBonusAccount,
            $account,
            self::WELCOME_BONUS_AMOUNT,
            false
        );
    }

    /**
     * @return \App\Repository\Account\ServiceAccountRepository
     */
    private function getServiceAccountRepository(): ServiceAccountRepository
    {
        return $this->em->getRepository(ServiceAccount::class);
    }
}
