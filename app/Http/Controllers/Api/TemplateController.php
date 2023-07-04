<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Cloudinary;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::all();
        return response()->json([
            "result" => $templates
        ], Response::HTTP_OK);
    }
    public function show($id)
    {
        $template = Template::findOrFail($id);
        return response()->json(['result' => $template], Response::HTTP_OK);
    }
    public function store(Request $request)
    {
        $uploadedFile = $request->file('image');
        
        $image = Cloudinary::upload($uploadedFile->getRealPath());
        $template = new Template;
        $template->urlImg = $image->getSecurePath();
        $template->name = $request->name;
        $template->save();

        return response()->json(['result' => $template], Response::HTTP_CREATED);
    }
    public function update(Request $request)
    {
        $template = Template::findOrFail($request->id);
        $template->urlImg = $request->urlImg;
        $template->name = $request->name;
        $template->save();

        return response()->json(['result' => $template], Response::HTTP_CREATED);
    }
}
