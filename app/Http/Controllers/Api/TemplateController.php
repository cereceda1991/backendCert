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
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
        $template = Template::findOrFail($id);
        return response()->success($template, 'template found!');
    }

    public function store(Request $request)
    {
        $uploadedFile = $request->file('image');
        try {
            $image = Cloudinary::upload($uploadedFile->getRealPath());
            $template = new Template;
            $template->urlImg = $image->getSecurePath();
            $template->publicId = $image->getPublicId();
            $template->name = $request->name;
            $template->status = true;
            $template->save();
    
            return response()->success($template, 'Data saved!');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }

    }

    public function update(Request $request,$id)
    {
        $uploadedFile = $request->file('image');
         try { 
             $template = Template::findOrFail($id); 
             if($uploadedFile){
                $destroy = Cloudinary::destroy($template->publicId);
                $image = Cloudinary::upload($uploadedFile->getRealPath());
                $template->urlImg = $image->getSecurePath();
                $template->publicId = $image->getPublicId();      
            }            
            $template->fill($request->only(['name', 'status']));
            $template->save();
            return response()->success($template, 'Data updated!');
         } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        } 
    }
    
/*     public function destroy(Request $request)
    {
        try {
            $template = Template::findOneAndDelete($request->id); 
            return response()->json(['result' => $template], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        }
       
    } */
}
