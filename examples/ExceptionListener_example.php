<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExceptionListener extends AbstractController
{

	public function onKernelException(ExceptionEvent $event){
		$exception = $event -> getThrowable();
		$message = sprintf(
			'My error says :%s with code :%s',
			$exception->getMessage(),
			$exception->getCode()
		);


		//customize the response object
		$response = $this->render('error/basic.html.twig',[
			'message' => $message,
			'code'=> $exception -> getStatusCode()
		]);
		//$response->setContent($message);
		//instanceof finds if an object is an instance of a class 
		if($exception instanceof HttpExceptionInterface){
			$response -> setStatusCode($exception->getStatusCode());
			$response ->headers->replace($exception->getHeaders());

		}
		else {
			$response -> setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		//	return $this->render('dashboard/show.html.twig');
		$event->setResponse($response);
	}	
}