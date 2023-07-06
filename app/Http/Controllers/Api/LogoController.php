<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Cloudinary;

class LogoController extends Controller
{
    public function index()
    {
        $perPage = 50; // Número de logos por página
        $logos = Logo::paginate($perPage);
    
        $response = [
            'status' => 'success',
            'message' => 'Logos found!',
            'data' => [
                'logos' => $logos->items(),
                'currentPage' => $logos->currentPage(),
                'perPage' => $logos->perPage(),
                'totalPages' => $logos->lastPage(),
                'totalCount' => $logos->total(),
            ],
        ];
    
        return response()->json($response, Response::HTTP_OK);
    }
    

    public function show($id)
    {
        try {
            $logo = Logo::findOrFail($id);
            return response()->success($logo , 'Logo found!');

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Logo not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $uploadedFile = $request->file('image');
        try {
            $image = Cloudinary::upload($uploadedFile->getRealPath());
            $logo = new Logo;
            $logo->urlImg = $image->getSecurePath();
            $logo->publicId = $image->getPublicId();
            $logo->name = $request->name;
            $logo->status = true;
            $logo->save();

        return response()->success($logo, 'The logo has been added successfully!')->header('Content-Type', 'application/json')->setStatusCode(Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request,$id)
    {
        $uploadedFile = $request->file('image');
         try { 
             $logo = logo::findOrFail($id); 
             if($uploadedFile){
                $destroy = Cloudinary::destroy($logo->publicId);
                $image = Cloudinary::upload($uploadedFile->getRealPath());
                $logo->urlImg = $image->getSecurePath();
                $logo->publicId = $image->getPublicId();      
            }            
            $logo->fill($request->only(['name', 'status']));
            $logo->save();
            return response()->success($logo, 'Data updated!');
         } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        } 
    }      
    
    // public function destroy($id)
    // {
    //     try {
    //         $logo = Logo::findOrFail($id);
    //         $destroy = Cloudinary::destroy($logo->publicId);
    //         $logo->delete();

    //         return response()->json(['message' => "Logo deleted"], Response::HTTP_OK);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => 'Logo not found'], Response::HTTP_NOT_FOUND);
    //     }
    // }
}
