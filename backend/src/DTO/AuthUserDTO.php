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

/**
 * Auth user DTO
 **/
class AuthUserDTO
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    private ?string $email;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    private ?string $rawPassword;

    /**
     * AuthUserDTO constructor.
     *
     * @param string|null $email
     * @param string|null $rawPassword
     */
    public function __construct(?string $email, ?string $rawPassword)
    {
        $this->email       = $email;
        $this->rawPassword = $rawPassword;
    }

    public static function createFromRequestData(?array $requestData): self
    {
        return new self(
            $requestData['email'] ?? null,
            $requestData['rawPassword'] ?? null,
        );
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
