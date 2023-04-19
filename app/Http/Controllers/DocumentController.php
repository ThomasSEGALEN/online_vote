<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DocumentController extends Controller
{
    public function download(Document $document)
    {
        return response()->download(public_path('documents') . '\\' . $document->path, $document->name);
    }
}
