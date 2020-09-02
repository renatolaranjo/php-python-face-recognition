<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class UserController extends Controller
{
    const DS = DIRECTORY_SEPARATOR;

    public function list()
    {
        return User::get(['id', 'name', 'email', 'country']);
    }

    public function register(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'email|required|unique:users,email',
                'country' => 'required'
            ]);

            $user = User::create($request->toArray());
            $filename = $user->id . '.png';
            $content = base64_decode(explode(',', $request->encode_img)[1]);
            Storage::put('faces/' . $filename, $content);
            $storagePath = storage_path('app' . self::DS . 'faces');
            $scriptPath = app_path('Console' . self::DS . 'Scripts');
            $pathScript = 'Console' . self::DS . 'Scripts' .
                self::DS . 'face_train.py';
            $process = new Process([
                env('PYTHON_PATH'),
                app_path($pathScript),
                $storagePath,
                $scriptPath
            ]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            return response()->json([
                'status' => 'success',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function recog(Request $request)
    {
        $filename = time() . '.png';
        $content = base64_decode(explode(',', $request->img)[1]);
        Storage::put('recon/' . $filename, $content);
        $pathScript = '..' . self::DS . 'app' . self::DS . 'Console' . self::DS . 'Scripts' .
            self::DS . 'face_recog.py';
        $process = new Process([env('PYTHON_PATH'), $pathScript, $filename]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $outputJson =  json_decode($process->getOutput());
        Storage::delete('recon/' . $filename);
        if ($outputJson->confidence < 100 && $outputJson->id) {
            $user = User::find($outputJson->id);
            return [
                'status' => 'success',
                'confidence' => $outputJson->confidence,
                'user' => $user
            ];
        } elseif ($outputJson->confidence > 100 && $outputJson->id != 'no_face') {
            return [
                'status' => 'unknown'
            ];
        } else {
            return [
                'status' => 'no_face'
            ];
        }
    }

    public function face($index)
    {
        $path = 'faces' . self::DS . $index . '.png';
        $contents = Storage::get($path);
        return base64_encode($contents);
    }
}
