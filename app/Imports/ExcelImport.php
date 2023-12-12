<?php

namespace App\Imports;


use App\Jobs\ImportExcelDataJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;


class ExcelImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithCalculatedFormulas

{
    use Importable;

    const CHUNKSIZE = 1000;

    /**
     * @param Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $redisKey = $this->redisKey();

        foreach ($rows->chunk(self::CHUNKSIZE) as $chunk) {
            ImportExcelDataJob::dispatch($chunk, $redisKey)->afterCommit();
        }
    }

    /**
     * @return string
     */
    private function redisKey(): string
    {
        return 'progress_'.uniqid(true);
    }

    /**
     * @return int
     * skipp heading
     */
    public function startRow(): int
    {
        return 2;
    }
}
