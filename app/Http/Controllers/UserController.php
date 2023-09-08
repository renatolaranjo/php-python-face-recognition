<?php

/**
 * Recognize file
 *
 * PHP Version 8.2
 *
 * @category Controller
 * @package  Controller
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/renatolaranjo/php-face-recognition/app/Services
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Services\Recognize\Recognizer;
use App\Services\Train\Trainer;

/**
 * UserController Class
 *
 * @category Controller
 * @package  Controller
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-face-recognition/app/Services
 */
class UserController extends Controller
{
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Recognize Face
     *
     * @var Recognizer
     */
    private $recognizer;

    /**
     * Train Dataset
     *
     * @var Trainer
     */
    private $trainer;

    /**
     * Constructor
     *
     * @param Recognizer $recognizer Recognize Face
     * @param Trainer $trainer Train Dataset
     * @return void
     */
    public function __construct(Recognizer $recognizer, Trainer $trainer)
    {
        $this->recognizer = $recognizer;
        $this->trainer = $trainer;
    }

    /**
     * List users
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return User::get(['id', 'name', 'email', 'country']);
    }

    /**
     * Register user and train dataset
     *
     * @param Request $request Request with data
     * @return array
     */
    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'email|required|unique:users,email',
                'country' => 'required'
            ]);
            return $this->trainer->execute($request);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Recognize face
     *
     * @param Request $request Request form
     * @return array
     */
    public function recog(Request $request)
    {
        return $this->recognizer->execute($request->img);
    }

    /**
     * Show face in dataset
     *
     * @param integer $index User ID
     * @return array
     */
    public function face($index)
    {
        $path = 'faces' . self::DS . $index . '.png';
        $contents = Storage::get($path);
        return base64_encode($contents);
    }
}
