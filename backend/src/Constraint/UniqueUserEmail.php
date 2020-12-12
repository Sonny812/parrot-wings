<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Unique user email
 *
 * @Annotation
 */
class UniqueUserEmail extends Constraint
{
    public string $message = 'The user with email "{{ email }}" already exists';
}
