<?php

/**
 * NotFound file
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

use App\Services\Recognize\Result;

/**
 * NotFound Class
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */
class NotFound implements Result
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
        if ($output->confidence > 100 && $output->id != 'no_face') {
            return [
                'status' => 'unknown'
            ];
        }
        return $this->next->getResult($output);
    }
}
