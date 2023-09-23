<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MyVoter extends Voter implements CacheableVoterInterface
{
    public function __construct(private UserRepository $userRepository)
    {}

    protected function supports(string $attribute, $subject): bool
    {
        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        return $this->userRepository->hasUserPermission($user, $attribute);
    }
}