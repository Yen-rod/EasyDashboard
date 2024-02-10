<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class Listener
{
    #[AsEventListener(event: KernelEvents::REQUEST,priority: 5000)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $lang = $request->query->get('lang');

        if(!empty($lang)){
            $request->setLocale($lang);
        }


    }
}
