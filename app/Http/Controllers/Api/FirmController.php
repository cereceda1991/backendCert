<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Cloudinary;

class FirmController extends Controller
{
    public function index()
    {
        $perPage = 50; 
        $encryptedId = Auth::user()->getAuthIdentifier();
        $firms = Firm::Where('id_user',$encryptedId)
        ->paginate($perPage)
        ->get();
    
        $response = [
            'status' => 'success',
            'message' => 'Logos found!',
            'data' => [
                'firms' => $firms->items(),
                'currentPage' => $firms->currentPage(),
                'perPage' => $firms->perPage(),
                'totalPages' => $firms->lastPage(),
                'totalCount' => $firms->total(),
            ],
        ];
    
        return response()->json($response, Response::HTTP_OK);
    }
    

    public function show($id)
    {
        try {
            $encryptedId = Auth::user()->getAuthIdentifier();
            $firm = Firm::Where('id_user',$encryptedId)
            ->findOrFail($id)
            ->get();

            return response()->success($firm , 'Firm found!');

        } catch (\Throwable $th) {
            return response()->json(['error' => 'Firm not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $uploadedFile = $request->file('image');
        try {
            $encryptedId = Auth::user()->getAuthIdentifier();
            $image = Cloudinary::upload($uploadedFile->getRealPath());
            $firm = firm::create([
                'urlImg' => $image->getSecurePath(),
                'publicId' => $image->getPublicId(),
                'autority' => $request->autority,
                'name' => $request->name,
                'status' => true,
                'id_user' => $encryptedId
            ]);

        return response()->success($firm, 'The Firm has been added successfully!')->header('Content-Type', 'application/json')->setStatusCode(Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request,$id)
    {
        $uploadedFile = $request->file('image');
        $image = Cloudinary::upload($uploadedFile->getRealPath());
        try {
            $encryptedId = Auth::user()->getAuthIdentifier();
            $firm = Firm::Where('id_user',$encryptedId)
            ->findOrFail($id);
            if($uploadedFile){
                $destroy = Cloudinary::destroy($firm->publicId);
                $firm->update([
                    'urlImg' => $image->getSecurePath(),
                    'PublicId' => $image->getPublicId(),
                    'autority' => $request->autority,
                    'name' => $request->name,
                    'status' => $request->status,
                ]);
            }
            return response()->success($firm, 'Data updated!');
        } catch (\Throwable $th) {
            return response()->error($th->getMessage());
        } 
    }      
    
}