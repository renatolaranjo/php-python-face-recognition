<?php

/**
 * Found file
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

use App\Services\Recognize\Result;
use App\Models\User;

/**
 * Found Class
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-face-recognition/app/Services
 */
class Found implements Result
{
    /**
     * Next
     *
     * @var Result
     */
    private $next;

    /**
     * Next
     *
     * @param Result $next Result
     *
     * @return @void
     */
    public function next(Result $next)
    {
        $this->next = $next;
    }

    /**
     * Get Result
     *
     * @param object $output Output from script
     *
     * @return array
     */
    public function getResult($output)
    {
        if ($output->confidence < 100 && $output->id) {
            $user = User::find($output->id);
            $confidenceResult = 100 - $output->confidence;
            $status = ($confidenceResult) < 30 ? 'unknown' : 'success';
            return [
                'status' => $status,
                'confidence' => $confidenceResult,
                'user' => $user
            ];
        }
        return $this->next->getResult($output);
    }
}
