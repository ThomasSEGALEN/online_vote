<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileTest extends TestCase
{
    use HandlesAuthorization;

    public function test_excel_download()
    {
        $user = User::factory()->state(['role_id' => 1])->create();
        $permissions = Role::where('id', 1)->first()->permissions->pluck('id')->toArray();
        $user->permissions()->attach($permissions);

        $response = $this->actingAs($user)->get('/users/export');

        $response->assertOk();

        // $response->streamedContent();
    }

    public function test_excel_upload()
    {
        $user = User::factory()->state(['role_id' => 1])->create();
        $permissions = Role::where('id', 1)->first()->permissions->pluck('id')->toArray();
        $user->permissions()->attach($permissions);
        $file = UploadedFile::fake()->create('users.xlsx');

        $response = $this->actingAs($user)->post('/users/import', ['usersFile' => $file]);

        $response->assertFound();
    }
}
