@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.legal-documents.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-all shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Legal Document</h1>
            <p class="text-slate-500 text-sm mt-1">Update details for {{ $legalDocument->title }}.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl">
    <div class="col-span-2">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 sm:p-10">
                <form action="{{ route('admin.legal-documents.update', $legalDocument) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <div>
                            <label for="document_type" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Document Type <span class="text-red-500">*</span></label>
                            <input type="text" name="document_type" id="document_type" value="{{ old('document_type', $legalDocument->document_type) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" required>
                            @error('document_type') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Display Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $legalDocument->title) }}" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" required>
                            @error('title') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all">{{ old('description', $legalDocument->description) }}</textarea>
                            @error('description') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                        <a href="{{ route('admin.legal-documents.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition-all">Cancel</a>
                        <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">Update Document</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-red-50 rounded-[2.5rem] border border-red-100 shadow-xl shadow-red-200/20 overflow-hidden">
            <div class="p-8">
                <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                </div>
                <h3 class="text-xl font-extrabold text-red-900 tracking-tight">Danger Zone</h3>
                <p class="text-red-700 text-sm mt-3 leading-relaxed">
                    Once you delete a document, all its versions will be permanently removed. You can only delete documents that have <strong>no accepted versions</strong>.
                </p>
                <form action="{{ route('admin.legal-documents.destroy', $legalDocument) }}" method="POST" class="mt-8" onsubmit="return confirm('Are you sure you want to delete this document entirely?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-6 py-4 bg-white border border-red-200 text-red-600 rounded-2xl font-bold text-sm hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Delete Document
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#description' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
        border-bottom-left-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
    }
    .ck-toolbar {
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
        background: #f8fafc !important;
        border-color: #f1f5f9 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable {
        border-color: #f1f5f9 !important;
    }
</style>
@endpush
