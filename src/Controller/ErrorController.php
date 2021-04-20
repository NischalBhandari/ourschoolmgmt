<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ErrorController extends AbstractController{

public function __invoke(\Throwable $exception): Response
    {
        $page = $this->render('error/error_from_controller.html.twig',[
        	'exceptionMessage' => $exception -> getMessage(),
        	'exceptionCode'=> $exception ->getCode(),
        	'message'=>$exception->getStatusCode()

        ]);

       return $page;
    }

// public function show(){

// 	return $this->render('error/error_from_controller.html.twig');
// }
} 