<?php

namespace App\Livewire;

use const CV\COLOR_BGR2GRAY;

use App\Models\FaceRecognition;
use CV\CascadeClassifier;
use CV\Scalar;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

use function CV\cvtColor;
use function CV\imread;
use function CV\imwrite;
use function CV\rectangleByRect;

class Trainer extends Component
{
    use WithFileUploads;

    #[Validate('image|max:1024')]
    public ?TemporaryUploadedFile $file = null;

    #[Validate('required')]
    public string $name = '';

    public string $fileFace = '';

    public function updatedFile(): void
    {

        $src = imread($this->file->getRealPath());
        if ($src === null) {
            throw new \Exception('Erro ao carregar a imagem');
        }

        $gray = cvtColor($src, COLOR_BGR2GRAY);

        $faceClassifier = new CascadeClassifier;
        $faceClassifier->load(resource_path('models/lbpcascade_frontalface.xml'));

        $faces = [];

        $faceClassifier->detectMultiScale($gray, $faces);

        if (!empty($faces)) {
            $scalar = new Scalar(0, 0, 255);
            foreach ($faces as $face) {
                rectangleByRect($src, $face, $scalar, 3);
            }
        }
        $this->fileFace = time();
        imwrite(storage_path('app/public/' . $this->fileFace . '.jpg'), $src);

    }

    public function save(): void
    {
        $this->validate();

        $faceRecognition = new FaceRecognition;
        $faceRecognition->name = $this->name;
        $faceRecognition->face_encoding = 'nothinh';
        $faceRecognition->image_path = $this->fileFace . '.jpg';
        $faceRecognition->save();

        $this->reset(['file', 'name']);
    }

    public function render()
    {
        return view('livewire.trainer');
    }
}
