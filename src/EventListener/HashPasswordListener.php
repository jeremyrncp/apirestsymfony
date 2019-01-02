<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace App\EventListener;

use App\Entity\BusinessCustomer;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class HashPasswordListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var BusinessCustomer $entity */
        $entity = $args->getObject();

        if (!$entity instanceof BusinessCustomer) {
            return;
        }

        $entity->setPassword(
            password_hash($entity->getPassword(), PASSWORD_BCRYPT)
        );
    }
}