<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 10],
        ];
    }

    public function upload($id,$i)
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $i++;
                $name = 'court_'.$id.'-'.$i;
                $file->saveAs('img/courts/' . $name . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}