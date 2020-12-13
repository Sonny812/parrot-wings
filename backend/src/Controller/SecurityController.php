<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\DTO\AuthUserDTO;
use App\DTO\RegisterUserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\User\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Security controller
 */
class SecurityController extends AbstractController
{
    private UserFactory $userFactory;

    private EntityManagerInterface $em;

    private UserPasswordEncoderInterface $encoder;

    private SerializerInterface $serializer;

    /**
     * SecurityController constructor.
     *
     * @param \App\User\UserFactory                                                 $userFactory
     * @param \Doctrine\ORM\EntityManagerInterface                                  $em
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     * @param \Symfony\Component\Serializer\SerializerInterface                     $serializer
     */
    public function __construct(
        UserFactory $userFactory,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer
    ) {
        $this->userFactory = $userFactory;
        $this->em          = $em;
        $this->encoder     = $encoder;
        $this->serializer  = $serializer;
    }

    /**
     * @Route("/register", methods={"POST"})
     *
     * @param \App\DTO\RegisterUserDTO $registerUserDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(RegisterUserDTO $registerUserDTO): Response
    {
        $user = $this->userFactory->createUserFromRegisterDTO($registerUserDTO);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json(['success' => true], Response::HTTP_CREATED);
    }

    /**
     * @Route("/login", methods={"POST"})
     *
     * @param \App\DTO\AuthUserDTO $authUserDTO
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function login(AuthUserDTO $authUserDTO): Response
    {
        $user = $this->getUserRepository()->findOneBy(['email' => $authUserDTO->getEmail()]);

        if (null === $user || !$this->encoder->isPasswordValid($user, $authUserDTO->getRawPassword() ?? '')) {
            throw new UnauthorizedHttpException('Login', 'User with this email not found or password is not correct.');
        }

        if (!$this->isGranted('login', $user)) {
            throw new UnauthorizedHttpException('Login', 'Your account is blocked.');
        }

        $token = bin2hex(random_bytes(36));
        $user->setToken($token);

        $this->em->flush();

        return JsonResponse::fromJsonString($this->serializer->serialize($user, 'json', [
            'groups' => [
                'show_user', 'with_balance', 'with_token', 'with_roles'
            ],
        ]));
    }

    /**
     * @return \App\Repository\UserRepository
     */
    private function getUserRepository(): UserRepository
    {
        return $this->em->getRepository(User::class);
    }
}
