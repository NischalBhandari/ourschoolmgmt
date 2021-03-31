<?php

namespace App\Controller;
use App\Entity\Student;
use App\Entity\User;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StudentController extends AbstractController
{

	private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {


        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    /**
     * @Route("/newstudent", name="newstudent")
     */
    public function registerNew(Request $request)
    {
    	$student = new Student();
    	$user = new User();
    	$form = $this->createForm(StudentType::class,$student);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		            // Encode the new users password
           $user->setPassword($this->passwordEncoder->encodePassword($user, $student->getPassword()));
           $user->setEmail($student->getEmail());
           $user->setName($student->getName());


            // Set their role
            $user->setRoles(['ROLE_USER']);

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($student);
    		$em->flush();
    		 $um = $this->getDoctrine()->getManager();
    		 $um->persist($user);
    		 $um->flush();
    		return $this->redirectToRoute('list');
       	}

       	return $this->render('student/register.html.twig',[
       		'form' => $form->createView(),
       	]);
    }


}
