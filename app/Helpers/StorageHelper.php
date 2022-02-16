<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{

    private $fieldName;
    private $file;
    private $model;
    private $filename;
    private $path;

    public function __construct($fieldName,$path, $file, $model)
    {
        $this->fieldName = $fieldName;
        $this->file = $file;
        $this->model = $model;
        $this->path = $path;

    }

    private function checkFile()
    {
        $fieldName = $this->fieldName;
        if ($this->file) {
            $this->filename = md5(uniqid('', true)) . '.' . $this->file->getClientOriginalExtension();
        } elseif ($this->model && $this->model->$fieldName) {
            $this->filename = $this->model->$fieldName;
        } else {
            $this->filename = null;
        }
        return $this;
    }

    public function saveImage()
    {
        $this->checkFile();
        if ($this->file) {
            Storage::putFileAs('/public/'.$this->path, $this->file, $this->filename);
            $this->destroyImage();
        }
        return $this->filename;
    }

    public function destroyImage()
    {
        $fieldName = $this->fieldName;
        if ($this->model && $this->model->$fieldName && Storage::exists('/public/'.$this->path . $this->model->$fieldName)) {
            Storage::delete('/public/'.$this->path . $this->model->$fieldName);
        }
    }
}