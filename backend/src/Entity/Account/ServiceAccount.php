<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\Account;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service account
 *
 * @ORM\Entity(repositoryClass="App\Repository\Account\ServiceAccountRepository")
 */
class ServiceAccount extends AbstractAccount
{
    /**
     * @ORM\Column()
     *
     * @var string
     */
    private string $tag;

    /**
     * ServiceAccount constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function isNegativeBalanceAllowed(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayName(): string
    {
        return sprintf('Service (%s)', $this->tag);
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     *
     * @return ServiceAccount
     */
    public function setTag(string $tag): ServiceAccount
    {
        $this->tag = $tag;

        return $this;
    }
}
