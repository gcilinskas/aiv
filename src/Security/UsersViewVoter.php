<?php

namespace App\Security;

use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class UsersViewVoter
 */
class UsersViewVoter extends Voter
{
    const ATTRIBUTE = 'users_view_voter';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, [self::ATTRIBUTE]) && $subject instanceof User;
    }

    /**
     * @param string $attribute
     * @param mixed $argUser
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $argUser, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::ATTRIBUTE:
                return $this->shouldAllow($argUser, $user);
        }

        throw new LogicException(
            sprintf('%s voter does not protect against %s action.', UsersViewVoter::class, $attribute)
        );
    }

    /**
     * @param User $argUser
     * @param User $user
     * @return bool
     */
    private function shouldAllow(User $argUser, User $user): bool
    {
        return $argUser === $user;
    }
}
