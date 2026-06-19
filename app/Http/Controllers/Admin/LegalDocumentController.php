<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalDocument;
use App\Models\LegalDocumentVersion;
use App\Http\Requests\StoreLegalDocumentRequest;
use App\Http\Requests\UpdateLegalDocumentRequest;
use App\Http\Requests\StoreLegalDocumentVersionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LegalDocumentController extends Controller
{
    public function index()
    {
        $documents = LegalDocument::with('activeVersion')->latest()->get();
        return view('admin.legal_documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.legal_documents.create');
    }

    public function store(StoreLegalDocumentRequest $request)
    {
        DB::transaction(function () use ($request) {
            $document = LegalDocument::create($request->only(['document_type', 'title', 'description']));
            
            $document->versions()->create([
                'version' => $request->version,
                'content' => $request->content,
                'effective_date' => $request->effective_date,
                'is_active' => $request->boolean('is_active'),
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('admin.legal-documents.index')->with('success', 'Document created successfully.');
    }

    public function show(LegalDocument $legalDocument)
    {
        $legalDocument->load(['versions.creator', 'versions' => function($q) {
            $q->orderByDesc('effective_date')->orderByDesc('id');
        }]);
        return view('admin.legal_documents.show', compact('legalDocument'));
    }

    public function edit(LegalDocument $legalDocument)
    {
        return view('admin.legal_documents.edit', compact('legalDocument'));
    }

    public function update(UpdateLegalDocumentRequest $request, LegalDocument $legalDocument)
    {
        $legalDocument->update($request->validated());
        return redirect()->route('admin.legal-documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(LegalDocument $legalDocument)
    {
        // Prevent deletion if any version is accepted by users
        $hasAcceptances = $legalDocument->versions()->whereHas('acceptances')->exists();
        if ($hasAcceptances) {
            return back()->with('error', 'Cannot delete document because one or more of its versions have been accepted by users.');
        }

        $legalDocument->delete();
        return redirect()->route('admin.legal-documents.index')->with('success', 'Document deleted successfully.');
    }

    public function storeVersion(StoreLegalDocumentVersionRequest $request, LegalDocument $legalDocument)
    {
        DB::transaction(function () use ($request, $legalDocument) {
            $isActive = $request->boolean('is_active');

            if ($isActive) {
                // Deactivate other versions
                $legalDocument->versions()->update(['is_active' => false]);
            }

            $legalDocument->versions()->create([
                'version' => $request->version,
                'content' => $request->content,
                'effective_date' => $request->effective_date,
                'is_active' => $isActive,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'New version added successfully.');
    }

    public function activateVersion(LegalDocument $legalDocument, LegalDocumentVersion $version)
    {
        if ($version->legal_document_id !== $legalDocument->id) {
            abort(404);
        }

        DB::transaction(function () use ($legalDocument, $version) {
            $legalDocument->versions()->update(['is_active' => false]);
            $version->update(['is_active' => true]);
        });

        return back()->with('success', 'Version activated successfully.');
    }

    public function destroyVersion(LegalDocument $legalDocument, LegalDocumentVersion $version)
    {
        if ($version->legal_document_id !== $legalDocument->id) {
            abort(404);
        }

        if ($version->acceptances()->exists()) {
            return back()->with('error', 'Cannot delete this version because it has been accepted by users.');
        }

        $version->delete();
        return back()->with('success', 'Version deleted successfully.');
    }
}
