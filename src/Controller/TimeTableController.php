<?php

namespace App\Controller;
use App\Entity\TimeTable;
use App\Form\TimeTableType;
use App\Service\ConflictChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\createNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class TimeTableController extends AbstractController
{
    /**
     * @Route("/newtime", name="newschedule")
     */
    public function registerNew(Request $request, ConflictChecker $conflictChecker)
    {
    	$schedule = new TimeTable();
    	$form = $this->createForm(TimeTableType::class,$schedule);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$conflictChecker->check($form);
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($schedule);
    		$em->flush();
    		return $this->redirectToRoute('liststudent');
       	}

       	return $this->render('time_table/index.html.twig',[
       		'form' => $form->createView(),
       	]);
    }
/**
* @Route("/listclass", name="listclass")	
*/
    public function listClass(PaginatorInterface $paginator,Request $request) : Response
    {
    	$pagination = $paginator->paginate(
        $schedule = $this->getDoctrine()
                    ->getRepository(TimeTable::class)
                    ->findAll(),$request->query->getInt('page', 1),5                
                );
    	return $this->render('time_table/list.html.twig',[
            'pagination' =>$pagination,
        ]);
    }
}
