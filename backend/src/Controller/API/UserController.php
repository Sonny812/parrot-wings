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
use App\Entity\User;
use App\Repository\UserRepository;
use App\Response\RestListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * User controller
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private EntityManagerInterface $em;

    private SerializerInterface $serializer;

    /**
     * UserController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface              $em
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em         = $em;
        $this->serializer = $serializer;
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
        $repository = $this->getUserRepository();
        $qb         = $repository->createDefaultQueryBuilder();

        $searchQuery = $restListDTO->getSearchQuery();

        if (null !== $searchQuery) {
            $repository->applySearchQuery($qb, $searchQuery);
        }

        return new RestListResponse($restListDTO, $qb, $this->serializer, ['list_user']);

    }

    /**
     * @return \App\Repository\UserRepository
     */
    private function getUserRepository(): UserRepository
    {
        return $this->em->getRepository(User::class);
    }
}
