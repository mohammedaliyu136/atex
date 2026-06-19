@extends('layouts.admin')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Legal Documents</h1>
        <p class="text-slate-500 text-sm">Manage terms, policies, and their versions</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.legal-documents.create') }}" class="px-5 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-sm flex items-center hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Add Document
        </a>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50">
    <div class="min-h-[450px]">
        <table class="w-full text-left border-separate border-spacing-0">
            <thead>
                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/30">
                    <th class="px-8 py-5 border-b border-slate-50">Type</th>
                    <th class="px-8 py-5 border-b border-slate-50">Title</th>
                    <th class="px-8 py-5 border-b border-slate-50 text-center">Active Version</th>
                    <th class="px-8 py-5 border-b border-slate-50">Effective Date</th>
                    <th class="px-8 py-5 border-b border-slate-50 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($documents as $doc)
                <tr class="group hover:bg-slate-50/50 transition-all">
                    <td class="px-8 py-5 text-sm font-bold text-slate-800 uppercase tracking-wide">{{ $doc->document_type }}</td>
                    <td class="px-8 py-5 text-sm font-semibold text-slate-600">{{ $doc->title }}</td>
                    <td class="px-8 py-5 text-center">
                        @if($doc->activeVersion)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600">
                                v{{ $doc->activeVersion->version }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-400">
                                None
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-sm font-medium text-slate-500">
                        {{ $doc->activeVersion ? $doc->activeVersion->effective_date->format('j/n/Y') : '-' }}
                    </td>
                    <td class="px-8 py-5 text-right" x-data="{ open: false }">
                        <div class="relative inline-block text-left">
                            <button @click="open = !open" @click.away="open = false" class="p-2 hover:bg-white hover:shadow-sm rounded-xl transition-all text-slate-400 hover:text-slate-600">
                                <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 py-2" style="display: none;">
                                
                                <a href="{{ route('admin.legal-documents.show', $doc) }}" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4 mr-3 text-slate-400"></i>
                                    Manage
                                </a>
                                <a href="{{ route('admin.legal-documents.edit', $doc) }}" class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4 mr-3 text-slate-400"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.legal-documents.destroy', $doc) }}" method="POST" class="contents" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button class="w-full flex items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-3 text-red-400"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-sm text-slate-500">No legal documents found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
