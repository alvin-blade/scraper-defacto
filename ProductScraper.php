<?php

class ProductScraper
{
    public string $url;
    protected DOMDocument $dom;

    public function __construct($url)
    {
        $this->url = $url;
        $this->dom = new DOMDocument();
    }

    public function trimBetween($string, $startSymbol, $endSymbol): false|string
    {
        $startPos = strpos($string, $startSymbol);
        $endPos = strpos($string, $endSymbol, $startPos);

        if ($startPos === false || $endPos === false || $endPos <= $startPos) {
            return false;
        }

        $startPos += strlen($startSymbol);
        $length = $endPos - $startPos;

        return substr($string, $startPos, $length);
    }

    /**
     * @throws Exception
     */
    public function fetchContent(): array|null
    {
        $html = file_get_contents($this->url);
        libxml_use_internal_errors(true);
        $this->dom->loadHTML($html);
        $xpath = new DOMXPath($this->dom);
        $productContainerSelector = "//div[@class='product-card']";
        $productTitleSelector = ".//a[@class='product-card__title--name']";
        $productPriceSelector = ".//div[@class='product-card__price--new d-inline-flex']";
        $imageBox = ".//div[@class='image-box']";

        $products = $xpath->query($productContainerSelector);

        $productArr = [];

        foreach ($products as $index => $product) {
            $title = $xpath->query($productTitleSelector, $product);
            $price = $xpath->query($productPriceSelector, $product);

            if ($index > 3) {
                $imgBox = $this->trimBetween($xpath->query($imageBox, $product)->item(0)
                    ->firstChild->firstChild->firstChild
                    ->getAttribute('data-srcset'), '//', '525');
            } else {
                $imgBox = $xpath->query($imageBox, $product)->item(0)
                    ->firstChild->firstChild->firstChild->getAttribute('data-src');
            }

            $productArr[] = [
                'title' => $title->item(0)->textContent,
                'price' => $price->item(0)->textContent,
                'image' => $imgBox
            ];

        }

        return !empty($productArr) ? $productArr : null;

    }
}