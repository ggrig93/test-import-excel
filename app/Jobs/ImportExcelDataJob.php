<?php

namespace App\Jobs;

use App\Events\NotifyRowCreationEvent;
use App\Models\Row;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportExcelDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection $data
     */
    public $data;

    /**
     * @var string $progressKey
     */
    public $progressKey;

    CONST CHUNKSIZE = 50;

    /**
     * @param Collection $data
     * @param string $progressKey
     */
    public function __construct(Collection $data, string $progressKey)
    {
        $this->data = $data;
        $this->progressKey = $progressKey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data->chunk(self::CHUNKSIZE);

        foreach ($this->data->chunk(self::CHUNKSIZE) as $chunk) {

            DB::transaction(function () use ($chunk) {
                $data = $this->getChunkedRows($chunk);

                $inserted = Row::upsert($data, 'id');

                $progress = Redis::get($this->progressKey) ?? 0;
                $createdData = Redis::get("{$this->progressKey}_inserted") ?? 0;

                Redis::set($this->progressKey, $progress += count($data));
                Redis::set("{$this->progressKey}_inserted", $createdData += $inserted);

                Log::info('redisProgressKey -------' .$this->progressKey);
                Log::info('processed rows -------' .$progress);
                Log::info('inserted -------- ' .$createdData);

                $eventData = ['created' => $createdData, 'processed' => $progress, 'data' => $data];

                event(new NotifyRowCreationEvent($eventData));
            });
        }
    }

    /**
     * @param Collection $chunk
     * @return array
     */
    private function getChunkedRows(Collection $chunk): array
    {
        $data = [];

        foreach ($chunk as $row) {
            $data[] = [
                'id' => intval($row[0]),
                'name' => $row[1],
                'date' => Date::excelToDateTimeObject($row[2]),
            ];
        }

        return $data;
    }

}

