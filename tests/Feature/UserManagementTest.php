<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin login simulation
        $this->admin = User::factory()->create(['status' => 'Admin']);
        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_users_list()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
    }

    public function test_admin_can_create_a_user()
    {
        Branch::factory()->create(['nama_branch' => 'Jakarta']);

        $data = [
            'name' => 'user1',
            'nama_lengkap' => 'User Satu',
            'email' => 'user1@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Jl. Sudirman',
            'branch' => 'Jakarta',
            'status' => 'Staff',
        ];

        $response = $this->post(route('admin.users.store'), $data);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'user1@example.com',
            'branch' => 'Jakarta',
            'status' => 'Staff'
        ]);
    }

    public function test_user_creation_requires_validation()
    {
        $response = $this->post(route('admin.users.store'), []);
        $response->assertSessionHasErrors(['name', 'nama_lengkap', 'email', 'password', 'nomor_telepon', 'alamat', 'branch', 'status']);
    }

    public function test_admin_can_update_user()
    {
        $branch = Branch::factory()->create(['nama_branch' => 'Bandung']);
        $user = User::factory()->create(['status' => 'Staff', 'branch' => $branch->nama_branch]);

        $data = [
            'name' => 'newname',
            'nama_lengkap' => 'Nama Baru',
            'email' => 'newemail@example.com',
            'nomor_telepon' => '082112345678',
            'alamat' => 'Jl. Merdeka',
            'branch' => 'Bandung',
            'status' => 'Customer',
        ];

        $response = $this->put(route('admin.users.update', $user), $data);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'newemail@example.com',
            'status' => 'Customer'
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
