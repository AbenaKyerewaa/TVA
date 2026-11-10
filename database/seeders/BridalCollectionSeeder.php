<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class BridalCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::where('slug', 'engagement')->first();

        if (! $category) {
            $category = Category::create([
                'name' => 'Bridal & Engagement',
                'slug' => 'engagement',
                'description' => 'A celebrated collection of haute couture bridal gowns, bespoke grooms suits, and certified solitaire diamonds sculpted with unyielding integrity.',
                'image' => '/images/products/aurelia-silk-mikado-gown-diamond-choker.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 5,
            ]);
        }

        $bridalItems = [
            // --- 4 LADIES BRIDAL GOWNS ---
            [
                'name' => 'The Aurelia Silk-Mikado Gown & Diamond Choker Suite',
                'slug' => 'aurelia-silk-mikado-gown-diamond-choker',
                'sku' => 'TVA-BRD-L01',
                'price' => 84500.00,
                'compare_at_price' => 98000.00,
                'short_description' => 'Architectural ivory silk-mikado ballgown sculpted with a structured sweetheart bodice, adorned with an articulated 15-carat cascading pear-and-marquise diamond choker in 950 platinum.',
                'description' => "Born of architectural purity and haute joaillerie mastery, The Aurelia Gown is tailored in pure French silk-mikado with a sweeping cathedral train and corseted contouring.\n\nEncircling the décolletage is a bespoke 15.00-carat choker composed of graduated marquise and pear-cut D/IF certified diamonds set in 950 solid platinum. Each gown is custom-fitted by master couturiers in Paris and Geneva.",
                'materials' => 'Lyon Silk-Mikado & 950 Solid Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '15.00ct D/IF Marquise & Pear Diamonds',
                'weight_grams' => 18.50,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/aurelia-silk-mikado-gown-diamond-choker.jpg',
                ],
                'variants' => [
                    ['name' => 'Bespoke Atelier Fit / Platinum Suite', 'size' => 'Custom Fit', 'metal_type' => '950 Platinum', 'stock' => 2],
                ],
                'attributes' => [
                    'Collection Segment' => "Ladies' Bridal Gown",
                    'Gown Silhouette' => 'Architectural Sweetheart Ballgown',
                    'Gown Fabric' => '100% Lyon Silk-Mikado with Concealed Pockets',
                    'Jewelry Suite' => '15.00ct Cascading Marquise & Pear Diamond Choker',
                    'Diamond Grading' => 'D-Color / Flawless Clarity (GIA Certified)',
                    'Train Length' => 'Cathedral 2.8 Meters',
                ],
            ],
            [
                'name' => 'The Vendôme Chantilly Lace Gown & Muzo Emerald Tiara',
                'slug' => 'vendome-chantilly-gown-emerald-tiara',
                'sku' => 'TVA-BRD-L02',
                'price' => 135000.00,
                'compare_at_price' => 155000.00,
                'short_description' => 'French Chantilly lace illusion silhouette with hand-embroidered seed pearls, paired with an imperial Colombian Muzo emerald and diamond diadem tiara.',
                'description' => "An homage to regal European dynastic ceremonies. Delicate hand-loomed Chantilly lace cascades over sheer nude silk-illusion tulle with a scalloped hemline.\n\nThe centerpiece of the bridal ensemble is an arresting royal diadem tiara crowned with three octagonal Muzo Colombian emeralds totaling 9.20 carats, encircled by brilliant diamond halos and set in solid 18K white gold.",
                'materials' => 'French Chantilly Lace & 18K White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '9.20ct Muzo Emeralds & 8.50ct Diamonds',
                'weight_grams' => 24.00,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/vendome-chantilly-gown-emerald-tiara.jpg',
                ],
                'variants' => [
                    ['name' => 'Bespoke Haute Couture / Emerald Suite', 'size' => 'Custom Fit', 'metal_type' => '18K White Gold', 'stock' => 1],
                ],
                'attributes' => [
                    'Collection Segment' => "Ladies' Bridal Gown",
                    'Gown Silhouette' => 'Illusion Lace Long-Sleeve Silhouette',
                    'Gown Fabric' => 'Hand-Loomed French Chantilly Lace & Silk Illusion',
                    'Jewelry Suite' => 'Imperial Royal Diadem Tiara with 3 Muzo Emeralds',
                    'Emerald Provenance' => 'Muzo Mines, Colombia (Minor Cedar Oil)',
                    'Tiara Construction' => 'Solid 18K White Gold Articulated Frame',
                ],
            ],
            [
                'name' => 'The Seraphina Crepe Column Gown & Waterfall Diamond Back-Lariat',
                'slug' => 'seraphina-crepe-column-gown-waterfall-diamond',
                'sku' => 'TVA-BRD-L03',
                'price' => 98000.00,
                'compare_at_price' => 115000.00,
                'short_description' => 'Svelte Italian heavy silk-crepe column gown with a dramatic low cowl back, highlighted by an articulated 18-carat waterfall diamond reverse-drop lariat necklace.',
                'description' => "Sensual minimalism meets architectural brilliance. The Seraphina gown hugs the body in fluid Italian heavy silk crepe-de-chine before opening to a breathtaking low cowl back.\n\nFloating down the spine is a master-crafted 24-inch reverse-drop rivière lariat comprising 18.00 carats of graduated cushion and round brilliant diamonds that catch light with every stride.",
                'materials' => 'Italian Silk Crepe & 18K Solid White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '18.00ct Graduated Cushion & Round Diamonds',
                'weight_grams' => 16.20,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/seraphina-crepe-column-gown-waterfall-diamond.jpg',
                ],
                'variants' => [
                    ['name' => 'Custom Column Tailoring / White Gold Suite', 'size' => 'Custom Fit', 'metal_type' => '18K White Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Collection Segment' => "Ladies' Bridal Gown",
                    'Gown Silhouette' => 'Minimalist Cowl-Back Column Gown',
                    'Gown Fabric' => 'Double-Weight Italian Silk Crepe-de-Chine',
                    'Jewelry Suite' => '24-Inch Articulated Waterfall Diamond Back-Lariat',
                    'Diamond Carat' => '18.00 Total Carats (E/VVS1)',
                    'Closure' => 'Hidden Platinum Snap-Lock on Shoulder Seam',
                ],
            ],
            [
                'name' => 'The Celestia Pleated Tulle Gown & Kashmir Sapphire Suite',
                'slug' => 'celestia-pleated-tulle-gown-kashmir-sapphire',
                'sku' => 'TVA-BRD-L04',
                'price' => 158000.00,
                'compare_at_price' => 180000.00,
                'short_description' => 'Voluminous multi-tiered pleated English tulle ballgown paired with a 10.45-carat royal velvet Kashmir sapphire collar necklace and drop earrings.',
                'description' => "A dreamscape of weightless volume. Over 120 meters of hand-pleated English tulle billow from a boned silk-corset bodice into an ethereal tiered ballgown.\n\nThe jewelry adornment is crowned by a legendary 10.45-carat unheated Kashmir sapphire displaying velvety royal cornflower blue, mounted in platinum with marquise diamond foliage and matching ear pendants.",
                'materials' => 'English Tulle, Silk Organza & 950 Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '10.45ct Royal Velvet Kashmir Sapphire & 12.00ct Diamonds',
                'weight_grams' => 26.50,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/celestia-pleated-tulle-gown-kashmir-sapphire.jpg',
                ],
                'variants' => [
                    ['name' => 'Palatial Haute Couture / Kashmir Suite', 'size' => 'Custom Fit', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Collection Segment' => "Ladies' Bridal Gown",
                    'Gown Silhouette' => 'Multi-Tiered Cloud Ballgown',
                    'Gown Fabric' => 'Hand-Pleated English Tulle & Mulberry Silk Organza',
                    'Jewelry Suite' => '10.45ct Kashmir Sapphire Collar & Drop Earrings',
                    'Sapphire Origin' => 'Kashmir (Unheated, SSEF & Gübelin Certified)',
                    'Crafting Hours' => '650 Hours Tailoring & Lapidary Setting',
                ],
            ],

            // --- 5 MEN'S BRIDAL SUITS ---
            [
                'name' => 'The Sovereign Midnight Barathea Tuxedo & Paraíba Tourmaline Brooch',
                'slug' => 'sovereign-midnight-tuxedo-paraiba-brooch',
                'sku' => 'TVA-BRD-M01',
                'price' => 68000.00,
                'compare_at_price' => 78000.00,
                'short_description' => 'Bespoke midnight-navy English wool barathea tuxedo with black silk grosgrain peak lapels, anchored by a 4.50-carat neon Paraíba tourmaline brooch.',
                'description' => "The pinnacle of masculine bridal elegance. Cut from Super 160s English wool barathea in deep midnight navy with handmade silk grosgrain peak facings and roped shoulders.\n\nMounted on the lapel is an extraordinary architectural brooch centering an electrified neon cyan Brazilian Paraíba tourmaline of 4.50 carats, flanked by channel-set diamond baguettes in 950 platinum.",
                'materials' => 'Super 160s English Barathea Wool & 950 Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '4.50ct Brazilian Paraíba Tourmaline & 2.20ct Baguette Diamonds',
                'weight_grams' => 12.80,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/sovereign-midnight-tuxedo-paraiba-brooch.jpg',
                ],
                'variants' => [
                    ['name' => 'Bespoke Savile Row Cut / Platinum Suite', 'size' => 'Bespoke', 'metal_type' => '950 Platinum', 'stock' => 2],
                ],
                'attributes' => [
                    'Collection Segment' => "Men's Bridal Suit",
                    'Suit Silhouette' => 'Double-Breasted Midnight Barathea Dinner Suit',
                    'Suit Fabric' => 'Super 160s English Wool Barathea with Silk Grosgrain',
                    'Jewelry Accent' => '4.50ct Electric Neon Brazilian Paraíba Lapel Brooch',
                    'Gemstone Origin' => 'São José da Batalha, Paraíba, Brazil',
                    'Cufflinks' => 'Matching Platinum & Baguette Diamond Sleeve Links',
                ],
            ],
            [
                'name' => 'The Grand Ducal Ivory Jacquard Dinner Jacket & Muzo Emerald Studs',
                'slug' => 'grand-ducal-ivory-jacket-emerald-studs',
                'sku' => 'TVA-BRD-M02',
                'price' => 92000.00,
                'compare_at_price' => 108000.00,
                'short_description' => 'Ivory silk-damask jacquard dinner jacket with black satin shawl lapels, fitted with 4 octagonal Muzo emerald shirt studs and chain-link cufflinks in 18K yellow gold.',
                'description' => "Woven on historic Como jacquard looms, this dinner jacket features a subtle botanical damask motif tailored with black duchess satin shawl lapels.\n\nStyled with a four-piece formal set of octagonal Muzo Colombian emerald shirt studs totaling 5.80 carats, and matching double-sided chain-link cufflinks forged in solid 18K hand-brushed yellow gold.",
                'materials' => 'Como Silk Damask Jacquard & 18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '5.80ct Octagonal Colombian Muzo Emeralds',
                'weight_grams' => 15.40,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/grand-ducal-ivory-jacket-emerald-studs.jpg',
                ],
                'variants' => [
                    ['name' => 'Bespoke Como Cut / 18K Gold Suite', 'size' => 'Bespoke', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Collection Segment' => "Men's Bridal Suit",
                    'Suit Silhouette' => 'Single-Button Satin Shawl Collar Dinner Jacket',
                    'Suit Fabric' => 'Pure Italian Silk Damask Jacquard from Lake Como',
                    'Jewelry Accent' => 'Set of 4 Muzo Emerald Studs & Chain-Link Cufflinks',
                    'Gold Hallmark' => '18K Solid 750 Yellow Gold (Hand-Satin Finish)',
                    'Emerald Cut' => 'Step-Cut Octagonal (Muzo Green)',
                ],
            ],
            [
                'name' => 'The Vendôme Charcoal Wool-Silk Three-Piece Suit & Diamond Albert Chain',
                'slug' => 'vendome-charcoal-three-piece-diamond-chain',
                'sku' => 'TVA-BRD-M03',
                'price' => 58000.00,
                'compare_at_price' => 68000.00,
                'short_description' => 'Savile Row charcoal wool-silk three-piece wedding suit featuring a solid 18K white gold double-Albert vest chain with a 2.50-carat diamond fob.',
                'description' => "A commanding formal ensemble for the modern connoisseur. Tailored from an ultra-fine charcoal wool-and-mulberry-silk blend with a horseshoe waistcoat and wide peak lapels.\n\nSweeping across the vest is a solid 18K white gold double-Albert curb chain, suspending an antique-inspired rotating fob set with a 2.50-carat emerald-cut diamond.",
                'materials' => 'Super 150s Wool-Silk Blend & 18K Solid White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '2.50ct Emerald-Cut Diamond (VVS1 / E Color)',
                'weight_grams' => 22.50,
                'stock_quantity' => 3,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/vendome-charcoal-three-piece-diamond-chain.jpg',
                ],
                'variants' => [
                    ['name' => 'Three-Piece Bespoke Cut / White Gold Suite', 'size' => 'Bespoke', 'metal_type' => '18K White Gold', 'stock' => 3],
                ],
                'attributes' => [
                    'Collection Segment' => "Men's Bridal Suit",
                    'Suit Silhouette' => 'Structured Three-Piece Suit with Peak Lapels',
                    'Suit Fabric' => 'Super 150s English Worsted Wool & Mulberry Silk',
                    'Jewelry Accent' => '18K White Gold Double-Albert Vest Chain & Diamond Fob',
                    'Waistcoat Style' => 'Horseshoe Evening Waistcoat with Horn Buttons',
                    'Lapel Bar' => 'Flush-Set Baguette Diamond Collar Pin Included',
                ],
            ],
            [
                'name' => 'The Royal Imperial Black Velvet Tuxedo & Kashmir Sapphire Cufflinks',
                'slug' => 'royal-imperial-black-velvet-tuxedo-sapphire-cufflinks',
                'sku' => 'TVA-BRD-M04',
                'price' => 76000.00,
                'compare_at_price' => 88000.00,
                'short_description' => 'Midnight black Italian cotton-velvet evening suit complemented by sugarloaf cabochon Kashmir royal sapphire bar-cufflinks in mirror-polished 950 platinum.',
                'description' => 'Impeccable evening suiting crafted from deep onyx Italian cotton velvet with rich tactile depth. Accessorized with double-sided platinum bar-cufflinks holding 6.80 carats of sugarloaf cabochon Kashmir royal blue sapphires, paired with a matching platinum and sapphire collar bar.',
                'materials' => 'Italian Cotton Velvet, Duchess Silk & 950 Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '6.80ct Sugarloaf Cabochon Kashmir Sapphires',
                'weight_grams' => 14.20,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/royal-imperial-black-velvet-tuxedo-sapphire-cufflinks.jpg',
                ],
                'variants' => [
                    ['name' => 'Italian Velvet Evening Fit / Platinum Suite', 'size' => 'Bespoke', 'metal_type' => '950 Platinum', 'stock' => 2],
                ],
                'attributes' => [
                    'Collection Segment' => "Men's Bridal Suit",
                    'Suit Silhouette' => 'Single-Breasted Velvet Smoking Jacket & Formal Trousers',
                    'Suit Fabric' => 'Luxury Italian Cotton-Velvet with Pure Silk Satin Trim',
                    'Jewelry Accent' => 'Sugarloaf Cabochon Kashmir Sapphire Bar-Cufflinks',
                    'Sapphire Hue' => 'Royal Blue Velvety Saturation (Unheated)',
                    'Collar Accessory' => 'Solid 950 Platinum Tie/Collar Bar',
                ],
            ],
            [
                'name' => 'The Archduke Champagne Cashmere Groom\'s Suit & Yellow Diamond Pin',
                'slug' => 'archduke-champagne-cashmere-suit-yellow-diamond-pin',
                'sku' => 'TVA-BRD-M05',
                'price' => 98000.00,
                'compare_at_price' => 115000.00,
                'short_description' => 'Tailored champagne Loro Piana cashmere-silk morning suit adorned with a 3.80-carat radiant Fancy Vivid Yellow diamond sunburst brooch.',
                'description' => "Created for destination and garden nuptials of the highest caliber. Cut from unspun Loro Piana champagne cashmere blended with fine mulberry silk for an ethereal drape in natural sunlight.\n\nFastened to the lapel is a solar sunburst brooch sculpted in 18K solid yellow gold, radiating around a sensational 3.80-carat GIA-certified Fancy Vivid Yellow diamond.",
                'materials' => 'Loro Piana Cashmere-Silk & 18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '3.80ct Fancy Vivid Yellow Radiant Diamond (VS1)',
                'weight_grams' => 11.60,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/archduke-champagne-cashmere-suit-yellow-diamond-pin.jpg',
                ],
                'variants' => [
                    ['name' => 'Loro Piana Bespoke Fit / 18K Gold Suite', 'size' => 'Bespoke', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Collection Segment' => "Men's Bridal Suit",
                    'Suit Silhouette' => 'Modern Daytime Morning Suit with Tailored Waist',
                    'Suit Fabric' => 'Loro Piana Cashmere & Mulberry Silk (Champagne Tone)',
                    'Jewelry Accent' => '3.80ct Fancy Vivid Yellow Diamond Solar Lapel Pin',
                    'Diamond Certificate' => 'GIA Natural Fancy Vivid Yellow Color Origin',
                    'Watch Clasp' => 'Matching Hand-Engraved 18K Gold Dress Buckle',
                ],
            ],
        ];

        foreach ($bridalItems as $item) {
            $product = Product::updateOrCreate(
                ['sku' => $item['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'short_description' => $item['short_description'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'compare_at_price' => $item['compare_at_price'],
                    'cost_price' => round($item['price'] * 0.45, -2),
                    'materials' => $item['materials'],
                    'metal_color' => $item['metal_color'],
                    'gemstone' => $item['gemstone'],
                    'weight_grams' => $item['weight_grams'],
                    'stock_quantity' => max(2, (int) ($item['stock_quantity'] ?? 3)),
                    'low_stock_threshold' => 1,
                    'is_featured' => $item['is_featured'],
                    'is_new_arrival' => $item['is_new_arrival'],
                    'is_bestseller' => $item['is_bestseller'],
                    'is_active' => true,
                ]
            );

            ProductImage::where('product_id', $product->id)->delete();
            foreach ($item['images'] as $index => $imgPath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgPath,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                    'alt_text' => $product->name,
                ]);
            }

            ProductVariant::where('product_id', $product->id)->delete();
            foreach ($item['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'sku' => $product->sku.'-V1',
                    'price' => $product->price,
                    'stock_quantity' => max(2, (int) ($v['stock'] ?? 3)),
                    'size' => $v['size'],
                    'metal_type' => $v['metal_type'],
                    'is_active' => true,
                ]);
            }

            ProductAttribute::where('product_id', $product->id)->delete();
            foreach ($item['attributes'] as $attrName => $attrValue) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'attribute_name' => $attrName,
                    'attribute_value' => $attrValue,
                ]);
            }
        }
    }
}
