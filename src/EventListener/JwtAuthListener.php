<?php

namespace App\EventListener;

use App\Service\JwtService;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtAuthListener
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api/protected')) {
            return; // Ignore les autres routes
        }

        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/^Bearer\s+(.+)$/', $authHeader, $matches)) {
            $event->setResponse(new JsonResponse(['error' => 'Unauthorized'], 401));
            return;
        }

        $token = $this->jwtService->validateToken($matches[1]);
        if (!$token) {
            $event->setResponse(new JsonResponse(['error' => 'Invalid token'], 401));
            return;
        }

        // Ajouter l'ID utilisateur dans la requête
        $request->attributes->set('user_id', $token->claims()->get('uid'));
    }
}