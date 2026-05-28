<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama'            => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'email'           => 'required|email|unique:m_users,email',
            'password'        => 'required|string|min:6|confirmed',
            'no_telp'         => 'nullable|string|max:20',
            'alamat'          => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'            => 'Nama wajib diisi.',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'email.required'           => 'Email wajib diisi.',
            'email.unique'             => 'Email sudah terdaftar.',
            'password.required'        => 'Password wajib diisi.',
            'password.min'             => 'Password minimal 6 karakter.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
        ];
    }
}
