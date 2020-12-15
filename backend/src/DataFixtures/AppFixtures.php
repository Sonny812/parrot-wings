<?php

namespace App\DataFixtures;

use App\DTO\RegisterUserDTO;
use App\Entity\Account\AbstractAccount;
use App\Entity\Account\ServiceAccount;
use App\Entity\User;
use App\Transaction\TransactionManager;
use App\User\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserFactory $userFactory;

    private Generator $faker;

    private TransactionManager $transactionManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * AppFixtures constructor.
     *
     * @param \App\User\UserFactory                                                 $userFactory
     * @param \App\Transaction\TransactionManager                                   $transactionManager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserFactory $userFactory, TransactionManager $transactionManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userFactory        = $userFactory;
        $this->transactionManager = $transactionManager;
        $this->passwordEncoder    = $passwordEncoder;
        $this->faker              = Factory::create('en');
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createServiceAccountForWelcomeBonus());
        $manager->flush();

        $users = [];

        for ($i = 0; $i < 50; $i++) {
            $user = $this->createRandomUser();
            $manager->persist($user);
            $users [] = $user;
        }

        $accounts = array_map(fn(User $user) => $user->getAccount(), $users);

        for ($i = 0; $i < 1000; $i++) {
            [$from, $to] = $this->faker->randomElements($accounts, 2);

            $fromBalance = $from->getBalance();

            if ($fromBalance === 0) {
                continue;
            }

            $amount = $this->faker->numberBetween(1, $fromBalance);

            $this->transactionManager->makeTransaction($from, $to, $amount, false);
        }

        $manager->persist($this->createAdmin());

        $manager->flush();
    }

    /**
     * @return \App\Entity\Account\AbstractAccount
     */
    private function createServiceAccountForWelcomeBonus(): AbstractAccount
    {
        $account = new ServiceAccount();
        $account->setTag('welcome_bonus');

        return $account;
    }

    /**
     * @return \App\Entity\User
     */
    private function createRandomUser(): User
    {
        $registerUserDTO = new RegisterUserDTO(
            $this->faker->name,
            $this->faker->email,
            $this->faker->password
        );

        return $this->userFactory->createUserFromRegisterDTO($registerUserDTO);
    }

    /**
     * @return \App\Entity\User
     */
    private function createAdmin(): User
    {
        $admin = new User();

        $admin
            ->setUsername('John Smith')
            ->setEmail('admin@example.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'));

        return $admin;
    }
}
