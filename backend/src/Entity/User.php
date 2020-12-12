<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     * @ORM\Id()
     */
    private ?int $id;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $username;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $salt;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $password;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private string $token;

    /**
     * @var array
     *
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private array $roles;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private bool $blocked;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = [];
        $this->blocked = false;
        $this->salt = bin2hex(random_bytes(64));
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return User
     */
    public function setId(?int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     *
     * @return User
     */
    public function setSalt(string $salt): User
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return User
     */
    public function setToken(string $token): User
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return array_unique(array_merge($this->roles, ['ROLE_USER']));
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     *
     * @return User
     */
    public function setBlocked(bool $blocked): User
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // do nothing
    }
}
