<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mercure;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class TokenFactory
{
    private string $mercureSecret;

    /**
     * TokenFactory constructor.
     *
     * @param string $mercureSecret
     */
    public function __construct(string $mercureSecret)
    {
        $this->mercureSecret = $mercureSecret;
    }

    /**
     * @param array $topics
     *
     * @return string
     */
    public function createSubscribeToken(array $topics): string
    {
        $key           = Key\InMemory::plainText($this->mercureSecret);
        $configuration = Configuration::forSymmetricSigner(new Sha256(), $key);

        $token = $configuration->builder()
            ->withClaim('mercure', ['subscribe' => $topics])
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString();

        return $token;
    }
}
