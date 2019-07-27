<?php

declare(strict_types=1);

namespace App\Security\Authentication;

use App\View\Security\TokenView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var UserAuthenticationProviderInterface
     */
    private $authenticationProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(UserAuthenticationProviderInterface $authenticationProvider, SerializerInterface $serializer)
    {
        $this->authenticationProvider = $authenticationProvider;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $token = $this->authenticationProvider->createToken($token->getUser());
        $serialized = $this->serializer->serialize(TokenView::from($token), 'json');

        return new Response($serialized);
    }
}
