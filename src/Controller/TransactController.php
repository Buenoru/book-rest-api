<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\TransactService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class TransactController extends AbstractController
{
    private TransactService $transactService;
    private RequestStack $requestStack;

    public function __construct(TransactService $transactService, RequestStack $requestStack)
    {
        $this->transactService = $transactService;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/user/{idFrom<\d+>}/transact/{idTo<\d+>}", methods={"POST"}, name="app_transact")
     */
    public function index(int $idFrom, int $idTo): Response
    {
        try {
            // @todo: десериализациям и валидациям тут не место.
            if (!$body = json_decode($this->requestStack->getCurrentRequest()->getContent(), true)) {
                throw new InvalidArgumentException('Error request body parsing');
            }
            if (!($amount = $body['amount'] ?? false) || (int)$amount < 0) {
                throw new InvalidArgumentException(sprintf('Invalid amount value (%s)', $amount));
            }

            $res = $this->transactService->transactByUsersIds($idFrom, $idTo, (int)$amount);

            return $this->json([
                'message' => $res,
            ]);
        } catch (Throwable $t) {
            return $this->json(
                [
                    'error' => $t->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
