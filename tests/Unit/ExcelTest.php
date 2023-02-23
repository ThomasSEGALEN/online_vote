<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExcelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_excel_download()
    {
        $user = User::factory()->create();

        Excel::fake();

        $response = $this->actingAs($user)->get('/users/export');

        $response->assertOk();

        Excel::assertDownloaded('users.xlsx');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_excel_upload()
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('users.xlsx');

        Excel::fake();

        $response = $this->actingAs($user)->post('/users/import', ['file' => $file]);

        $response->assertFound();

        Excel::assertImported('users.xlsx');
    }
}
