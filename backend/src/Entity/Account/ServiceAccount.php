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
 * @ORM\Entity()
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
     *
     * @param string $tag
     */
    public function __construct(string $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @inheritDoc
     */
    public function canHaveNegativeBalance(): bool
    {
        return true;
    }
}
