<?php

namespace Tests\Feature;

use App\Imports\ExcelImport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ImportFromExcelTest extends TestCase
{
    use DatabaseTransactions;

    public function testUploadedFileProcessed()
    {
        Queue::fake();
        Excel::fake();

        $file = UploadedFile::fake()->create(
            base_path('tests/Files/test.xlsx'),
        );

        $this->post('/import', [
            'file' => $file
        ])->assertRedirect('/');

        Excel::assertImported('test.xlsx');
    }


     public function testImportedExcelData()
    {
        Excel::import(new ExcelImport,
            base_path('tests/Files/test.xlsx'));

        $this->assertDatabaseCount('rows', 2474);

        $lastRecordOfExcelFile = [
            'id' => '2474',
            'name' => 'Denim',
            'date' => '2027-07-22',
        ];

        $this->assertDatabaseHas('rows', $lastRecordOfExcelFile );
    }

}
