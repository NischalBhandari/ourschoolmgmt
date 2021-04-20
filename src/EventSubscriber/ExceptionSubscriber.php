<?php

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['logException', 0],
                ['notifyException', -10],
            ],
        ];
    }

    public function processException(ExceptionEvent $event)
    {
       	$exception = $event -> getThrowable();
		$message = sprintf(
			'My error says :%s with code :%s',

			$exception->getMessage(),
			$exception->getCode()
		);

		//customize the response object

		$response = new Response("no response");
		$response->setContent($message);

		if($exception instanceof HttpExceptionInterface){
			$response -> setStatusCode($exception->getStatusCode());
			$response ->headers->replace($exception->getHeaders());

		}
		else {
			$response -> setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$event->setResponse($response);
    }

    public function logException(ExceptionEvent $event)
    {
        // ...
    }

    public function notifyException(ExceptionEvent $event)
    {
        // ...
    }
}