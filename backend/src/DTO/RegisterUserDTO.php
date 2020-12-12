<?php declare(strict_types=1);

/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Constraint\UniqueUserEmail;

class RegisterUserDTO
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    private ?string $username;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueUserEmail()
     */
    private ?string $email;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    private ?string $rawPassword;

    /**
     * RegisterUserDTO constructor.
     *
     * @param string|null $username
     * @param string|null $email
     * @param string|null $rawPassword
     */
    public function __construct(?string $username, ?string $email, ?string $rawPassword)
    {
        $this->username    = $username;
        $this->email       = $email;
        $this->rawPassword = $rawPassword;
    }

    /**
     * @param array|null $data
     *
     * @return $this
     */
    public static function createFromRequestData(?array $data): self
    {
        return new self(
            $data['username'] ?? null,
            $data['email'] ?? null,
            $data['rawPassword'] ?? null
        );
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }
}
