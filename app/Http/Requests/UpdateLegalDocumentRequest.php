<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLegalDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage legal documents');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $document = $this->route('legal_document');
        return [
            'document_type' => 'required|string|max:255|unique:legal_documents,document_type,' . ($document ? $document->id : ''),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
