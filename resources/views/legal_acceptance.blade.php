@extends('layouts.landing')

@section('title', 'Action Required - Legal Document Updates')

@section('styles')
<style>
  .acceptance-container {
    min-height: calc(100vh - 70px);
    padding: 3rem 1rem;
    background: #f8fafc;
  }
  .acceptance-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    max-w-3xl;
    margin: 0 auto;
    padding: 2.5rem;
  }
  .doc-section {
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
  }
  .doc-header {
    background: #f1f5f9;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
  }
  .doc-header h3 {
    margin: 0;
    font-size: 1.125rem;
    color: #1e293b;
    font-weight: 600;
  }
  .doc-content {
    padding: 1.5rem;
    max-height: 300px;
    overflow-y: auto;
    background: #fff;
    font-size: 0.875rem;
    color: #475569;
    line-height: 1.6;
  }
  .acceptance-checkbox {
    display: flex;
    align-items: flex-start;
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
  }
  .acceptance-checkbox input {
    margin-top: 0.25rem;
    margin-right: 0.75rem;
    width: 1.25rem;
    height: 1.25rem;
    accent-color: #10b981;
  }
  .acceptance-checkbox label {
    font-size: 0.875rem;
    color: #334155;
    font-weight: 500;
    cursor: pointer;
  }
</style>
@endsection

@section('content')
<div class="acceptance-container">
    <div class="acceptance-card max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Legal Agreements Update</h1>
            <p class="mt-2 text-slate-600">Please review and accept our updated policies to continue using the platform.</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 border border-red-200 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('legal-acceptance.store') }}" method="POST" id="acceptance-form">
            @csrf
            
            @foreach($pendingVersions as $version)
            <div class="doc-section">
                <div class="doc-header flex justify-between items-center">
                    <h3>{{ $version->document->title }} <span class="text-xs text-slate-500 font-normal ml-2">v{{ $version->version }}</span></h3>
                    <span class="text-xs text-slate-500">Effective: {{ $version->effective_date->format('M d, Y') }}</span>
                </div>
                <div class="doc-content prose prose-sm max-w-none fancy-scroll">
                    {!! $version->content !!}
                </div>
                <div class="acceptance-checkbox">
                    <input type="checkbox" name="documents[]" value="{{ $version->id }}" id="doc_{{ $version->id }}" required>
                    <label for="doc_{{ $version->id }}">
                        I have read and agree to the <strong>{{ $version->document->title }}</strong>.
                    </label>
                </div>
            </div>
            @endforeach
        </form>

        <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm font-medium text-slate-600 hover:text-red-600">Cancel & Logout</button>
            </form>
            <button type="button" onclick="document.getElementById('acceptance-form').submit();" class="btn primary" style="background: #10b981; color: white; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Accept & Continue
            </button>
        </div>
    </div>
</div>
@endsection
