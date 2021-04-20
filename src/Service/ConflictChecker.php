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
            if($newStartTime>$newEndTime){
                print_r("You End Time is less than your Start Time");
                exit();
            }

    		// loop throught the object to find all the timetable for the class
    		foreach($prevSchedule as $key=>$oldSchedule){
                $oldStartTime = $oldSchedule->getStarttime()->format('U');
                $oldEndTime = $oldSchedule->getEndtime()->format('U');
    		//	print_r($value->getStarttime());
                if($newEndTime > $oldStartTime && $newEndTime <= $oldEndTime){
                    print_r("The new end time ". $this->mTime($newEndTime) ." conflicts with old time ".$this->mTime($oldStartTime) . " - ". $this->mTime($oldEndTime));
                    exit();
                    print_r("<br>");
                }
                else if($newStartTime < $oldEndTime && $newStartTime >= $oldStartTime ){
                    print_r("The new Start time ". $this->mTime($newStartTime). " conflicts with old time ". $this->mTime($oldStartTime) . " - " . $this->mTime($oldEndTime));
                    exit();
                }
                else if($newStartTime == $oldStartTime && $newEndTime == $oldEndTime){
                    print_r("The start and end times match exactly");
                    exit();
                }
                else if($newStartTime < $oldStartTime && $newEndTime > $oldEndTime){
                    print_r("The new Time frame ".$this->mTime($newStartTime) . ' - ' . $this->mTime($newEndTime).  " conflicts with old time frame ". $this->mTime($oldStartTime). ' - '. $this->mTime($oldEndTime));
                    exit();
                }
                else{
                    print_r("The new Start Time is ok  as per old schedule" );
                }
    		}

    		// The fromat('U') returns a string while getTimeStamp() returns an object

    		
	}

    public function mTime(string $string){
        return $string/3600;
    }
}