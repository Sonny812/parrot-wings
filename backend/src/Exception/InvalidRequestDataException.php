<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Exception;

use Exception;
use Throwable;

class InvalidRequestDataException extends Exception
{
    private array $errors;

    /**
     * InvalidRequestDataException constructor.
     *
     * @param array           $constraintViolationList
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $constraintViolationList, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->errors = $constraintViolationList;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
