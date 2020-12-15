<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Transaction;

use App\Entity\Account\AbstractAccount;
use App\Entity\Transaction;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class TransactionPublisher
{
    private PublisherInterface $publisher;

    /**
     * UpdateBalanceNotifier constructor.
     *
     * @param \Symfony\Component\Mercure\PublisherInterface $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @param \App\Entity\Transaction $transaction
     */
    public function publish(Transaction $transaction): void
    {
        $this->publisher->__invoke($this->createUpdate($transaction->getFrom()));
        $this->publisher->__invoke($this->createUpdate($transaction->getTO()));
    }

    /**
     * @param \App\Entity\Account\AbstractAccount $account
     *
     * @return \Symfony\Component\Mercure\Update
     */
    private function createUpdate(AbstractAccount $account): Update
    {
        return new Update(
            sprintf('balance/%d', $account->getId()),
            json_encode(['balance' => $account->getBalance()]),
            true
        );
    }
}
