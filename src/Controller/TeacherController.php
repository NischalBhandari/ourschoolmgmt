<?php

namespace App\Controller;
use App\Entity\Teacher;
use App\Form\TeacherType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TeacherController extends AbstractController
{


	private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/teacher", name="teacher")
     */
    public function index(): Response
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }
    /**
     * @Route("/newteacher", name="newteacher")
     */
    public function registerNew(Request $request)
    {
    	$teacher = new Teacher();
    	$form = $this->createForm(TeacherType::class,$teacher);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		$teacher->setPassword($this->passwordEncoder->encodePassword($teacher, $teacher->getPassword()));

            // Set their role
            $teacher->setRoles(['ROLE_USER']);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($teacher);
            $em->flush();
    		return $this->redirectToRoute('list');
       	}

       	return $this->render('teacher/register.html.twig',[
       		'form' => $form->createView(),
       	]);
    }

        /**
    * @Route("/teacher/{id}",name="getteacher")
    */
    public function getTeacher(int $id): Response
    {
        //method to get a single student using an id.

        $teacher = $this->getDoctrine()
                    ->getRepository(Teacher::class)
                    ->find($id);
        if(!$teacher){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }
        print_r($teacher);
        exit();

        return $this->render('teacher/show.html.twig',[
            'teacher' =>$teacher
        ]);
    }


        /**
    * @Route("/listteacher",name="listteacher")
    */
    public function listTeacher(): Response
    {
        //method to list  Teachers

        $teacher = $this->getDoctrine()
                    ->getRepository(Teacher::class)
                    ->findAll();
        if(!$teacher){
            throw $this->createNotFoundException(
                "No Teacher found "
            );
        }
/*        print_r($teacher);
        exit();*/
        return $this->render('teacher/list.html.twig',[
            'teacher' =>$teacher
        ]);
    }

        /**
    * @Route("/delteacher/{id}",name="delteacher")
    */
    public function deleteTeacher(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $teacher = $em ->getRepository(Teacher::class)->find($id);
        if(!$teacher){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }
        $em -> remove($teacher);
        $em ->flush();
        return $this->redirectToRoute('listteacher');
    }

}
