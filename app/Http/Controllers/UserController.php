<?php
/**
 * Recognize file
 *
 * PHP Version 7.4
 *
 * @category Controller
 * @package  Controller
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     https://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

/**
 * UserController Class
 *
 * @category Controller
 * @package  Controller
 * @author   Renato Laranjo <renatolaranjo@gmail.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link     http://github.com/renatolaranjo/php-python-face-recognition/app/Services
 */
class UserController extends Controller
{
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Recognize Face
     *
     * @var Recgnize
     */
    private $recognize;

    /**
     * Train Dataset
     *
     * @var Train
     */
    private $train;

    // /**
    //  * Constructor
    //  *
    //  * @param Recognize $recognize Recognize Face
    //  * @param Train $train Train Dataset
    //  * @return void
    //  */
    // public function __construct(Recognize $recognize, Train $train)
    // {
    //     $this->recognize = $recognize;
    //     $this->train = $train;
    // }

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
            return $this->train->execute($request);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Recoginze face
     *
     * @param Request $request Request form
     * @return array
     */
    public function recog(Request $request)
    {
        return $this->recognize->execute($request->img);
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
