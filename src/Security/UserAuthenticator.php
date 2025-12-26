<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserAuthenticator extends AbstractGuardAuthenticator
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'login';
    }

    public function getCredentials(Request $request)
    {
        $credentials = $request->request->all();
        return [
            'username' => $credentials['username'] ?? '',
            'password' => $credentials['password'] ?? '',
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $username = $credentials['username'];

        if (null === $username) {
            return null;
        }

        return $userProvider->loadUserByUsername($username);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, UserInterface $user, string $firewallName): ?Response
    {
        return new JsonResponse(['message' => 'Authentication successful']);
    }

    public function onAuthenticationFailure(Request $request, \Exception $exception): ?Response
    {
        return new JsonResponse(['message' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, \Exception $authException = null): ?Response
    {
        return new JsonResponse(['message' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
