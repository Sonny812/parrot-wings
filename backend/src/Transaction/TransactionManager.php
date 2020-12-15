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
use App\Exception\Account\NegativeBalanceException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class TransactionManager
{
    private EntityManagerInterface $em;

    private TransactionPublisher $transaction;

    /**
     * TransactionManager constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface  $em
     * @param \App\Transaction\TransactionPublisher $transactionPublisher
     */
    public function __construct(EntityManagerInterface $em, TransactionPublisher $transactionPublisher)
    {
        $this->em          = $em;
        $this->transaction = $transactionPublisher;
    }

    /**
     * @param \App\Entity\Account\AbstractAccount $from
     * @param \App\Entity\Account\AbstractAccount $to
     * @param int                                 $amount
     *
     * @return \App\Entity\Transaction
     */
    public function makeTransaction(AbstractAccount $from, AbstractAccount $to, int $amount): Transaction
    {
        if ($from === $to) {
            throw new ConflictHttpException('Unable to make transaction when the recipient and sender are same.');
        }

        $transaction = new Transaction($from, $to, $amount);

        try {
            $this->em->transactional(function ($em) use ($transaction) {
                $em->persist($transaction);

                $amount = $transaction->getAmount();

                $transaction->getFrom()->decreaseBalance($amount);
                $transaction->getTo()->increaseBalance($amount);
            });
        } catch (NegativeBalanceException $exception) {
            throw new ConflictHttpException('Creating this transaction will result in a negative balance on any account');
        }

        $this->transaction->publish($transaction);

        return $transaction;
    }
}
