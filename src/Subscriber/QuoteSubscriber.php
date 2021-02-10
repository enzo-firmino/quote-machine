<?php
namespace App\Subscriber;

use App\Entity\User;
use App\Event\QuoteCreated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QuoteSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            QuoteCreated::class => 'onQuoteCreated',
        ];
    }

    public function onQuoteCreated(QuoteCreated $event)
    {
        $user = $this->getUser();
        $experience = $user->getExperience() + 100;
        $entityManager = $this->getDoctrine()->getManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->update(User::class, 'u')
            ->set('u.experience', '?1')
            ->setParameter(1, $experience);
    }

}