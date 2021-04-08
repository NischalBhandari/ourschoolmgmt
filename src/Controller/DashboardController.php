<?php

namespace App\Controller;
use App\Entity\Student;
use App\Entity\Staff;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request)
    { 
    	return $this->render('dashboard/index.html.twig',[
            
        ]);
    }

        /**
     * @Route("/search/{param}", name="search")
     */
    public function getSearch(string $param)
    {   $student = $this->getDoctrine()
                    ->getRepository(Student::class)
                    ->findOneBy(['name'=>$param]);
          if(!$student){
            throw $this->createNotFoundException(
                "No one found with name  ".$param
            );
          }
    	return $this->render('dashboard/show.html.twig',[
            'student' =>$student
        ]);

    }
}
