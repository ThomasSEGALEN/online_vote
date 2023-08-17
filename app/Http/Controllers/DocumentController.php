<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Exception;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Store document in storage path.
     *
     * @param \App\Models\Document $document
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(Document $document): BinaryFileResponse|RedirectResponse
    {
        try {
            return response()->download(public_path('storage/documents') . '\\' . $document->path, $document->name);
        } catch (Exception $exception) {
            return back()->with('error', 'Document inexistant');
        }
    }
}
