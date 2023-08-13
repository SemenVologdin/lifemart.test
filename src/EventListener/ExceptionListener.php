<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ExceptionListener
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelException(ExceptionEvent $obEvent): void
    {
        $obException = $obEvent->getThrowable();
        if( $obException instanceof NotFoundHttpException){
            if ('404' === $obEvent->getRequest()->get('_route')) {
                return;
            }

            $strUrl = $this->router->generate('404');
            $obEvent->setResponse(new RedirectResponse($strUrl));
        }
    }
}