<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Balance controller
 *
 * @Route("/balance")
 */
class BalanceController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $account = $user->getAccount();

        if (null === $account) {
            $this->createNotFoundException('User account not found');
        }

        return new JsonResponse([
            'balance' => $account->getBalance(),
        ]);
    }
}
