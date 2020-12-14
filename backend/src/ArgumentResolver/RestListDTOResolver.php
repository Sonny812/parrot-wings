<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\ArgumentResolver;

use App\DTO\RestListDTO;
use App\Exception\InvalidRequestDataException;
use App\Utils\ConstraintViolationListToErrorArrayConvertor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RestListDTOResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;

    /**
     * UserDTOResolver constructor.
     *
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        return $type === RestListDTO::class || is_subclass_of($type, RestListDTO::class);
    }

    /**
     * @inheritDoc
     *
     * @throws \App\Exception\InvalidRequestDataException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var RestListDTO $type */
        $type = $argument->getType();

        $dto = $type::createFromRequestData($request->query->all());

        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            throw new InvalidRequestDataException(ConstraintViolationListToErrorArrayConvertor::convert($violations));
        }

        yield $dto;
    }
}
