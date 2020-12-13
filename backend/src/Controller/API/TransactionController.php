<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\API;

use App\DTO\RestListDTO;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Response\RestListResponse;
use App\Transaction\TransactionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param \App\DTO\RestListDTO $restListDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(RestListDTO $restListDTO): Response
    {
        $repository = $this->getTransactionRepository();

        if ($this->isGranted('ROLE_ADMIN')) {
            $qb = $repository->createDefaultQueryBuilder();
        } else {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            $qb = $repository->createQueryBuilderForAccount($user->getAccount());
        }

        return new RestListResponse($restListDTO, $qb, $this->serializer);
    }

    /**
     * @return \App\Repository\TransactionRepository
     */
    private function getTransactionRepository(): TransactionRepository
    {
        return $this->em->getRepository(Transaction::class);
    }
}
