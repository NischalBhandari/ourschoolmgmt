<?php
namespace App\Service;
use Symfony\Component\Form\Form;
use App\Entity\TimeTable;
use Doctrine\ORM\EntityManagerInterface;

class ConflictChecker {
	private $prevStartTime;
	private $prevEndTime;
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager){
		$this->entityManager = $entityManager;
	}
	public function check(Form $form){
    		$newStartTime = $form->get('starttime')->getData()->format('U');
    		$newEndTime = $form->get('endtime')->getData()->format('U');
    		$newClass = $form->get('class')->getData();
    		//get the data from schedule for the same class as the one given by form
		    $prevSchedule = $this->entityManager
    					->getRepository(TimeTable::class)
    					->findBy([
    						'class'=>$newClass
    					]);

    		// loop throught the object to find all the timetable for the class
    		foreach($prevSchedule as $key=>$oldSchedule){
                $oldStartTime = $oldSchedule->getStarttime()->format('U');
                $oldEndTime = $oldSchedule->getEndtime()->format('U');
    		//	print_r($value->getStarttime());
                if($newStartTime >=$oldStartTime && $newStartTime <= $oldEndTime){
                    print_r("The new start time". $newStartTime ."conflicts with old time".$oldStartTime . "- ". $oldEndTime);
                    print_r("<br>");
                }
                else{
                    print_r("The new Start Time is ok  as per old schedule" );
                }
    		}

    		// The fromat('U') returns a string while getTimeStamp() returns an object

    		exit();
    		
	}
}