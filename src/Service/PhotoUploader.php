<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhotoUploader{
	private $targetDirectory;
	private $slugger;

	public function __construct($targetDirectory, sluggerInterface $slugger){
		$this->targetDirectory = $targetDirectory;
		$this->slugger = $slugger;
	}

	public function upload(uploadedFile $file){
		$originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$safeFileName=$this->slugger->slug($originalFileName);
		$fileName=$safeFileName.'-'.uniqid().'.'.$file->guessExtension();

		try{

			$file->move($this->getTargetDirectory(),$fileName);
		} catch(Extension $e){


			//throw any exception if the file cannot be uploaded 
		}
		return $fileName;
	}

	public function getTargetDirectory(){

		return $this->targetDirectory;
	}
}