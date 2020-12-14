<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\API;

use App\DTO\MakeTransactionDTO;
use App\DTO\TransactionRestListDTO;
use App\Entity\Account\UserAccount;
use App\Entity\Transaction;
use App\Repository\Account\UserAccountRepository;
use App\Repository\TransactionRepository;
use App\Response\RestListResponse;
use App\Transaction\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Transaction controller
 *
 * @Route("/transaction")
 */
class TransactionController extends AbstractController
{
    private EntityManagerInterface $em;

    private TransactionManager $transactionManager;

    private SerializerInterface $serializer;

    /**
     * TransactionController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface              $em
     * @param \App\Transaction\TransactionManager               $transactionManager
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(EntityManagerInterface $em, TransactionManager $transactionManager, SerializerInterface $serializer)
    {
        $this->em                 = $em;
        $this->transactionManager = $transactionManager;
        $this->serializer         = $serializer;
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @param \App\DTO\TransactionRestListDTO $restListDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(TransactionRestListDTO $restListDTO): Response
    {
        $repository = $this->getTransactionRepository();

        if ($this->isGranted('ROLE_ADMIN')) {
            $qb = $repository->createDefaultQueryBuilder();
        } else {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            $qb = $repository->createQueryBuilderForAccount($user->getAccount());
        }

        return new RestListResponse($restListDTO, $qb, $this->serializer, ['list_transaction']);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(int $id): Response
    {
        $transaction = $this->getTransactionRepository()->find($id);

        if (null === $transaction) {
            $this->createNotFoundException(sprintf('Transaction with id %d not found', $id));
        }

        $json = $this->serializer->serialize($transaction, 'json', [
            'context' => [
                'groups' => 'show_transaction',
            ],
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param \App\DTO\MakeTransactionDTO $makeTransactionDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(MakeTransactionDTO $makeTransactionDTO): Response
    {
        $recipientId      = $makeTransactionDTO->getRecipientId();
        $recipientAccount = $this->getUserAccountRepository()->find($recipientId);

        if (null === $recipientAccount) {
            $this->createNotFoundException(sprintf('User account with ID %s not found', $recipientId));
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $transaction = $this->transactionManager->makeTransaction(
            $user->getAccount(),
            $recipientAccount,
            $makeTransactionDTO->getAmount()
        );

        $json = $this->serializer->serialize($transaction, 'json', [
            'context' => [
                'groups' => 'show_transaction',
            ],
        ]);

        return JsonResponse::fromJsonString($json, Response::HTTP_CREATED);
    }

    /**
     * @return \App\Repository\TransactionRepository
     */
    private function getTransactionRepository(): TransactionRepository
    {
        return $this->em->getRepository(Transaction::class);
    }

    /**
     * @return \App\Repository\Account\UserAccountRepository
     */
    private function getUserAccountRepository(): UserAccountRepository
    {
        return $this->em->getRepository(UserAccount::class);
    }
}
