<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * 
     * 
     */
    public function rules()
    {
        $id = $this->route('id'); // Ambil ID dari route

        return [
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'no_hp' => 'required|numeric|digits_between:10,15|unique:users,no_hp,' . $id,
            'address' => 'required|string|max:255',
            'jurusan' => 'required|string|max:100',
            'status' => 'required|in:1,0',
        ];
    }
        public function messages()
    {
        return [
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username sudah digunakan!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',
            'no_hp.required' => 'Nomor HP wajib diisi!',
            'no_hp.numeric' => 'Nomor HP hanya boleh berisi angka!',
            'no_hp.digits_between' => 'Nomor HP harus 10-15 digit!',
            'no_hp.unique' => 'Nomor HP sudah digunakan!',
            'address.required' => 'Alamat wajib diisi!',
            'jurusan.required' => 'Silakan pilih jurusan!',
            'jurusan.in' => 'Jurusan yang dipilih tidak valid!',
            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 8 karakter!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi!'
        ];
    }
}