<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
   const DELETE = 'delete';
   const VIEW = 'view';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool|int
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$subject instanceof User) {
            return self::ACCESS_DENIED;
        }

        /**
         * @var User $subject
         */
        if ($subject->getBusinessCustomer()->getId() !== $token->getUser()->getId()) {
            return self::ACCESS_DENIED;
        }

        return self::ACCESS_GRANTED;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    public function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::DELETE, self::VIEW))) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }
}