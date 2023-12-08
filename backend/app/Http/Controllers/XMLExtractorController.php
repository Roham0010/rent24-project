<?php

namespace App\Http\Controllers;

use App\Models\SyncStatus;
use App\Models\XMLFileChunkedRawData;
use App\XMLProducts\Jobs\GetStoredChunkedRawData;
use Illuminate\Support\Facades\Storage;

class XMLExtractorController extends Controller
{
    /**
     * Chuncks products data and save it into database to have a better
     * control on the data processing
     *
     * @return void
     */
    public function __invoke(): void
    {
        ini_set('memory_limit', '-1');
        $XMLStringData = Storage::disk('local')->get('all_products.xml');

        $products = $this->extractProductsFromXMLString($XMLStringData);

        $chunkedProducts = array_chunk($products, env('XML_FILE_CHUNCKS'));

        $lastChunkSavedNumber = SyncStatus::query()->where(['type' => 'chunked'])->first()->last_number ?? null;

        foreach ($chunkedProducts as $chunkProduct) {
            echo ', ' . (!isset($i) ? $i = 1 : ++$i);
            if ($i % 50 === 0) {
                echo "<br />";
            }
            if (!$lastChunkSavedNumber || $lastChunkSavedNumber < $i) {
                XMLFileChunkedRawData::query()->create([
                    'chunk_of_data' => json_encode($chunkProduct),
                ]);

                SyncStatus::query()->updateOrCreate(['type' => 'chunked'], ['last_number' => $i]);
            }
        }
    }

    /**
     * @return array
     */
    private function extractProductsFromXMLString(string $XMLStringData): array
    {
        $XMLData = simplexml_load_string($XMLStringData, 'SimpleXMLElement', LIBXML_NOCDATA);

        $json = json_encode($XMLData);
        $XMLDataArray = json_decode($json, TRUE);
        return $XMLDataArray['channel']['product'];
    }

    /**
     * @return void
     */
    public function runTheExtractAndProcessJob()
    {
        GetStoredChunkedRawData::dispatch()->onQueue('sync');
    }
}
