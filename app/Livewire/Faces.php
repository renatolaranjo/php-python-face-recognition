<?php

namespace App\Livewire;

use App\Models\FaceRecognition;
use Livewire\Component;
use CV\Face\LBPHFaceRecognizer, CV\CascadeClassifier, CV\Scalar, CV\Point;

class Faces extends Component
{
    public FaceRecognition $faceRecognition;
    public function render()
    {
        return view('faces.index', [
            'faces' => FaceRecognition::all()
        ]);
    }
}
