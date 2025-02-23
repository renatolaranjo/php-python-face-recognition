<?php

namespace App\Livewire;

use Livewire\{Component, WithFileUploads};
use Livewire\Attributes\Validate;
use function CV\{imread};
use CV\Face\LBPHFaceRecognizer, CV\CascadeClassifier, CV\Scalar, CV\Point;


class Trainer extends Component
{
    
    use WithFileUploads;

    #[Validate('image|max:1024')]
    public $image = null;

    public function updatedImage()
    {
       $this->image->store('faces', 'public');
       $filePath = $this->image->store('images/faces', 'public');
       $faceClassifier = new CascadeClassifier('models/lbpcascades/lbpcascade_frontalface.xml');
    $faceClassifier->load('models/lbpcascades/lbpcascade_frontalface.xml');
       $faceRecognizer = LBPHFaceRecognizer::create();
       $fullPath = storage_path("app/public/{$filePath}");  
       $src = imread($fullPath);
       $gray = cvtColor($src, COLOR_BGR2GRAY);
       $faces = $faceClassifier->detectMultiScale($gray, $faces);
       equalizeHist($gray, $gray);
    }

    public function render()
    {
        return view('livewire.trainer');
    }
}
