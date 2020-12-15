<?php declare(strict_types=1);
/**
 * @author    Nickolay Mikhaylov <sonny@milton.pro>
 * @copyright Copyright (c) 2020, Nikolay Mikhaylov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\API;

use App\Mercure\TokenFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

/**
 * Balance controller
 *
 * @Route("/balance")
 */
class BalanceController extends AbstractController
{
    private TokenFactory $tokenFactory;

    /**
     * BalanceController constructor.
     *
     * @param \App\Mercure\TokenFactory $tokenFactory
     */
    public function __construct(TokenFactory $tokenFactory)
    {
        $this->tokenFactory = $tokenFactory;
    }

    /**
     * @Route("", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $account = $user->getAccount();

        if (null === $account) {
            $this->createNotFoundException('User account not found');
        }

        $hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure', $hubUrl));

        $topic = sprintf('balance/%d', $account->getId());

        return $this->json([
            'balance'        => $account->getBalance(),
            'subscribeTopic' => $topic,
            'token'          => $this->tokenFactory->createSubscribeToken([$topic]),
        ]);
    }
}
