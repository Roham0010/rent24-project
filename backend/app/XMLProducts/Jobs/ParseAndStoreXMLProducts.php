<?php

namespace App\XMLProducts\Jobs;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\XMLProducts\XMLRawProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAndStoreXMLProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $products;

    private XMLRawProduct $XMLRawProduct;
    /**
     * Create a new job instance.
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->products as $rawProduct) {
            if (!$rawProduct['name']) {
                // This product can't be inserted it's corrupted!
                // should be logged somewhere in a real project
                continue;
            }

            $this->XMLRawProduct = new XMLRawProduct($rawProduct);

            $product = Product::query()->where(['name' => $this->XMLRawProduct->name])->first();

            if (!$product) {

                $product = $this->createProduct();

                $this->createAndSyncCategories($product);

                $this->createImages($product);
            }

            $this->createAndSyncVariants($product);
        }
    }

    private function createProduct(): Product
    {
        $productData = [
            'name' => $this->XMLRawProduct->name,
            'sku' => $this->XMLRawProduct->sku,
            'is_in_stock' => $this->XMLRawProduct->isInStock,
            'quantity' => $this->XMLRawProduct->quantity,
            'description' => $this->XMLRawProduct->description,
        ];

        return Product::query()->create($productData);
    }

    private function createAndSyncCategories(Product $product): void
    {
        $productCategories = $this->XMLRawProduct->categories;
        $category = null;
        $productCategoryIds = array_map(function ($cat) use (&$category) {
            $category = Category::firstOrCreate([
                'name' => $cat
            ], [
                'parent_id' => $category ? $category->id : null
            ]);
            return $category->id;
        }, $productCategories);
        $product->categories()->sync($productCategoryIds);
    }

    private function createImages(Product $product): void
    {
        $productImages = $this->XMLRawProduct->images;
        $productImages = array_map(function ($image) {
            return [
                'url' => $image
            ];
        }, array_values($productImages));
        $product->images()->createMany(array_filter($productImages));
    }

    private function createAndSyncVariants(Product $product): void
    {
        $productVariants = [
            'size' => $this->XMLRawProduct->size,
            'color' => $this->XMLRawProduct->color,
            'weight' => $this->XMLRawProduct->weight,
            'price' => $this->XMLRawProduct->price,
        ];
        $productVariantIds = [];
        foreach (array_filter($productVariants) as $type => $value) {
            $variant = Variant::firstOrCreate([
                'value' => $value,
                'type' => $type,
            ]);
            $productVariantIds[] = $variant->id;
        }

        $product->variants()->sync($productVariantIds);
    }
}
