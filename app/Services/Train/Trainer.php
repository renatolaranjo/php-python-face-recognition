<?php

/**
 * Recognize file
 *
 * PHP Version 8.2
 *
 * @category Services
 * @package  Services\Train
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/renatolaranjo/php-face-recognition/app/Services
 */

namespace App\Services\Train;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use CV\Face\LBPHFaceRecognizer, CV\CascadeClassifier;
use function CV\{imread, cvtColor, equalizeHist};
use const CV\{COLOR_BGR2GRAY};

/**
 * Train Class
 *
 * @category Services
 * @package  Services\Train
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-face-recognition/app/Services
 */
class Trainer
{
    /**
     * DIRECTORY SEPARATOR
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Execute
     *
     * @param object $request Request
     *
     * @return void
     */
    public function execute($request)
    {

        $user = User::create($request->toArray());
        $filename = $user->id . '.png';
        $filePath = 'faces' . self::DS . $filename;
        $content = base64_decode(explode(',', $request->encode_img)[1]);
        Storage::put($filePath, $content);
        $faceClassifier = new CascadeClassifier();
        $faceClassifier->load(base_path('resources/models/lbpcascade_frontalface.xml'));
        $faceRecognizer = LBPHFaceRecognizer::create();
        $src = imread(storage_path() . self::DS . 'app' . self::DS . 'faces' . self::DS . $filename);
        $gray = cvtColor($src, COLOR_BGR2GRAY);
        $faces = null;
        $faceClassifier->detectMultiScale($gray, $faces);
        equalizeHist($gray, $gray);
        $faceImages = $faceLabels = [];
        foreach ($faces as $k => $face) {
            $faceImages[] = $gray->getImageROI($face); 
            $faceLabels[] = $user->id; 
        }
        $faceRecognizer->train($faceImages, $faceLabels);
        $faceRecognizer->write(storage_path() . '/app/results/lbph_model.xml');
        return response()->json([
            'status' => 'success',
        ], 201);
    }
}
