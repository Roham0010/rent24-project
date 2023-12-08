<?php

namespace App\XMLProducts\Jobs;

use App\Models\SyncStatus;
use App\Models\XMLFileChunkedRawData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetStoredChunkedRawData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get 15 batch of 25 items each time, and send it to be processed and saved.
     * It also saves the latest chunk id that it processed for the next execution.
     */
    public function handle(): void
    {
        $syncStatus = SyncStatus::query()->where(['type' => 'processed'])->first();
        $lastChunkSavedNumber = $syncStatus->last_number ?? 0;
        $lastChunkSavedNumber++;
        $startedFrom = $lastChunkSavedNumber;

        // The next line is only for local development and should be removed in production
        set_time_limit(5000);

        while (
            // The next line is only for local development and should be removed in production
            $lastChunkSavedNumber < ($startedFrom + 1200) &&
            $chunkRawProductsData = XMLFileChunkedRawData::query()
            ->where(['id' => $lastChunkSavedNumber])->first()
        ) {
            $arrayProductsData = json_decode($chunkRawProductsData->chunk_of_data, true);

            ParseAndStoreXMLProducts::dispatch($arrayProductsData)->onQueue('sync');

            if ($syncStatus) {
                $syncStatus->update(['last_number' => $lastChunkSavedNumber]);
            } else {
                $syncStatus = SyncStatus::query()->create(['type' => 'processed', 'last_number' => $lastChunkSavedNumber]);
            }

            $lastChunkSavedNumber++;
        }
    }
}
