<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationListToErrorArrayConvertor
{
    /**
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $constraintViolationList
     *
     * @return array
     */
    public static function convert(ConstraintViolationListInterface $constraintViolationList): array
    {
        $errors = [];

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $errors [] = [
                'path' => $constraintViolation->getPropertyPath(),
                'text' => $constraintViolation->getMessage(),
            ];
        }

        return $errors;
    }
}
