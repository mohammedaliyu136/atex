@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Create Legal Document</h1>
    <p class="text-slate-500 text-sm mt-1">Define a new type of legal document and its initial version.</p>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden max-w-4xl">
    <div class="p-8 sm:p-10">
        <form action="{{ route('admin.legal-documents.store') }}" method="POST">
            @csrf
            
            <h3 class="text-lg font-extrabold text-slate-800 mb-6">Document Details</h3>
            <div class="space-y-6">
                <div>
                    <label for="document_type" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Document Type (Unique Key) <span class="text-red-500">*</span></label>
                    <input type="text" name="document_type" id="document_type" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" placeholder="e.g. terms_of_service" required>
                    @error('document_type') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Display Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" placeholder="e.g. Terms of Service" required>
                    @error('title') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all"></textarea>
                    @error('description') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-100">
                <h3 class="text-lg font-extrabold text-slate-800 mb-6">Initial Version Details</h3>
                <p class="text-sm text-slate-500 mb-6">This first version will be automatically set as Active.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="version" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Version Number <span class="text-red-500">*</span></label>
                        <input type="text" name="version" id="version" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" placeholder="e.g. 1.0" value="1.0" required>
                        @error('version') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="effective_date" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Effective Date <span class="text-red-500">*</span></label>
                        <input type="date" name="effective_date" id="effective_date" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" value="{{ date('Y-m-d') }}" required>
                        @error('effective_date') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-start bg-emerald-50 p-4 rounded-2xl border border-emerald-100 mb-6">
                    <div class="flex h-5 items-center mt-0.5">
                        <input id="is_active" name="is_active" type="checkbox" value="1" checked class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 bg-white">
                    </div>
                    <div class="ml-3">
                        <label for="is_active" class="text-sm font-bold text-emerald-800 cursor-pointer">Make active immediately</label>
                        <p class="text-xs text-emerald-600 mt-1 font-medium">If checked, this version will become active and users will be prompted to accept it.</p>
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Document Content <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="10" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all"></textarea>
                    @error('content') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                <a href="{{ route('admin.legal-documents.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">Save Document</button>
            </div>
        </form>
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
        
    ClassicEditor
        .create( document.querySelector( '#content' ) )
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
