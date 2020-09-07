<?php

/**
 * Recognize file
 *
 * PHP Version 7.4
 *
 * @category Services
 * @package  Services\Train
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */

namespace App\Services\Train;

use App\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Train Class
 *
 * @category Services
 * @package  Services\Train
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */
class Train
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
        $content = base64_decode(explode(',', $request->encode_img)[1]);
        Storage::put('faces/' . $filename, $content);
        $this->runScript();

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    /**
     * Run script python
     *
     * @return void
     */
    private function runScript()
    {
        $storagePath = storage_path('app' . self::DS . 'faces');
        $scriptPath = app_path('Console' . self::DS . 'Scripts');
        $pathScript = 'Console' . self::DS . 'Scripts' .
            self::DS . 'face_train.py';
        $scriptAppPath = app_path($pathScript);
        $process = new Process([
            env('PYTHON_PATH'),
            $scriptAppPath,
            $storagePath,
            $scriptPath
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
