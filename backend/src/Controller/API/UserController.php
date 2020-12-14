<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\API;

use App\DTO\UpdateUserDTO;
use App\DTO\UserRestListDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Response\RestListResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * User controller
 *
 * @Route("/user")
 *
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
     * @param \App\DTO\UserRestListDTO $restListDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserRestListDTO $restListDTO): Response
    {
        $repository = $this->getUserRepository();
        $qb         = $repository->getQueryForFilteredList($restListDTO->getUserFilterDTO());

        $searchQuery = $restListDTO->getSearchQuery();

        if (null !== $searchQuery) {
            $repository->applySearchQuery($qb, $searchQuery);
        }
        
        $groups = ['list_user'];

        if ($this->isGranted('ROLE_ADMIN')) {
            $groups [] = 'with_balance';
        }

        return new RestListResponse($restListDTO, $qb, $this->serializer, $groups);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(int $id): Response
    {
        $user = $this->getUserRepository()->find($id);

        if (null === $user) {
            $this->createNotFoundException(sprintf('User with id %d not found', $id));
        }

        $json = $this->serializer->serialize($user, 'json', [
            'groups' => [
                'show_user',
                'with_balance',
            ],
        ]);

        return JsonResponse::fromJsonString($json);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int                    $id
     * @param \App\DTO\UpdateUserDTO $userDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(int $id, UpdateUserDTO $userDTO): Response
    {
        $repository = $this->getUserRepository();
        $user       = $repository->find($id);

        if (null === $user) {
            $this->createNotFoundException(sprintf('User with id %d not found', $id));
        }

        $email = $userDTO->getEmail();

        if ($repository->findOneBy(['email' => $email]) !== $user && $user->getEmail() !== $email) {
            throw new ConflictHttpException('Email already in use');
        }

        $user->updateFromDTO($userDTO);

        $json = $this->serializer->serialize($user, 'json', [
            'groups' => [
                'show_user',
                'with_balance',
            ],
        ]);

        $this->em->flush();;

        return JsonResponse::fromJsonString($json);
    }

    /**
     * @return \App\Repository\UserRepository
     */
    private function getUserRepository(): UserRepository
    {
        return $this->em->getRepository(User::class);
    }
}
