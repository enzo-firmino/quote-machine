<?php

namespace App\Security\Voter;

use App\Entity\Quote;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class QuoteVoter extends Voter
{

    const EDIT = 'edit';
    const DELETE = 'delete';


    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Quote;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
                if ($user === $subject->getAuteur()) {
                    return true;
                } else {
                    return false;
                }
                break;
        }

        return false;
    }
}
