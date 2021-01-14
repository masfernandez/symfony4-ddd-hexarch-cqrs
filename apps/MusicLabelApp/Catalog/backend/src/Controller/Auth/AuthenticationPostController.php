<?php

declare(strict_types=1);

namespace Masfernandez\MusicLabelApp\Catalog\Infrastructure\Backend\Controller\Auth;

use Exception;
use Masfernandez\MusicLabel\Auth\Application\Token\GetNewToken\GetTokenQuery;
use Masfernandez\MusicLabel\Auth\Application\Token\GetNewToken\TokenResponse;
use Masfernandez\MusicLabel\Auth\Domain\Model\Token\InvalidCredentials;
use Masfernandez\MusicLabel\Auth\Domain\Model\User\UserNotFound;
use Masfernandez\MusicLabel\Auth\Infrastructure\InputRequest\Token\TokenPostInputData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationPostController extends AbstractController
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    #[Route(path: '/authentication', name: 'authentication_post', methods: ['POST'])]
    public function authenticate(TokenPostInputData $inputData): JsonResponse
    {
        try {
            /** @var HandledStamp $handledStamp */
            $handledStamp = $this->queryBus->dispatch(new GetTokenQuery(
                $inputData->getEmail(),
                $inputData->getPassword()
            ))->last(HandledStamp::class);

            /** @var TokenResponse $tokenResponse */
            $tokenResponse = $handledStamp->getResult();
            return new JsonResponse(null,
                Response::HTTP_CREATED, [
                'Location' => $tokenResponse->getToken()
            ]);

        } catch (Exception | HandlerFailedException $ex) {
            $prevException = $ex->getPrevious();
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($prevException instanceof UserNotFound || $prevException instanceof InvalidCredentials) {
                $code = Response::HTTP_UNAUTHORIZED;
            }

            return new JsonResponse(null, $code, []);
        }
    }
}