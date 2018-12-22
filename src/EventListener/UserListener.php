<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class UserListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var User $entity */
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $entityManager = $args->getObjectManager();

        $entity->setDateCreate(new \DateTime());
    }
}
