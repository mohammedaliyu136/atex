@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.legal-documents.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-all shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Manage Document Versions</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $legalDocument->title }}</p>
        </div>
    </div>
</div>

<div class="space-y-8">
    <div>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="p-8 border-b border-slate-50 flex items-center">
                <div class="w-12 h-12 bg-primary-100 rounded-2xl flex items-center justify-center mr-4">
                    <i data-lucide="plus-circle" class="w-6 h-6 text-primary-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Add New Version</h3>
                    <p class="text-slate-500 text-sm mt-1">Draft a new version of this legal document</p>
                </div>
            </div>
            
            <div class="p-8">
                <form action="{{ route('admin.legal-documents.versions.store', $legalDocument) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="version" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Version Number <span class="text-red-500">*</span></label>
                                <input type="text" name="version" id="version" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" placeholder="e.g. 1.0 or 2026-06" required>
                            </div>

                            <div>
                                <label for="effective_date" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Effective Date <span class="text-red-500">*</span></label>
                                <input type="date" name="effective_date" id="effective_date" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all" required>
                            </div>
                        </div>
                        
                        <div class="flex items-start bg-emerald-50 p-4 rounded-2xl border border-emerald-100 w-full md:w-1/2">
                            <div class="flex h-5 items-center mt-0.5">
                                <input id="is_active" name="is_active" type="checkbox" value="1" class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500 bg-white">
                            </div>
                            <div class="ml-3">
                                <label for="is_active" class="text-sm font-bold text-emerald-800 cursor-pointer">Make active immediately</label>
                                <p class="text-xs text-emerald-600 mt-1 font-medium">This deactivates the current version. Users will be prompted to accept this new version.</p>
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-bold text-slate-700 uppercase tracking-wide mb-2">Document Content <span class="text-red-500">*</span></label>
                            <textarea name="content" id="content" rows="10" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50 flex justify-end">
                        <button type="submit" class="px-8 py-4 bg-primary-600 text-white rounded-2xl font-bold text-sm hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center justify-center">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            Save Version
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="p-8 border-b border-slate-50 flex items-center">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mr-4">
                    <i data-lucide="history" class="w-6 h-6 text-slate-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Version History</h2>
                    <p class="text-slate-500 text-sm mt-1">Past and current versions of this document</p>
                </div>
            </div>

            <div class="overflow-x-auto pb-32 rounded-b-[2.5rem]">
                <table class="w-full text-left border-separate border-spacing-0 min-w-[800px]">
                    <thead>
                        <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/30">
                            <th class="px-8 py-5 border-b border-slate-50">Version</th>
                            <th class="px-8 py-5 border-b border-slate-50">Status</th>
                            <th class="px-8 py-5 border-b border-slate-50">Effective</th>
                            <th class="px-8 py-5 border-b border-slate-50">Hash (SHA256)</th>
                            <th class="px-8 py-5 border-b border-slate-50">Acceptances</th>
                            <th class="px-8 py-5 border-b border-slate-50 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($legalDocument->versions as $version)
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                <td class="px-8 py-5 text-sm font-bold text-slate-800 whitespace-nowrap">v{{ $version->version }}</td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if($version->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-sm font-medium text-slate-600 whitespace-nowrap">
                                    {{ $version->effective_date->format('j/n/Y') }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-[10px] font-mono text-slate-500" title="{{ $version->content_hash }}">
                                        {{ substr($version->content_hash, 0, 16) }}...
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center font-bold {{ $version->acceptances()->count() > 0 ? 'text-primary-600' : 'text-slate-400' }}">
                                        <i data-lucide="users" class="w-4 h-4 mr-2 opacity-50"></i>
                                        {{ $version->acceptances()->count() }}
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right whitespace-nowrap" x-data="{ open: false }">
                                    <div class="relative inline-block text-left z-10">
                                        <button @click="open = !open" @click.away="open = false" class="p-2 hover:bg-white hover:shadow-sm rounded-xl transition-all text-slate-400 hover:text-slate-600">
                                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 py-2" style="display: none;">
                                            
                                            @if(!$version->is_active)
                                                <form action="{{ route('admin.legal-documents.versions.activate', [$legalDocument, $version]) }}" method="POST" class="contents">
                                                    @csrf
                                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-emerald-600 hover:bg-emerald-50 transition-colors" onclick="return confirm('Activating this version will deactivate others and prompt users to accept it. Continue?');">
                                                        <i data-lucide="check-circle" class="w-4 h-4 mr-3 text-emerald-500"></i>
                                                        Set as Active
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button @click="$dispatch('preview-version', { content: `{{ addslashes(json_encode(['html' => $version->content])) }}` })" class="w-full flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                                                <i data-lucide="eye" class="w-4 h-4 mr-3 text-slate-400"></i>
                                                Preview Content
                                            </button>

                                            @if($version->acceptances()->count() === 0)
                                                <form action="{{ route('admin.legal-documents.versions.destroy', [$legalDocument, $version]) }}" method="POST" class="contents">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors" onclick="return confirm('Delete this version? This cannot be undone.');">
                                                        <i data-lucide="trash-2" class="w-4 h-4 mr-3 text-red-400"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <div class="px-4 py-2.5 text-xs text-slate-400 italic">
                                                    Cannot delete (has acceptances)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                        <i data-lucide="file-question" class="w-8 h-8 text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">No versions created yet.</p>
                                    <p class="text-sm text-slate-400 mt-1">Use the form to create the first version.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div x-data="{ 
        isOpen: false, 
        content: '',
        init() {
            window.addEventListener('preview-version', (e) => {
                let data = JSON.parse(e.detail.content);
                this.content = data.html;
                this.isOpen = true;
            });
        }
    }"
    x-show="isOpen"
    class="fixed inset-0 z-[100] overflow-y-auto"
    style="display: none;">
    
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click="isOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[85vh]">
            
            <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50 shrink-0">
                <h3 class="text-xl font-bold text-slate-800">Version Preview</h3>
                <button @click="isOpen = false" class="p-2 hover:bg-white rounded-xl transition-colors text-slate-400">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto prose prose-slate max-w-none flex-1" x-html="content">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#content' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
<style>
    .ck-editor__editable_inline {
        min-height: 400px;
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
