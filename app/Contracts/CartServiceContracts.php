<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Support\Collection;

interface CartServiceContracts
{
    /**
     * @return Collection
     */
    public function getCart();

    /**
     * @return int
     */
    public function getCount($cart);

    /**
     * @return float
     */
    public function getTotal($cart);


    /**
     * @return float
     */
    public function getShipping($cart);

    /**
     * @return float
     */
    public function getDiscount($cart);

    public function addItem(Product $product, int $quantity);
    public function removeItem($productId, int $quantity);
    public function increment($productId, int $quantity);

    /**
     * @return void
     */
    public function emptyCart();
    public function forget();
}
