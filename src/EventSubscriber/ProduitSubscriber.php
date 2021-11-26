<?php

namespace App\EventSubscriber;

use App\Entity\Attachment;
use App\Entity\Produit;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class ProduitSubscriber implements EventSubscriberInterface
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser'],
        ];
    }

    public function setUser(BeforeEntityPersistedEvent $event)
    {
        $entity= $event->getEntityInstance();
        if($entity instanceof Produit){
            $entity->setUser($this->security->getUser());
        }
    }

}
