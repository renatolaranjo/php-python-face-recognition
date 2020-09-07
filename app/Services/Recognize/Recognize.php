<?php

/**
 * Recognize file
 *
 * PHP Version 7.4
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */

namespace App\Services\Recognize;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Recognize Class
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */
class Recognize
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
        $output = $this->runScript($img64encode);
        $found = new Found();
        $notFound = new NotFound();
        $noFace = new NoFace();
        $found->next($notFound);
        $notFound->next($noFace);
        return $found->getResult($output);
    }

    /**
     * Run script python
     *
     * @param string $img64encode Image encoded
     * @return object
     */
    private function runScript($img64encode)
    {
        $filename = time() . '.png';
        $content = base64_decode(explode(',', $img64encode)[1]);
        Storage::put('recog/' . $filename, $content);
        $scriptPath = app_path('Console' . self::DS . 'Scripts');
        $storagePath = storage_path('app' . self::DS . 'recog');
        $process = new Process([
            env('PYTHON_PATH'),
            $scriptPath . self::DS . 'face_recog.py',
            $storagePath. self::DS. $filename,
            $scriptPath
        ], $scriptPath);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $output =  json_decode($process->getOutput());
        Storage::delete('recon/' . $filename);
        return $output;
    }
}
