<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_register_form()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Registrasi');
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post(route('register.process'), [
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'email' => 'johndoe@example.com',
            'nomor_telepon' => '81234567890',
            'alamat' => 'Jl. Contoh No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ]);

        $response->assertRedirect(); // Redirect ke halaman setelah sukses (misalnya dashboard)

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'nomor_telepon' => '81234567890',
        ]);
    }

    public function test_registration_fails_with_missing_fields()
    {
        $response = $this->post(route('register.process'), []);
    
        $response->assertSessionHasErrors([
            'name',
            'nama_lengkap',
            'email',
            'nomor_telepon',
            'alamat',
            'password',
        ]);
    }
    

    public function test_registration_requires_valid_email()
    {
        $response = $this->post(route('register.process'), [
            'name' => 'johndoe',
            'nama_lengkap' => 'John Doe',
            'email' => 'not-an-email',
            'nomor_telepon' => '81234567890',
            'alamat' => 'Jl. Contoh No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
