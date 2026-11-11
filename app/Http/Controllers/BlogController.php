<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class BlogController extends Controller
{
    public function index () {
        return view("pages.blog", ["articles" => Content::where("type" ,"=", "blog")->get()]);
    }

    public function show (string $slug) {
        $article =  Content::whereSlug($slug)->firstOrFail();
        return view("pages.blog.details",["article" => $article]);
    }

// reach text editor
    public function ckeditor (Request $request) {
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        
        
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        
        $safeName = Str::slug($originalName);
        $fileName = time() . '_' . $safeName . '.' . $extension;
        
        $file->move(public_path('media'), $fileName);

        $url = asset('media/' . $fileName);

        return response()->json([
            'uploaded' => true,
            'fileName' => $fileName,
            'url' => $url
        ]);
    }

    return response()->json([
        'uploaded' => false, 
        'error' => [
            'message' => 'File not received or invalid format.'
        ]
    ], 400); 

    }
}
