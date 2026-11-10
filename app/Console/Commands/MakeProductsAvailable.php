<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('products:make-available {--quantity=3 : Exclusive stock quantity for products}')]
#[Description('Ensure all products, variants, and categories are active with refined luxury availability')]
class MakeProductsAvailable extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $defaultQuantity = (int) $this->option('quantity');
        if ($defaultQuantity < 1) {
            $defaultQuantity = 3;
        }

        $this->info("Setting all products and categories to available with refined luxury stock ({$defaultQuantity} pieces remaining)...");

        // 1. Activate all categories
        $categoriesUpdated = Category::query()->update(['is_active' => true]);
        $this->line("Categories activated: {$categoriesUpdated}");

        // 2. Set exclusive luxury stock on all products
        $products = Product::all();
        $productsUpdated = 0;

        foreach ($products as $product) {
            // Refined luxury allocation (e.g., 2 to 4 pieces, centered on target quantity)
            $stock = $defaultQuantity;
            $product->update([
                'is_active' => true,
                'stock_quantity' => $stock,
                'low_stock_threshold' => 1,
            ]);
            $productsUpdated++;
        }
        $this->line("Products updated with {$defaultQuantity} pieces in vault: {$productsUpdated}");

        // 3. Set stock on variants
        $variants = ProductVariant::all();
        $variantsUpdated = 0;

        foreach ($variants as $variant) {
            $variant->update([
                'is_active' => true,
                'stock_quantity' => $defaultQuantity,
            ]);
            $variantsUpdated++;
        }
        $this->line("Variants updated with {$defaultQuantity} pieces in vault: {$variantsUpdated}");

        $totalActive = Product::where('is_active', true)->where('stock_quantity', '>', 0)->count();
        $this->info("SUCCESS: All {$totalActive} creations are now available with exclusive vault availability ({$defaultQuantity} pieces remaining)!");

        return Command::SUCCESS;
    }
}
