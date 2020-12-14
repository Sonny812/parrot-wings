<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

class UserRestListDTO extends RestListDTO
{
    /**
     * @return array
     */
    public static function getSortFields(): array
    {
        return array_merge(parent::getSortFields(), ['username', 'email', 'account.balance']);
    }
}
