<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class UserValidationTest extends TestCase
{
    public function test_valid_user_data_passes_validation()
    {
        $data = [
            'name' => 'user',
            'nama_lengkap' => 'User Full',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nomor_telepon' => '08123456789',
            'alamat' => 'Jl. Testing',
            'branch' => 'Jakarta',
            'status' => 'Admin',
        ];

        $rules = [
            'name' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'branch' => 'required|string',
            'status' => 'required|in:Admin,Customer,Staff'
        ];

        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->passes());
    }

    public function test_missing_fields_fails_validation()
    {
        $validator = Validator::make([], [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
        $this->assertArrayHasKey('email', $validator->errors()->messages());
    }

    public function test_invalid_status_fails_validation()
    {
        $data = ['status' => 'Manager'];
        $validator = Validator::make($data, ['status' => 'required|in:Admin,Customer,Staff']);
        $this->assertFalse($validator->passes());
    }
}
