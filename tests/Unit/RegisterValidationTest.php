<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterValidationTest extends TestCase
{
    private function getValidationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email', // Hapus unique:users
            'nomor_telepon' => 'required|numeric',
            'alamat' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
            'terms' => 'accepted',
        ];
    }
    

    public function test_valid_data_passes_validation()
    {
        $data = [
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'email' => 'johndoe@example.com',
            'nomor_telepon' => '81234567890',
            'alamat' => 'Jl. Contoh No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertTrue($validator->passes());
    }

    public function test_missing_fields_fails_validation()
    {
        $data = []; // Kosong

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_invalid_email_fails_validation()
    {
        $data = [
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'email' => 'invalid-email',
            'nomor_telepon' => '81234567890',
            'alamat' => 'Jl. Contoh No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_password_confirmation_mismatch_fails_validation()
    {
        $data = [
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'email' => 'johndoe@example.com',
            'nomor_telepon' => '81234567890',
            'alamat' => 'Jl. Contoh No. 1',
            'password' => 'password123',
            'password_confirmation' => 'wrongpass',
            'terms' => 'on',
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_terms_not_accepted_fails_validation()
    {
        $data = [
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'email' => 'johndoe@example.com',
            'nomor_telepon' => '81234567890',
            'alamat' => 'Jl. Contoh No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // 'terms' => 'on', // terms tidak dicentang
        ];

        $validator = Validator::make($data, $this->getValidationRules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('terms', $validator->errors()->toArray());
    }
}
