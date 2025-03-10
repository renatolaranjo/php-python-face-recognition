<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use CV\CascadeClassifier, CV\Scalar;
use function CV\{imread, imwrite, cvtColor, equalizeHist, rectangleByRect};
use const CV\{COLOR_BGR2GRAY};


class Trainer extends Component
{
    use WithFileUploads;

    #[Validate('required|image|max:1024')]
    public ?TemporaryUploadedFile $file = null;
    
    #[Validate('required')]
    public string $name;

    public function updatedFile(): void 
    {
        $src = imread($this->file->getRealPath());
        $gray = cvtColor($src, COLOR_BGR2GRAY);
        $faceClassifier = new CascadeClassifier();
        $faceClassifier->load('models/lbpcascades/lbpcascade_frontalface.xml');
        $faces = $faceClassifier->detectMultiScale($gray, 1.3, 5);
        if ($faces) {
            $scalar = new Scalar(0, 0, 255); //blue
        
            foreach ($faces as $face) {
                rectangleByRect($src, $face, $scalar, 3);
            }
        }  
        imwrite(storage_path('app/public/faces/' . $this->name . '.jpg'), $src);    
    }

    public function save(): void 
    {
        $this->validate();
        $this->reset(['file', 'name']);
    }

    public function render()
    {
        return view('livewire.trainer');
    }
}
