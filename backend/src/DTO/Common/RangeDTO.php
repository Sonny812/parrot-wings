<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO\Common;

use Symfony\Component\Validator\Constraints as Assert;

class RangeDTO
{
    /**
     * @var int|null
     */
    private ?int $min;

    /**
     * @var int|null
     *
     * @Assert\GreaterThanOrEqual(propertyPath="min",)
     */
    private ?int $max;

    /**
     * RangeDTO constructor.
     *
     * @param int|null $min
     * @param int|null $max
     */
    public function __construct(?int $min, ?int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @param array $bounds
     *
     * @return static
     */
    public static function createFromBoundsArray(array $bounds): self
    {
        $from = $bounds['from'] ?? null;
        $to   = $bounds['to'] ?? null;

        return new self(
            $from ? (int)$from : null,
            $to ? (int)$to : null,
        );
    }

    /**
     * @return int|null
     */
    public function getMin(): ?int
    {
        return $this->min;
    }

    /**
     * @return int|null
     */
    public function getMax(): ?int
    {
        return $this->max;
    }
}
