<?php

declare(strict_types=1);

namespace App\Security\Guard;

use App\Security\Authentication\UserAuthenticationProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

final class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private const AUTHENTICATION_HEADER_NAME = 'authorization';

    /**
     * @var AuthenticationProviderInterface
     */
    private $authenticationProvider;

    public function __construct(UserAuthenticationProviderInterface $authenticationProvider)
    {
        $this->authenticationProvider = $authenticationProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'message' => 'Authentication required',
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return $request->headers->has(self::AUTHENTICATION_HEADER_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $rawToken = $request->headers->get(self::AUTHENTICATION_HEADER_NAME);
        $parts = explode(' ', $rawToken);
        if (2 != count($parts) || 'Bearer' !== $parts[0]) {
            throw new \UnexpectedValueException('Authentication token have invalid format. Supports only Bearer tokens');
        }

        return ['token' => $parts[1]];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (!$apiToken) {
            return null;
        }

        return $this->authenticationProvider->findUserByToken($apiToken);
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => 'Bad credentials',
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
