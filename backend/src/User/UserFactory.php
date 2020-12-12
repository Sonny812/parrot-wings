<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\User;

use App\Account\AccountFactoryInterface;
use App\Account\UserAccountFactory;
use App\DTO\RegisterUserDTO;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @var \App\Account\AccountFactoryInterface
     */
    private AccountFactoryInterface $accountFactory;

    /**
     * UserFactory constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Account\UserAccountFactory                                       $accountFactory
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserAccountFactory $accountFactory)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->accountFactory  = $accountFactory;
    }

    /**
     * @param \App\DTO\RegisterUserDTO $userDTO
     *
     * @return \App\Entity\User
     */
    public function createUserFromRegisterDTO(RegisterUserDTO $userDTO): User
    {
        $user = new User();

        $user
            ->setUsername($userDTO->getUsername())
            ->setEmail($userDTO->getEmail())
            ->setAccount($this->accountFactory->createAccount());

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $userDTO->getRawPassword());

        $user->setPassword($encodedPassword);

        return $user;
    }
}
