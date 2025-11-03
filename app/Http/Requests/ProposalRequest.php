<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('mahasiswa');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'judul' => [
                'required',
                'string',
                'max:255',
                'min:10'
            ],
            'deskripsi' => [
                'required',
                'string',
                'min:100',
                'max:5000'
            ],
            'dosen_id' => [
                'nullable',
                'exists:dosens,id'
            ],
            'file_proposal' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240' // 10MB in KB
            ],
            'file_pitch_deck' => [
                'nullable',
                'file',
                'mimes:pdf,ppt,pptx',
                'max:15360' // 15MB in KB
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul tugas akhir wajib diisi',
            'judul.min' => 'Judul tugas akhir minimal 10 karakter',
            'judul.max' => 'Judul tugas akhir maksimal 255 karakter',
            
            'deskripsi.required' => 'Deskripsi/abstrak wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 100 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter',
            
            'dosen_id.exists' => 'Dosen pembimbing yang dipilih tidak valid',
            
            'file_proposal.required' => 'File proposal wajib diunggah',
            'file_proposal.file' => 'File proposal harus berupa file',
            'file_proposal.mimes' => 'File proposal harus berformat PDF',
            'file_proposal.max' => 'File proposal maksimal 10 MB',
            
            'file_pitch_deck.file' => 'File pitch deck harus berupa file',
            'file_pitch_deck.mimes' => 'File pitch deck harus berformat PDF, PPT, atau PPTX',
            'file_pitch_deck.max' => 'File pitch deck maksimal 15 MB',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'judul' => 'judul tugas akhir',
            'deskripsi' => 'deskripsi',
            'dosen_id' => 'dosen pembimbing',
            'file_proposal' => 'file proposal',
            'file_pitch_deck' => 'file pitch deck',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}