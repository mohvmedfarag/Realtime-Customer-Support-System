<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'session_uuid' => 'required|exists:session_chats,uuid',
            'id'           => 'required',
            'response'     => 'required_without:file|array',
            'type'         => 'in:text,file,checkbox,dropdown',
            'file'         => 'required_without:response|file|max:10240'
        ];
    }
}
