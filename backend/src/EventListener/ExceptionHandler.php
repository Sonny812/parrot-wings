<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Exception\InvalidRequestDataException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onInvalidRequestDataException', 10],
                ['onHttpException', 0],
            ],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     */
    public function onInvalidRequestDataException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof InvalidRequestDataException) {
            return;
        }

        $errors = $exception->getErrors();

        $event->setResponse(new JsonResponse([
            'message' => implode(array_map(fn(array $error) => $error['text'], $errors)),
            'errors'  => $errors,
        ], Response::HTTP_BAD_REQUEST));

        $event->stopPropagation();
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     */
    public function onHttpException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        
        if (!$exception instanceof HttpException) {
            return;
        }

        $message = $exception->getMessage();

        $event->setResponse(new JsonResponse([
            'message' => $message,
            'errors'  => [['text' => $message]],
        ], $exception->getStatusCode()));

        $event->stopPropagation();
    }
}
