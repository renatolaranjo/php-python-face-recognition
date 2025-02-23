<?php

namespace App\Livewire;

use Livewire\Component;
use CV\Face\LBPHFaceRecognizer, CV\CascadeClassifier, CV\Scalar, CV\Point;

class Faces extends Component
{
    public function render()
    {
        return view('grid');
    }
}
