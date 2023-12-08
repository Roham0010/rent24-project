<?php

namespace App\XMLProducts;

class XMLRawProduct
{
	protected array $rawXMLProduct;

	public string $name;
	public string $sku;
	public bool $isInStock;
	public int $quantity;
	public ?string $description;
	public string $size;
	public string $color;
	public ?float $weight;
	public float $price;
	public array $categories;
	public array $images;

	public function __construct(array $rawXMLProduct)
	{
		$this->rawXMLProduct = $rawXMLProduct;

		$this->setAttributes();
	}

	public function setAttributes(): void
	{
		$this->name = $this->rawXMLProduct['name'];
		$this->sku = $this->rawXMLProduct['sku'];
		$this->quantity = (int) $this->rawXMLProduct['qty'];
		$this->isInStock = $this->extractStockStatus();
		$this->description = $this->rawXMLProduct['description'] ?? null;
		$this->size = $this->extractSizeIfNotExists();
		$this->color = $this->extractColorIfNotExists();
		$this->weight = !empty($this->rawXMLProduct['weight']) ? ((float)$this->rawXMLProduct['weight']) : null;
		$this->price = (float)$this->rawXMLProduct['price'];

		$this->categories = $this->extractCategories();
		$this->images = $this->extractImages();
	}

	private function extractImages(): array
	{
		$imageKeys = preg_grep('/^image\d$/', array_keys($this->rawXMLProduct));
		return array_intersect_key($this->rawXMLProduct, array_flip($imageKeys));
	}

	private function extractCategories(): array
	{
		$categories = explode('>', $this->rawXMLProduct['type']);
		array_walk($categories, function (&$category) {
			$category = trim($category);
		});

		return $categories;
	}
	private function extractStockStatus(): bool
	{
		$stockStatusString = $this->rawXMLProduct['is_in_stock'];

		if ($stockStatusString === 'out of stock') {
			if ($this->quantity > 0) {
				return true;
			}

			return false;
		} elseif ($stockStatusString === 'in stock') {
			return true;
		}

		dd($stockStatusString);
	}

	private function extractSizeIfNotExists(): string
	{
		if (isset($this->rawXMLProduct['size'])) {
			return $this->rawXMLProduct['size'];
		} elseif ($this->checkIfSKUMatchesThePatern() && isset($this->rawXMLProduct['sku'])) {
			$pattern = "/-\s*(\w+\s*-\s*\w+\s*\(\d+-\d+\))/";
			if (preg_match($pattern, $this->rawXMLProduct['sku'], $match)) {
				return $match[1];
			}
		}

		return '';
	}

	private function extractColorIfNotExists(): string
	{
		if (isset($this->rawXMLProduct['color'])) {
			return $this->rawXMLProduct['color'];
		} elseif ($this->checkIfSKUMatchesThePatern() && isset($this->rawXMLProduct['sku'])) {
			$pattern = "/-\s*(\w+(?:\s*\w+)*)/";
			if (preg_match($pattern, $this->rawXMLProduct['sku'], $match)) {
				return $match[1];
			}
		}

		return '';
	}

	private function checkIfSKUMatchesThePatern(): bool
	{
		$pattern = "/^\d\d-.*-.*-.*-.*-.*-.*\)/";
		return preg_match($pattern, $this->rawXMLProduct["sku"]);
	}
}
