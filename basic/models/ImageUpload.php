<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\base\ExitException;

class ImageUpload extends Model
{
	public $image;

	public function rules()
	{
		return [
			[['image'], 'required'],
			[['image'], 'file', 'extensions' => 'jpg,png']
		];
	}

	private function getFolder(){
		return Yii::getAlias('@web') . 'uploads/';
	}

	private function generateFilename(){
		return strtolower(md5(uniqid($this->image->baseName)). '.' . $this->image->extension);
	}

	public function deleteCurrentImage($currentImage){
		if(is_file($this->getFolder() . $currentImage)){
			@unlink($this->getFolder() . $currentImage);
		}
	}

	public function saveImage()
	{
		$filename  = $this->generateFilename();

		$this->image->saveAs($this->getFolder() . $filename);
		
		return $filename;
	}

	public function uploadImage(UploadedFile $file, $currentImage){

		$this->image = $file;

		if($this->validate()){
			
			$this->deleteCurrentImage($currentImage);

			return $this->saveImage();
		}
	}
}