<?php

namespace App\Security\Voter;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class JwtAuthenticatedVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'IS_AUTHENTICATED_JWT';
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        return $token instanceof JWTUserToken;
    }
}
