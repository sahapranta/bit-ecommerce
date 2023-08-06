<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:2048'
        ]);

        if ($file = $request->file('file')) {
            $path = storage_path('temp/uploads');
            $name = $file->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $slugged = Str::of($name)->slug('-')->lower()->__toString();
            $imageName =  $slugged . '_' . uniqid() . '.' . $file->extension();

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $file->move($path, $imageName);

            return response()->json(['message' => 'You have successfully upload image.', 'name' => $imageName]);
        }

        return response()->json(['message' => 'Please choose a file.'], 422);
    }
}
