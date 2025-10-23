<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserMessageRequest extends FormRequest
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
            'session_uuid' => 'required|uuid|exists:session_chats,uuid',
            'message' => 'required_without_all:file,service_id',
            'file' => 'required_without_all:message,service_id',
            'service_id' => 'required_without_all:message,file',
        ];
    }
}
