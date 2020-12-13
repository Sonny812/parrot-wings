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
use Doctrine\ORM\EntityManagerInterface;

class TransactionManager
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * TransactionManager constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
        $transaction = new Transaction($from, $to, $amount);

        $this->em->transactional(function ($em) use ($transaction) {
            $em->persist($transaction);

            $amount = $transaction->getAmount();

            $transaction->getFrom()->decreaseBalance($amount);
            $transaction->getTo()->increaseBalance($amount);
        });

        return $transaction;
    }
}
