<?php

/**
 * Recognize file
 *
 * PHP Version 8.2
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/renatolaranjo/php-face-recognition/app/Services
 */

namespace App\Services\Recognize;

use Illuminate\Support\Facades\Storage;
use CV\Face\LBPHFaceRecognizer, CV\CascadeClassifier, CV\Scalar, CV\Point;
use stdClass;

use function CV\{imread, cvtColor, equalizeHist};
use const CV\{COLOR_BGR2GRAY};

/**
 * Recognize Class
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-face-recognition/app/Services
 */
class Recognizer
{
    /**
     * DIRECTORY SEPARATOR
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Execute
     *
     * @param array $img64encode Image Encoded
     *
     * @return void
     */
    public function execute($img64encode)
    {
        $output = $this->identify($img64encode);
        $found = new Found();
        $notFound = new NotFound();
        $noFace = new NoFace();
        $found->next($notFound);
        $notFound->next($noFace);
        return $found->getResult($output);
    }

    /**
     * Identify faces in a picture
     *
     * @param string $img64encode Image encoded
     * @return object
     */
    private function identify($img64encode)
    {
        $filename = time() . '.png';
        $content = base64_decode(explode(',', $img64encode)[1]);
        Storage::put('recog' . self::DS . $filename, $content);
        $src = imread(storage_path() . self::DS . 'app' . self::DS . 'recog' . self::DS . $filename);
        $gray = cvtColor($src, COLOR_BGR2GRAY);
        $faceClassifier = new CascadeClassifier();
        $faceClassifier->load(base_path('resources/models/lbpcascade_frontalface.xml'));
        $faceClassifier->detectMultiScale($gray, $faces);
        $faceRecognizer = LBPHFaceRecognizer::create();
        $faceRecognizer->read(storage_path() . '/app/results/lbph_model.xml');
        
        equalizeHist($gray, $gray);
        foreach ($faces as $face) {
            $faceImage = $gray->getImageROI($face);
            
            //predict
            $faceLabel = $faceRecognizer->predict($faceImage, $faceConfidence);
            // echo "{$faceLabel}, {$faceConfidence}\n";

            // $scalar = new \CV\Scalar(0, 0, 255);
            // \CV\rectangleByRect($src, $face, $scalar, 2);

            // // $text = $labels[$faceLabel];
            // \CV\rectangle($src, $face->x, $face->y, $face->x + ($faceLabel == 1 ? 50 : 130), $face->y - 30, new Scalar(255, 255, 255), -2);
            // \CV\putText($src, "$text", new Point($face->x, $face->y - 2), 0, 1.5, new Scalar(), 2);
        }
        Storage::delete('recon' . self::DS . $filename);
        $output = new stdClass;
        $output->confidence = $faceConfidence;
        $output->id = $faceLabel;
        return $output;
    }
}
