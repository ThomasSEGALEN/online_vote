<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Store document in public path.
     *
     * @param \App\Models\Document $document
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Document $document): BinaryFileResponse
    {
        return response()->download(public_path('documents') . '\\' . $document->path, $document->name);
    }
}
