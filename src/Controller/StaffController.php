<?php

namespace App\Controller;
use App\Service\FileUploader;
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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;



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
        try{
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
        catch(\Exception $e){
           error_log($e->getMessage());

        }
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
    public function registerNew(Request $request, FileUploader $fileUploader)
    {
    	$staff = new Staff();
    	$user = new User();
    	$form = $this->createForm(StaffType::class,$staff);
    	$form->handleRequest($request);
    	if($form->isSubmitted() && $form->isValid()){


            //This is the code to handle files uploaded by the user 
            $citizenshipFile= $form->get('citizenship')->getData();
            if($citizenshipFile){
                $citizenshipFileName = $fileUploader->upload($citizenshipFile);
                $staff->setBrochureFilename($citizenshipFileName);

            }

            try{
    		// Encode the new users password
               $user->setPassword($this->passwordEncoder->encodePassword($user, $staff->getPassword()));
               $user->setEmail($staff->getEmail());
               $user->setName($staff->getName());
                // Set their role
                $user->setRoles(['ROLE_ADMIN']);
            }
            catch(\Exception $e){
                echo $e->getMessage();
            }

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
    public function deleteStaff(int $id, LoggerInterface $logger): Response
    {
        $em = $this->getDoctrine()->getManager();
        $staff = $em ->getRepository(Staff::class)->find($id);
        if(!$staff){
            $logger->error("Cannot find the staff with id ");
            throw $this->createNotFoundException(
                "No staff found for id ".$id
            );
        }

        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findOneBy(array('email'=>$staff->getEmail()));  

        if(!$user){
            $logger->error("Cannot find the user with id ");
            throw $this->createNotFoundException(
                "No user found for id ".$id
            );
        }
    try{
        $em -> remove($staff);
        $em ->remove($user);
        $em ->flush();
     }
    catch(\Exception $e)
    {
        $logger->error($e->getMessage());
    }
    return $this->redirectToRoute('staff');
    }

     /**
    * @Route("/editstaff/{id}",name="editstaff")
    */
    public function editStaff(int $id,request $request,FileUploader $fileUploader): Response
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->getDoctrine()->getManager();
        try{
            $staff = $em ->getRepository(Staff::class)->find($id);
            $user = $um->getRepository(User::class)->findOneBy(array('email'=>$staff->getEmail()));
        // StaffEditType::class is used to get Fully Qualified Class Name App\Form\StaffEditType
            $form = $this->createForm(StaffEditType::class,$staff,[
                'label'=>$staff->getBrochureFilename(),
            ]);
        }
        catch(exception $e){
            print_r($e);
        }
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


         // Encode the new users password
           $user->setPassword($this->passwordEncoder->encodePassword($user, $staff->getPassword()));
           $user->setEmail($staff->getEmail());
           $user->setName($staff->getName());

           $citizenshipFile=$form->get('citizenship')->getData();
           if($citizenshipFile){
            //if the new citizenship is uploaded then use this condition
                 $citizenshipFileName = $fileUploader->upload($citizenshipFile);
                 $staff->setBrochureFilename($citizenshipFileName);
            }

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
        return $this->render('staff/edit.html.twig',[
            'form' => $form->createView(),

        ]);

    }

        /**
    * @Route("/getstaff/{id}",name="getstaff")
    */
    public function getStaff(int $id): Response
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

    /**
    * @Route("/teststaff/{id}", name="teststaff")
    * @Entity("Staff", expr="repository.find(id)")
    */
    public function myStaff(Staff $teststaff)
    // this function uses annotation and getParam / Entity to get the entity row 
    //
    {
        return $this->render('staff/test.html.twig',[
            'staff' => $teststaff
        ]);

    }

}
