<?php

namespace App\Controller;
use App\Service\PhotoUploader;
use App\Entity\Student;
use App\Entity\User;
use App\Form\StudentType;
use App\Form\StudentEditType;
use App\Form\SearchingType;
use App\Entity\Staff;
use Gedmo\Sluggable\Util\Urlizer;
use Knp\Component\Pager\PaginatorInterface;
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
    public function registerNew(Request $request, PhotoUploader $photoUploader)
    {
    	$student = new Student();
    	$user = new User();
        $staff = $this->getDoctrine()
                    ->getRepository(Staff::class)
                    ->findAll();
    	$form = $this->createForm(StudentType::class,$student);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
            //do this if an image is uploaded
            $photoFile=$form->get('studentphoto')->getData();
            if($photoFile){
                $photoFileName=$photoUploader->upload($photoFile);
                $student->setPhotoFilename($photoFileName);
            }
        
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
    		return $this->redirectToRoute('liststudent');
       	}

       	return $this->render('student/register.html.twig',[
       		'form' => $form->createView(),
       	]);
    }

    /**
    * @Route("/student/{id}",name="getstudent")
    */
    public function getStudent(int $id): Response
    {
        //method to get a single student using an id.

        $student = $this->getDoctrine()
                    ->getRepository(Student::class)
                    ->find($id);

        // this creates an exception for the ErrorController to handle
        if(!$student){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }

        return $this->render('student/show.html.twig',[
            'student' =>$student
        ]);
    }

        /**
    * @Route("/liststudent",name="liststudent")
    */
    public function listStudents(Request $request,PaginatorInterface $paginator)
    {
        //method to list  students 
        $student = new Student();
        $form = $this->createForm(SearchingType::class,$student);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()){
             //normal query using doctrin
 //            $student = $this->getDoctrine()
  //                  ->getRepository(Student::class)
   //                 ->findBy(['name'=>$student->getName()],
     //                        ['phone' => 'ASC']);

        //complex query using mysql custom query 

//        $student = $this->getDoctrine() -> getRepository(Student::class)
  //                  ->findAllWithName($student->getName());

            //not using DQL language
            
            $repository = $this->getDoctrine() -> getRepository(Student::class);
            
            $query = $repository->createQueryBuilder('p')
            ->where('p.name LIKE :myname')
            ->orWhere('p.parent LIKE :myname')
            ->orWhere('p.phone LIKE :myname')
             ->setParameter('myname', '%'.$student->getName().'%')
            ->orderBy('p.phone', 'ASC')
            ->getQuery();
            $pagination = $paginator->paginate(
            $student = $query->getResult(),$request->query->getInt('page', 1), 5
            );

         return $this->render('student/list.html.twig',[
            
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);

        }
        $pagination = $paginator->paginate(
        $student = $this->getDoctrine()
                    ->getRepository(Student::class)
                    ->findAll(),$request->query->getInt('page', 1),5                
                );
        if(!$student){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }
        return $this->render('student/list.html.twig',[
            'form' =>$form->createView(),
            'pagination' =>$pagination,
        ]);
    }

    /**
    * @Route("/delstudent/{id}",name="delstudent")
    */
    public function deleteStudent(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em ->getRepository(Student::class)->find($id);
        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(array('email'=>$student->getEmail()));

        if(!$student){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }
        $em -> remove($student);
        $em ->remove($user);
        $em ->flush();
        return $this->redirectToRoute('app_login');
    }

    /**
    * @Route("/editstudent/{id}",name="editstudent")
    */
    public function editStudent(int $id,request $request,PhotoUploader $photoUploader): Response
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->getDoctrine()->getManager();
        $student = $em ->getRepository(Student::class)->find($id);
        if(!$student){
            throw $this->createNotFoundException(
                "This student is non existent for id ". $id
            );
        }
        $user = $um->getRepository(User::class)->findOneBy(array('email'=>$student->getEmail()));
        $form = $this->createForm(StudentEditType::class,$student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //do this if an image is uploaded
            $photoFile=$form->get('studentphoto')->getData();
            if($photoFile){
                $photoFileName=$photoUploader->upload($photoFile);
                $student->setPhotoFilename($photoFileName);
            }
        

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
            return $this->redirectToRoute('liststudent');
        }
        return $this->render('student/edit.html.twig',[
            'form' => $form->createView(),
            'user' => $user->getPassword(),
        ]);

    }

}
