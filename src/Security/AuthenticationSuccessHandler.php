<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        // Récupérer l'utilisateur authentifié
        $user = $token->getUser();

        // Générer le token JWT
        $jwtToken = $this->jwtManager->create($user);

        // Construire la réponse JSON personnalisée
        $data = [
            'token' => $jwtToken,
            'user' => [
                'username' => $user->getUsername(),
            ],
            'message' => 'Login successful',
        ];

        // Retourner la réponse JSON
        return new JsonResponse($data, 200);
    }
}
