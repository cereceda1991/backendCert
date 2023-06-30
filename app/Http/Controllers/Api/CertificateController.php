<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::all();
        return response()->json([
            "result" => $certificates
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $certificate = Certificate::findOrFail($id);
        return response()->json(['result' => $certificate], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $certificate = new Certificate();

        $certificate->subjectName = $request->subjectName;
        $certificate->certificateType = $request->certificateType;
        $certificate->issuingAuthority = $request->issuingAuthority;
        $certificate->expiryDate = $request->expiryDate;
        $certificate->publicKey = $request->publicKey;
        $certificate->president = $request->president;
        $certificate->academicDirector = $request->academicDirector;
        $certificate->certificateContent = $request->certificateContent;

        $certificate->save();

        return response()->json(['result' => $certificate], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $certificate->subjectName = $request->subjectName;
        $certificate->certificateType = $request->certificateType;
        $certificate->issuingAuthority = $request->issuingAuthority;
        $certificate->expiryDate = $request->expiryDate;
        $certificate->publicKey = $request->publicKey;
        $certificate->president = $request->president;
        $certificate->academicDirector = $request->academicDirector;
        $certificate->certificateContent = $request->certificateContent;

        $certificate->save();

        return response()->json(['result' => $certificate], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        Certificate::destroy($id);
        return response()->json(['message' => "Deleted"], Response::HTTP_OK);
    }
}
