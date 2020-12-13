<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Response;

use App\DTO\RestListDTO;
use App\Utils\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RestListResponse extends JsonResponse
{
    /**
     * RestListResponse constructor.
     *
     * @param \App\DTO\RestListDTO                              $restListDTO
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder    $query
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     * @param array                                             $serializationGroups
     */
    public function __construct(RestListDTO $restListDTO, $query, SerializerInterface $serializer, array $serializationGroups)
    {
        $pagination = Paginator::createFromRestListDTO($restListDTO, $query);
        $json       = $serializer->serialize($pagination, 'json', ['groups' => $serializationGroups]);

        parent::__construct($json, Response::HTTP_OK, ['X-Total-Count' => count($pagination)], true);
    }
}
