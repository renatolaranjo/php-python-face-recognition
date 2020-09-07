<?php

/**
 * Result file
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

/**
 * Result Interface
 *
 * @category Services
 * @package  Services\Recognize
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */
interface Result
{
    /**
     * Get Result
     *
     * @param array $output Output from script
     *
     * @return void
     */
    public function getResult($output);

    /**
     * Next
     *
     * @param Result $result Result
     *
     * @return void
     */
    public function next(Result $result);
}
