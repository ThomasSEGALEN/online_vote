<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileTest extends TestCase
{
    public function test_excel_download()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users/export');

        $response->assertOk();

        $response->streamedContent();
    }

    public function test_excel_upload()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('users.xlsx');

        $response = $this->actingAs($user)->post('/users/import', ['usersFile' => $file]);

        $response->assertFound();
    }
}
