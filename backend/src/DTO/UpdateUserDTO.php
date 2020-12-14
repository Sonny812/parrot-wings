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

class UpdateUserDTO
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
     */
    private ?string $email;

    /**
     * @var bool|null
     *
     * @Assert\NotNull()
     */
    private ?bool $blocked;

    /**
     * UpdateUserDTO constructor.
     *
     * @param string|null $username
     * @param string|null $email
     * @param bool|null   $blocked
     */
    public function __construct(?string $username, ?string $email, ?bool $blocked)
    {
        $this->username = $username;
        $this->email    = $email;
        $this->blocked  = $blocked;
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
            $data['blocked'] ?? null
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
     * @return bool|null
     */
    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }
}
