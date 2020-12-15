<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO\Common;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class DateRangeDTO
{
    /**
     * @var \DateTimeImmutable|null
     *
     */
    private ?DateTimeImmutable $min;

    /**
     * @var \DateTimeImmutable|null
     *
     * @Assert\GreaterThanOrEqual(propertyPath="min")
     */
    private ?DateTimeImmutable $max;

    /**
     * DateRangeDTO constructor.
     *
     * @param \DateTimeImmutable|null $min
     * @param \DateTimeImmutable|null $max
     */
    public function __construct(?\DateTimeImmutable $min, ?\DateTimeImmutable $max)
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
        $from = $bounds['after'] ?? null;
        $to   = $bounds['before'] ?? null;

        return new self(
            self::isDate($from) ? new DateTimeImmutable($from) : null,
            self::isDate($to) ? new DateTimeImmutable($to) : null
        );
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getMin(): ?\DateTimeImmutable
    {
        return $this->min;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getMax(): ?\DateTimeImmutable
    {
        return $this->max;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private static function isDate($value): bool
    {
        if (!$value) {
            return false;
        }

        try {
            new DateTimeImmutable($value);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
