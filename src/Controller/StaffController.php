<?php

namespace App\Controller;
use App\Entity\Staff;
use App\Entity\User;
use App\Form\StaffType;
use App\Form\StaffEditType;
use App\Form\StaffSearchingType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\createNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StaffController extends AbstractController
{

	private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/staff", name="staff")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {  $staff = new Staff();
        $form = $this->createForm(StaffSearchingType::class,$staff);
        $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
            
            $repository = $this->getDoctrine() -> getRepository(Staff::class);
            
            $query = $repository->createQueryBuilder('p')
            ->where('p.name LIKE :myname')
            ->orWhere('p.address LIKE :myname')
            ->orWhere('p.phone LIKE :myname')
             ->setParameter('myname', '%'.$staff->getName().'%')
            ->orderBy('p.phone', 'ASC')
            ->getQuery();
            $pagination = $paginator->paginate(
            $staff = $query->getResult(),$request->query->getInt('page', 1), 5
            );

         return $this->render('staff/index.html.twig',[
            
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);

        }

     $pagination = $paginator->paginate(
    	$staff = $this->getDoctrine()
                    ->getRepository(Staff::class)
                    ->findAll(),$request->query->getInt('page', 1),5);
        if(!$staff){
            throw $this->createNotFoundException(
                "No Staff found "
            );
        }

        return $this->render('staff/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/newstaff", name="newstaff")
     */
    public function registerNew(Request $request)
    {
    	$staff = new Staff();
    	$user = new User();
    	$form = $this->createForm(StaffType::class,$staff);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){
    		            // Encode the new users password
           $user->setPassword($this->passwordEncoder->encodePassword($user, $staff->getPassword()));
           $user->setEmail($staff->getEmail());
           $user->setName($staff->getName());


            // Set their role
            $user->setRoles(['ROLE_ADMIN']);

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($staff);
    		$em->flush();
    		 $um = $this->getDoctrine()->getManager();
    		 $um->persist($user);
    		 $um->flush();
    		return $this->redirectToRoute('staff');
       	}

       	return $this->render('staff/register.html.twig',[
       		'form' => $form->createView(),
       	]);
    }

     /**
    * @Route("/delstaff/{id}",name="delstaff")
    */
    public function deleteStaff(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $staff = $em ->getRepository(Staff::class)->find($id);
        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(array('email'=>$staff->getEmail()));
        if(!$staff OR !$user){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }
        $em -> remove($staff);
        $em ->remove($user);
        $em ->flush();
        return $this->redirectToRoute('staff');
    }

     /**
    * @Route("/editstaff/{id}",name="editstaff")
    */
    public function editStaff(int $id,request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->getDoctrine()->getManager();
        $staff = $em ->getRepository(Staff::class)->find($id);

        $user = $um->getRepository(User::class)->findOneBy(array('email'=>$staff->getEmail()));
        $form = $this->createForm(StaffEditType::class,$staff);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                        // Encode the new users password
           $user->setPassword($this->passwordEncoder->encodePassword($user, $staff->getPassword()));
           $user->setEmail($staff->getEmail());
           $user->setName($staff->getName());


            // Set their role
            $user->setRoles(['ROLE_ADMIN']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($staff);
            $em->flush();
             $um = $this->getDoctrine()->getManager();
             $um->persist($user);
             $um->flush();
            return $this->redirectToRoute('liststaff');
        }
        return $this->render('staff/edit.html.twig',[
            'form' => $form->createView(),

        ]);

    }

        /**
    * @Route("/getstaff/{id}",name="getstaff")
    */
    public function getStudent(int $id): Response
    {
        //method to get a single student using an id.

        $staff = $this->getDoctrine()
                    ->getRepository(Staff::class)
                    ->find($id);

        if(!$staff){
            throw $this->createNotFoundException(
                "No product found for id ".$id
            );
        }

        return $this->render('staff/show.html.twig',[
            'staff' =>$staff
        ]);
    }



}
