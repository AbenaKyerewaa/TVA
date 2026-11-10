<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Helper to generate a prestigious luxury price ending in hundreds or thousands.
     */
    protected function randomLuxuryPrice(int $min = 59000, int $max = 735000): float
    {
        $step = 500;
        $randomSteps = rand((int) ($min / $step), (int) ($max / $step));

        return (float) ($randomSteps * $step);
    }

    /**
     * Seed the application's database with luxury jewelry e-commerce data.
     */
    public function run(): void
    {
        // 1. Seed Administrators and Demonstration Customers
        $admin = User::firstOrCreate(
            ['email' => 'admin@tvajewelry.com'],
            [
                'name' => 'TVA Atelier Admin',
                'phone' => '+1 (800) 555-8822',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@tvajewelry.com'],
            [
                'name' => 'Eleanor Vance',
                'phone' => '+1 (415) 890-1234',
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        CustomerProfile::firstOrCreate(
            ['user_id' => $customer->id],
            [
                'date_of_birth' => '1992-06-18',
                'gender' => 'Female',
                'anniversary_date' => '2021-09-24',
                'preferences' => ['preferred_metal' => '18K Yellow Gold', 'ring_size' => '6.5', 'newsletter' => true],
                'notes' => 'VIP Collector. Prefers solitaire cut diamonds and emerald accents.',
            ]
        );

        Address::firstOrCreate(
            ['user_id' => $customer->id, 'type' => 'shipping'],
            [
                'full_name' => 'Eleanor Vance',
                'phone' => '+1 (415) 890-1234',
                'address_line_1' => '742 Evergreen Terrace, Suite 400',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '94102',
                'country' => 'United States',
                'is_default' => true,
            ]
        );

        // 2. Seed Shipping Methods
        $shippingMethods = [
            [
                'name' => 'Complimentary White-Glove Insured Delivery',
                'code' => 'insured_standard',
                'description' => 'Fully insured discrete courier with signature confirmation required upon delivery.',
                'fee' => 0.00,
                'estimated_days' => '3–5 business days',
                'is_active' => true,
            ],
            [
                'name' => 'Priority Armored Express Courier',
                'code' => 'priority_express',
                'description' => 'Priority overnight expedited transit with dedicated security escort and GPS tracking.',
                'fee' => 50.00,
                'estimated_days' => '1–2 business days',
                'is_active' => true,
            ],
            [
                'name' => 'TVA Private Salon In-Store Collection',
                'code' => 'salon_pickup',
                'description' => 'Personalized concierge appointment and champagne collection at our flagship boutique.',
                'fee' => 0.00,
                'estimated_days' => 'Same day collection',
                'is_active' => true,
            ],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::firstOrCreate(['code' => $method['code']], $method);
        }

        // 3. Seed Promotional Coupons
        $coupons = [
            [
                'code' => 'WELCOME10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_spend' => 1000.00,
                'max_discount' => 25000.00,
                'usage_limit' => 500,
                'times_used' => 12,
                'starts_at' => now()->subDays(30),
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'TVALUXURY50',
                'type' => 'fixed',
                'value' => 500.00,
                'min_spend' => 2500.00,
                'max_discount' => 500.00,
                'usage_limit' => 200,
                'times_used' => 5,
                'starts_at' => now()->subDays(10),
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(['code' => $coupon['code']], $coupon);
        }

        // 4. Seed Categories
        $categoriesData = [
            [
                'name' => 'High Jewelry Rings',
                'slug' => 'rings',
                'description' => 'Exquisitely handcrafted statement solitaires, untreated Burma rubies, and Colombian emeralds in solid 18K gold and platinum.',
                'image' => '/images/products/aurelia-solitaire-diamond-ring.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Haute Joaillerie Necklaces & Pendants',
                'slug' => 'necklaces',
                'description' => 'Rare diamond rivières, cascade emerald collars, and museum-grade sapphire sautoirs engineered with fluid architectural grace.',
                'image' => '/images/products/queens-riviere-graduated-diamond-necklace.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Earrings',
                'slug' => 'earrings',
                'description' => 'From luminous diamond pavé huggies to cascade drop earrings crafted to capture light from every angle.',
                'image' => '/images/products/luminary-pave-huggie-hoops.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Bracelets & Cuffs',
                'slug' => 'bracelets',
                'description' => 'Station bracelets engineered with seamless flexibility and structural hinged cuffs that redefine wristwear.',
                'image' => '/images/products/arc-riviera-bezel-diamond-station-bracelet.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Bridal & Engagement',
                'slug' => 'engagement',
                'description' => 'A celebrated collection of certified conflict-free solitaire diamond engagement heirlooms sculpted with unyielding integrity.',
                'image' => '/images/products/sovereign-emerald-cut-solitaire.jpg',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Fine Timepieces',
                'slug' => 'watches',
                'description' => 'Swiss-engineered horology wrapped in hand-finished rose and yellow gold casings.',
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=85',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::updateOrCreate(['slug' => $cData['slug']], $cData);
        }

        // 5. Complete Fine & High Jewelry Catalog (21 Unique Pieces, 21 Strictly Corresponding Luxury Images)
        $productsData = [
            // --- HIGH VALUED RINGS ($59,000 to $735,000) ---
            [
                'category_slug' => 'rings',
                'name' => 'The Aurelia Solitaire Diamond Ring',
                'slug' => 'aurelia-solitaire-diamond-ring',
                'sku' => 'TVA-RNG-001',
                'short_description' => 'A sculptural 18K solid yellow gold band cradling a master-cut 3.5-carat VVS1 round brilliant diamond.',
                'description' => "Handcrafted in our private atelier, The Aurelia Solitaire redefines the quintessential high jewelry ring. A custom four-prong claw setting lifts the certified diamond to optimize brilliance and chromatic refraction. Each band is contoured for an ergonomic, velvet-smooth fit against the skin.\n\nCrafted from 100% recycled 18-karat gold with a mirror polish finish.",
                'materials' => '18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '3.50ct Certified Diamond (VVS1 / E Color)',
                'weight_grams' => 6.20,
                'stock_quantity' => 5,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/aurelia-solitaire-diamond-ring.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / 18K Yellow Gold', 'size' => '6', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                    ['name' => 'Size 7 / 18K Yellow Gold', 'size' => '7', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                    ['name' => 'Size 8 / 18K Yellow Gold', 'size' => '8', 'metal_type' => '18K Yellow Gold', 'stock' => 1],
                ],
                'attributes' => [
                    'Carat Weight' => '3.50 ct',
                    'Cut' => 'Round Brilliant',
                    'Clarity' => 'VVS1',
                    'Color Grade' => 'E Color',
                    'Certificate' => 'GIA Certified Diamond Hallmark',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Archduke 7.4ct Fancy Vivid Yellow Diamond Solitaire',
                'slug' => 'archduke-fancy-vivid-yellow-diamond-solitaire',
                'sku' => 'TVA-RNG-006',
                'short_description' => 'An ultra-rare 7.40-carat radiant-cut Fancy Vivid Yellow diamond framed with tapered baguette diamond shoulders in 18K solid gold.',
                'description' => "Celebrated for its intense, golden-sunburst saturation, The Archduke showcases an extraordinary 7.40-carat Fancy Vivid Yellow radiant-cut diamond. Handcrafted with an 18K yellow gold inner basket to intensify the natural yellow fire, mounted on a solid 950 platinum shank with calibrated side baguettes.\n\nGraded with exceptional polish and symmetry by the Gemological Institute of America (GIA).",
                'materials' => '18K Yellow Gold & 950 Platinum',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '7.40ct Fancy Vivid Yellow Diamond (VS1) + 1.2ct Baguette Diamonds',
                'weight_grams' => 8.60,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/archduke-fancy-vivid-yellow-diamond-solitaire.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6.5 / Atelier Platinum & Gold', 'size' => '6.5', 'metal_type' => '18K Yellow Gold / Platinum', 'stock' => 1],
                    ['name' => 'Size 7 / Atelier Platinum & Gold', 'size' => '7', 'metal_type' => '18K Yellow Gold / Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Center Carat' => '7.40 ct',
                    'Color Grade' => 'Natural Fancy Vivid Yellow',
                    'Clarity' => 'VS1 (GIA Certified)',
                    'Side Stones' => '1.20ct D-Flawless Baguettes',
                    'Hallmark' => 'TVA Atelier Master Hallmark',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Royal Mogok Pigeon Blood Ruby Ring',
                'slug' => 'royal-mogok-pigeon-blood-ruby-ring',
                'sku' => 'TVA-RNG-007',
                'short_description' => 'A museum-grade 5.10-carat unheated Burmese Pigeon Blood ruby flanked by twin shield-cut diamonds.',
                'description' => "Mined from the legendary valley of Mogok, Burma, this 5.10-carat cushion-cut ruby exhibits the iconic, unadulterated 'Pigeon Blood' red hue. Completely untreated and unheated, it displays mesmerizing natural fluorescence under sunlight. Flanked by two custom shield-cut D-color diamonds mounted on an 18K solid yellow gold gallery.\n\nAccompanied by dual Gübelin and SSEF Swiss Gemological Certificates.",
                'materials' => '18K Solid Yellow Gold & Platinum',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '5.10ct Unheated Burmese Ruby + 1.80ct Shield Diamonds',
                'weight_grams' => 7.80,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/royal-mogok-pigeon-blood-ruby-ring.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / Bespoke Sizing Included', 'size' => '6', 'metal_type' => '18K Yellow Gold', 'stock' => 1],
                ],
                'attributes' => [
                    'Ruby Origin' => 'Mogok, Burma (Myanmar)',
                    'Ruby Weight' => '5.10 ct (No Heat)',
                    'Color Designation' => 'Pigeon Blood Red',
                    'Accent Diamonds' => '1.80ct Shield Cut (D/VVS1)',
                    'Certification' => 'Gübelin & SSEF Gemological Reports',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Muzo Heritage 8.5ct Emerald-Cut Ring',
                'slug' => 'muzo-heritage-emerald-cut-ring',
                'sku' => 'TVA-RNG-008',
                'short_description' => 'An incomparable 8.50-carat untreated Colombian emerald exhibiting lush velvety green fire, set in 18K yellow gold.',
                'description' => "Unearthed from the legendary Muzo mines in Colombia, this 8.50-carat octagonal step-cut emerald embodies the pinnacle of beryl royalty. Showing extraordinary transparency and minimal trace cedar oil, it is flanked by two trilliant-cut diamonds in an architectural 18-karat yellow gold claw mount.\n\nCertified by both GRS and AGL with Classic Muzo Green origin provenance.",
                'materials' => '18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '8.50ct Colombian Emerald (Muzo) + 1.65ct Trilliant Diamonds',
                'weight_grams' => 9.20,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/muzo-heritage-emerald-cut-ring.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 7 / Atelier Bespoke Mount', 'size' => '7', 'metal_type' => '18K Yellow Gold', 'stock' => 1],
                ],
                'attributes' => [
                    'Emerald Origin' => 'Muzo, Colombia',
                    'Emerald Weight' => '8.50 ct',
                    'Clarity Enhancement' => 'Insignificant / None (Rare)',
                    'Trilliant Diamonds' => '1.65ct (E Color / VVS1)',
                    'Certificates' => 'GRS & AGL Provenance Documents',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Vendôme Paraíba Tourmaline & Diamond Ring',
                'slug' => 'vendome-paraiba-tourmaline-diamond-ring',
                'sku' => 'TVA-RNG-009',
                'short_description' => 'An incandescent 4.60-carat Brazilian Paraíba tourmaline enveloped in a double halo of marquise and pear diamonds.',
                'description' => 'One of the rarest geological treasures on Earth. This Brazilian Paraíba tourmaline emits an astonishing, electric neon blue-green luminescence caused by trace copper and manganese. Set in a masterfully hand-pierced platinum under-gallery encrusted with marquise and pear-cut diamonds that radiate like sunbeams.',
                'materials' => '950 Solid Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '4.60ct Brazilian Paraíba Tourmaline + 2.4ct Marquise Diamonds',
                'weight_grams' => 8.10,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/vendome-paraiba-tourmaline-diamond-ring.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6.5 / Platinum', 'size' => '6.5', 'metal_type' => '950 Platinum', 'stock' => 1],
                    ['name' => 'Size 7 / Platinum', 'size' => '7', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Gemstone' => 'Old-Mine Brazilian Paraíba',
                    'Tourmaline Carat' => '4.60 ct',
                    'Diamond Halo' => '2.40ct Total Diamond Weight',
                    'Metal' => 'Hand-Forged 950 Platinum',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Royal Velvet Kashmir Sapphire & Diamond Ring',
                'slug' => 'royal-velvet-kashmir-sapphire-diamond-ring',
                'sku' => 'TVA-RNG-010',
                'short_description' => 'A legendary 6.25-carat untreated Kashmir sapphire boasting intense cornflower royal blue saturation.',
                'description' => 'Mined in the high Himalayas before deposits were exhausted in the late 19th century, Kashmir sapphires represent the holy grail of colored gemstones. This 6.25-carat cushion sapphire demonstrates the celebrated velvety, sleepy blue hue that glows in low light. Framed by two half-moon diamonds on solid 950 platinum.',
                'materials' => '950 Solid Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '6.25ct Untreated Kashmir Sapphire + 1.5ct Half-Moon Diamonds',
                'weight_grams' => 8.90,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/royal-velvet-kashmir-sapphire-diamond-ring.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / Platinum Heirloom Mount', 'size' => '6', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Sapphire Origin' => 'Kashmir, India',
                    'Sapphire Weight' => '6.25 ct (Unheated)',
                    'Side Stones' => '1.50ct Half-Moon Diamonds (D/IF)',
                    'Certificate' => 'SSEF Royal Blue Swiss Certificate',
                ],
            ],
            [
                'category_slug' => 'engagement',
                'name' => 'The Sovereign Emerald Cut Solitaire',
                'slug' => 'sovereign-emerald-cut-solitaire',
                'sku' => 'TVA-ENG-005',
                'short_description' => 'A commanding 5.0-carat step-cut emerald diamond mounted in an architectural platinum setting.',
                'description' => 'Clean parallel lines and an uninterrupted hall-of-mirrors effect define the Sovereign. Master lapidaries cut this 5-carat centerpiece with meticulous symmetry to highlight deep optical clarity. Accented with hidden pavé diamonds along the basket under-gallery.',
                'materials' => '950 Solid Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '5.0ct Emerald Cut Diamond (VVS1 / D Color)',
                'weight_grams' => 7.80,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/sovereign-emerald-cut-solitaire.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / Platinum', 'size' => '6', 'metal_type' => '950 Platinum', 'stock' => 1],
                    ['name' => 'Size 7 / Platinum', 'size' => '7', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Center Stone Carat' => '5.00 ct',
                    'Shape' => 'Emerald Step-Cut',
                    'Color' => 'D (Colorless Perfection)',
                    'Clarity' => 'VVS1',
                ],
            ],

            // --- HIGH VALUED NECKLACES ($59,000 to $735,000) ---
            [
                'category_slug' => 'necklaces',
                'name' => 'Elysian Diamond Collar Pendant',
                'slug' => 'elysian-diamond-collar-pendant',
                'sku' => 'TVA-NCK-002',
                'short_description' => 'A luminous teardrop diamond suspended on a fluid 18K solid white gold box chain.',
                'description' => "Designed to evoke the effortless grace of morning dew, the Elysian Pendant showcases a hand-selected 3.0-carat pear cut diamond. The articulated bail allows subtle fluid motion as you breathe, capturing reflections with mesmerizing depth.\n\nCompleted with our signature TVA filigree lobster clasp and adjustable extension loops.",
                'materials' => '18K Solid White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '3.00ct Pear-Cut Diamond (IF / D Color)',
                'weight_grams' => 8.40,
                'stock_quantity' => 4,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/elysian-diamond-collar-pendant.jpg',
                ],
                'variants' => [
                    ['name' => '42cm Chain Length', 'size' => '42cm', 'metal_type' => '18K White Gold', 'stock' => 2],
                    ['name' => '45cm Chain Length', 'size' => '45cm', 'metal_type' => '18K White Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Diamond Weight' => '3.00 ct',
                    'Chain Length' => 'Adjustable 42cm - 45cm',
                    'Gold Karat' => '18K 750 Hallmark',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The Queen’s Rivière Graduated Diamond Necklace',
                'slug' => 'queens-riviere-graduated-diamond-necklace',
                'sku' => 'TVA-NCK-012',
                'short_description' => 'An imperial continuous line of 92 flawless D-color round brilliant diamonds graduating to a colossal 5.2-carat center stone.',
                'description' => "Crafted over 1,400 hours in our Parisian partner atelier, The Queen's Rivière is an unbroken river of light. Featuring 92 meticulously matched round brilliant diamonds possessing Triple-Excellence cuts, D color, and Internally Flawless clarity. The necklace seamlessly articulates without turning, terminating in a hidden pavé box lock.",
                'materials' => '950 Solid Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '54.50ct Total Diamond Weight (D-E Color, IF-VVS1)',
                'weight_grams' => 48.00,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/queens-riviere-graduated-diamond-necklace.jpg',
                ],
                'variants' => [
                    ['name' => '42cm Choker Length', 'size' => '42cm', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Total Diamond Weight' => '54.50 Carats',
                    'Center Stone Carat' => '5.20 Carats (GIA D/IF)',
                    'Stone Count' => '92 Master-Matched Diamonds',
                    'Clasp' => 'Concealed Safety Lock Mechanism',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The Imperial Muzo Emerald & Diamond Collar',
                'slug' => 'imperial-muzo-emerald-diamond-collar',
                'sku' => 'TVA-NCK-013',
                'short_description' => 'Seven pear-shaped Colombian emerald drops suspended from a flexible lattice of marquise and brilliant diamonds.',
                'description' => "An imperial statement piece recalling royalty. Seven certified Colombian emerald drops totaling 28.5 carats hang gracefully from a fluid collar of alternating marquise and round brilliant diamonds set in solid 18K yellow gold and platinum. The vivid green drops sway in rhythm with the wearer's gait.",
                'materials' => '18K Yellow Gold & 950 Platinum',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '28.50ct Muzo Emerald Drops + 22.00ct Diamonds',
                'weight_grams' => 62.00,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/imperial-muzo-emerald-diamond-collar.jpg',
                ],
                'variants' => [
                    ['name' => '40cm Ergonomic Collar', 'size' => '40cm', 'metal_type' => '18K Gold / Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Emerald Total Weight' => '28.50 Carats',
                    'Diamond Total Weight' => '22.00 Carats',
                    'Emerald Origin' => 'Muzo & Chivor, Colombia',
                    'Artisan Crafting Hours' => '1,120 Hours',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The Grand Ceylon Sapphire Sautoir Necklace',
                'slug' => 'grand-ceylon-sapphire-sautoir-necklace',
                'sku' => 'TVA-NCK-014',
                'short_description' => 'An Art Deco inspired sautoir featuring a detachable 16.5-carat Royal Blue Ceylon sapphire tassel pendant.',
                'description' => 'Exemplifying the opulent glamour of the Roaring Twenties, this sautoir features a mesmerizing 16.5-carat unheated Ceylon sapphire. The pendant detaches gracefully to be worn as a statement brooch. The long sautoir strand is composed of faceted diamond beads and natural South Sea pearls in solid platinum.',
                'materials' => '950 Solid Platinum & 18K White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '16.50ct Royal Ceylon Sapphire + 18.0ct Diamond Beads',
                'weight_grams' => 74.00,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/grand-ceylon-sapphire-sautoir-necklace.jpg',
                ],
                'variants' => [
                    ['name' => '80cm Convertible Sautoir', 'size' => '80cm', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Center Sapphire' => '16.50ct Royal Blue (Unheated)',
                    'Origin' => 'Sri Lanka (Ceylon)',
                    'Versatility' => 'Convertible Pendant / Brooch',
                    'Total Diamond Weight' => '18.00 Carats',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The L’Étoile 6.5ct Heart Diamond Solitaire Pendant',
                'slug' => 'letoile-heart-diamond-solitaire-pendant',
                'sku' => 'TVA-NCK-015',
                'short_description' => 'A romantic 6.50-carat heart-shaped diamond possessing immaculate optical fire on a solid 18K rose gold necklace.',
                'description' => 'The quintessential declaration of romance. An exceptional 6.50-carat heart-shaped diamond is suspended from a bespoke multi-link 18K warm rose gold chain. Cut with deep lobes and sharp cleft to maximize light dispersion, this singular heirloom captures gazes from across the ballroom.',
                'materials' => '18K Solid Rose Gold',
                'metal_color' => 'Rose Gold',
                'gemstone' => '6.50ct Heart Brilliant Diamond (VVS1 / D Color)',
                'weight_grams' => 16.50,
                'stock_quantity' => 2,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/letoile-heart-diamond-solitaire-pendant.jpg',
                ],
                'variants' => [
                    ['name' => '45cm Snake Chain', 'size' => '45cm', 'metal_type' => '18K Rose Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Carat Weight' => '6.50 ct',
                    'Cut' => 'Heart Brilliant',
                    'Color' => 'D (Pure Colorless)',
                    'Clarity' => 'VVS1',
                    'Certification' => 'GIA Dossier & Atelier Passport',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The Solstice 10-Row Diamond Waterfall Collar',
                'slug' => 'solstice-10-row-diamond-waterfall-collar',
                'sku' => 'TVA-NCK-016',
                'short_description' => 'Ten articulated rows of micro-pavé and marquise diamonds designed to drape like liquid light upon the collarbone.',
                'description' => 'Engineered with over 450 micro-hinges, The Solstice Collar contours flawlessly against any neckline. Ten cascading tiers of high-purity marquise and round diamonds taper in descending rhythm, creating a mesmerizing waterfall illusion that glides effortlessly with the body.',
                'materials' => '950 Solid Platinum',
                'metal_color' => 'Platinum',
                'gemstone' => '42.00ct Total Diamond Weight (Colorless Collection)',
                'weight_grams' => 58.00,
                'stock_quantity' => 1,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/solstice-10-row-diamond-waterfall-collar.jpg',
                ],
                'variants' => [
                    ['name' => '42cm Flexible Waterfall Choker', 'size' => '42cm', 'metal_type' => '950 Platinum', 'stock' => 1],
                ],
                'attributes' => [
                    'Total Diamond Weight' => '42.00 Carats',
                    'Structure' => 'Multi-Tiered Hinged Articulation',
                    'Diamond Color' => 'E-F Color',
                    'Diamond Clarity' => 'VVS2+',
                ],
            ],
            [
                'category_slug' => 'earrings',
                'name' => 'Luminary Pavé Huggie Hoops',
                'slug' => 'luminary-pave-huggie-hoops',
                'sku' => 'TVA-ERG-004',
                'short_description' => 'Seamless huggie hoops in 18K warm rose gold adorned with double rows of micro-pavé diamonds.',
                'description' => 'Designed for effortless day-to-evening transition, the Luminary Huggies offer a snug, comfortable curve around the earlobe. The micro-pavé diamonds are microscopically set by hand to ensure seamless radiance with no exposed prongs.',
                'materials' => '18K Rose Gold',
                'metal_color' => 'Rose Gold',
                'gemstone' => '4.50ct Micro-Pavé Diamonds (VVS2 / D-E Color)',
                'weight_grams' => 9.20,
                'stock_quantity' => 4,
                'is_featured' => false,
                'is_new_arrival' => false,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/luminary-pave-huggie-hoops.jpg',
                ],
                'variants' => [
                    ['name' => '14mm Diameter', 'size' => '14mm', 'metal_type' => '18K Rose Gold', 'stock' => 4],
                ],
                'attributes' => [
                    'Diameter' => '14mm',
                    'Width' => '3.5mm',
                    'Closure' => 'Click-Latch Snap Closure',
                ],
            ],

            // --- 7 ACCESSIBLE FINE JEWELRY PIECES (LOWER PRICES: $1,250 to $3,200) ---
            [
                'category_slug' => 'rings',
                'name' => 'The Lumina 18K Bezel Solitaire Diamond Ring',
                'slug' => 'lumina-bezel-solitaire-diamond-ring',
                'sku' => 'TVA-RNG-018',
                'price' => 1850.00,
                'compare_at_price' => 2200.00,
                'short_description' => 'A sleek, modern low-profile solid 18K yellow gold bezel setting cradling a 0.60-carat round brilliant diamond.',
                'description' => "Crafted for effortless daily luxury, The Lumina Solitaire features an ultra-smooth 18K yellow gold protective bezel that won't catch on knits or silks. Designed with a gentle comfort-fit interior and a brilliant round center stone that glistens in natural sunlight.",
                'materials' => '18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '0.60ct Round Brilliant Diamond (VS1 / F Color)',
                'weight_grams' => 3.80,
                'stock_quantity' => 8,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/lumina-bezel-solitaire-diamond-ring.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / 18K Yellow Gold', 'size' => '6', 'metal_type' => '18K Yellow Gold', 'stock' => 3],
                    ['name' => 'Size 7 / 18K Yellow Gold', 'size' => '7', 'metal_type' => '18K Yellow Gold', 'stock' => 3],
                    ['name' => 'Size 8 / 18K Yellow Gold', 'size' => '8', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Carat Weight' => '0.60 ct',
                    'Setting' => 'Full Protective Bezel',
                    'Band Width' => '1.8mm Comfort Fit',
                    'Gold Hallmark' => '18K (750)',
                ],
            ],
            [
                'category_slug' => 'earrings',
                'name' => 'The Petite Étoile Pavé Diamond Huggie Earrings',
                'slug' => 'petite-etoile-pave-diamond-huggies',
                'sku' => 'TVA-ERG-019',
                'price' => 1250.00,
                'compare_at_price' => 1500.00,
                'short_description' => 'Minimalist everyday huggie hoops crafted in 18K warm rose gold with twinkling micro-pavé diamonds.',
                'description' => 'Light as a feather and sculpted for all-day comfort. These petite huggies hug the earlobe snugly, paved with two rows of brilliant micro-cut diamonds. Finished with an audible snap-click closure engineered for active daily wear.',
                'materials' => '18K Solid Rose Gold',
                'metal_color' => 'Rose Gold',
                'gemstone' => '0.45ct Micro-Pavé Diamonds (G/H Color, VS Clarity)',
                'weight_grams' => 2.90,
                'stock_quantity' => 12,
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/petite-etoile-pave-diamond-huggies.jpg',
                ],
                'variants' => [
                    ['name' => '11mm Inner Diameter', 'size' => '11mm', 'metal_type' => '18K Rose Gold', 'stock' => 12],
                ],
                'attributes' => [
                    'Hoop Diameter' => '11mm',
                    'Total Carat Weight' => '0.45 Carats',
                    'Closure' => 'Precision Snap Lock',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The Heritage Compass Diamond Medallion Pendant',
                'slug' => 'heritage-compass-diamond-medallion-pendant',
                'sku' => 'TVA-NCK-020',
                'price' => 2100.00,
                'compare_at_price' => 2500.00,
                'short_description' => 'A satin-finish 18K solid yellow gold compass coin with a starburst-set center diamond on a delicate cable chain.',
                'description' => 'Inspired by classical navigational astrolabes, this tactile medallion pendant features hand-milled fluted edges and an eight-point celestial starburst centered with an eye-clean brilliant diamond. Hangs gracefully on an adjustable 18K gold chain.',
                'materials' => '18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '0.35ct Starburst Solitaire Diamond (G/VS1)',
                'weight_grams' => 6.10,
                'stock_quantity' => 7,
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/heritage-compass-diamond-medallion-pendant.jpg',
                ],
                'variants' => [
                    ['name' => 'Adjustable 40-45cm Chain', 'size' => '40-45cm', 'metal_type' => '18K Yellow Gold', 'stock' => 7],
                ],
                'attributes' => [
                    'Medallion Diameter' => '18mm',
                    'Center Diamond' => '0.35 Carat Starburst Flush Mount',
                    'Chain Type' => 'Diamond-Cut Oval Cable',
                ],
            ],
            [
                'category_slug' => 'bracelets',
                'name' => 'The Arc Riviera Bezel Diamond Station Bracelet',
                'slug' => 'arc-riviera-bezel-diamond-station-bracelet',
                'sku' => 'TVA-BRC-021',
                'price' => 2950.00,
                'compare_at_price' => 3400.00,
                'short_description' => 'Five bezel-set round brilliant diamonds stationed along a durable and fluid 18K white gold trace chain.',
                'description' => 'The epitome of refined wristwear. Five diamond stations are positioned equidistant across the top of the wrist, encased in smooth milgrain-edged bezels that sit flat and never flip. Features two sizing jump rings and an engraved TVA signature tag.',
                'materials' => '18K Solid White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '1.15ct Total Bezel Diamonds (VS2 / G Color)',
                'weight_grams' => 4.80,
                'stock_quantity' => 6,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/arc-riviera-bezel-diamond-station-bracelet.jpg',
                ],
                'variants' => [
                    ['name' => '6.5 to 7.0 Inch Adjustable', 'size' => '6.5-7.0"', 'metal_type' => '18K White Gold', 'stock' => 6],
                ],
                'attributes' => [
                    'Diamond Stations' => '5 Selected Round Diamonds',
                    'Total Carat' => '1.15 Carats',
                    'Clasp' => 'Secure Lobster Clasp with Dual Extension',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Serpentine Stackable Pavé Diamond Band',
                'slug' => 'serpentine-stackable-pave-diamond-band',
                'sku' => 'TVA-RNG-022',
                'price' => 2450.00,
                'compare_at_price' => 2900.00,
                'short_description' => 'A slender ribbon band contoured in solid 18K yellow gold, shimmering with channel-set pavé diamonds.',
                'description' => 'Sculpted with a gentle wave curve, the Serpentine Band is engineered to nestle seamlessly beneath any solitaire or stand alone with understated glamour. Hand-set with a continuous ribbon of 30 brilliant diamonds.',
                'materials' => '18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '0.75ct Channel-Set Diamonds (VS / F-G Color)',
                'weight_grams' => 3.20,
                'stock_quantity' => 10,
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_bestseller' => true,
                'images' => [
                    '/images/products/serpentine-stackable-pave-diamond-band.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / 18K Gold', 'size' => '6', 'metal_type' => '18K Yellow Gold', 'stock' => 4],
                    ['name' => 'Size 7 / 18K Gold', 'size' => '7', 'metal_type' => '18K Yellow Gold', 'stock' => 4],
                    ['name' => 'Size 8 / 18K Gold', 'size' => '8', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                ],
                'attributes' => [
                    'Diamond Count' => '30 Round Diamonds',
                    'Band Width' => '2.0mm',
                    'Profile' => 'Curved Contour Stacking Profile',
                ],
            ],
            [
                'category_slug' => 'necklaces',
                'name' => 'The Solstice Akoya Pearl & Diamond Drop Pendant',
                'slug' => 'solstice-akoya-pearl-diamond-drop-pendant',
                'sku' => 'TVA-NCK-023',
                'price' => 1650.00,
                'compare_at_price' => 1950.00,
                'short_description' => 'A luminous 9mm AAA Japanese Akoya cultured pearl capped with a sparkling diamond bail on an 18K white gold box chain.',
                'description' => 'A harmonious union of oceanic serenity and gemstone brilliance. A hand-selected 9mm Japanese Akoya pearl displaying radiant rose overtones is crowned by a tapered 18K white gold bail set with three graduated round diamonds.',
                'materials' => '18K Solid White Gold',
                'metal_color' => 'White Gold',
                'gemstone' => '9.0mm AAA Japanese Akoya Pearl + 0.20ct Diamond Bail',
                'weight_grams' => 4.50,
                'stock_quantity' => 8,
                'is_featured' => false,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/solstice-akoya-pearl-diamond-drop-pendant.jpg',
                ],
                'variants' => [
                    ['name' => '42cm Chain Length', 'size' => '42cm', 'metal_type' => '18K White Gold', 'stock' => 8],
                ],
                'attributes' => [
                    'Pearl Type' => 'Certified Japanese Akoya Cultured',
                    'Pearl Size' => '9.0mm - 9.5mm AAA Luster',
                    'Bail Accent' => '0.20ct Graduated Diamonds',
                ],
            ],
            [
                'category_slug' => 'rings',
                'name' => 'The Vendôme Sculpted 18K Gold Cigar Band',
                'slug' => 'vendome-sculpted-18k-gold-cigar-band',
                'sku' => 'TVA-RNG-024',
                'price' => 3200.00,
                'compare_at_price' => 3800.00,
                'short_description' => 'A bold, weighted comfort-fit 8mm cigar band in solid 18K yellow gold inset with a flush baguette diamond.',
                'description' => 'Substantial, architectural, and tactile. Forged from thick 18-karat solid yellow gold with beveled edge profiles and a satin-brushed top face. Centered with a sharp, bezel-flush step-cut baguette diamond that delivers subtle architectural sparkle.',
                'materials' => '18K Solid Yellow Gold',
                'metal_color' => 'Yellow Gold',
                'gemstone' => '0.40ct Flush Baguette Diamond (VVS1 / F Color)',
                'weight_grams' => 11.80,
                'stock_quantity' => 5,
                'is_featured' => true,
                'is_new_arrival' => true,
                'is_bestseller' => false,
                'images' => [
                    '/images/products/vendome-sculpted-18k-gold-cigar-band.jpg',
                ],
                'variants' => [
                    ['name' => 'Size 6 / 18K Gold', 'size' => '6', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                    ['name' => 'Size 7 / 18K Gold', 'size' => '7', 'metal_type' => '18K Yellow Gold', 'stock' => 2],
                    ['name' => 'Size 8 / 18K Gold', 'size' => '8', 'metal_type' => '18K Yellow Gold', 'stock' => 1],
                ],
                'attributes' => [
                    'Band Width' => '8.0mm Solid Architecture',
                    'Gold Weight' => 'Approx. 11.80 Grams Solid 18K',
                    'Center Stone' => '0.40ct Baguette Cut Diamond',
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $category = $categories[$pData['category_slug']] ?? null;
            if (! $category) {
                continue;
            }

            $existingProduct = Product::where('sku', $pData['sku'])->first();

            if (isset($pData['price'])) {
                $price = (float) $pData['price'];
                $compareAtPrice = (float) ($pData['compare_at_price'] ?? round($price * 1.2, -2));
                $costPrice = (float) round($price * 0.45, -2);
            } elseif ($existingProduct && $existingProduct->price >= 59000) {
                $price = (float) $existingProduct->price;
                $compareAtPrice = (float) $existingProduct->compare_at_price;
                $costPrice = (float) $existingProduct->cost_price;
            } else {
                $price = $this->randomLuxuryPrice(59000, 735000);
                $compareAtPrice = (float) round($price * (1 + (rand(10, 25) / 100)), -3);
                $costPrice = (float) round($price * 0.45, -3);
            }

            $product = Product::updateOrCreate(
                ['sku' => $pData['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $pData['name'],
                    'slug' => $pData['slug'],
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'price' => $price,
                    'compare_at_price' => $compareAtPrice,
                    'cost_price' => $costPrice,
                    'materials' => $pData['materials'],
                    'metal_color' => $pData['metal_color'],
                    'gemstone' => $pData['gemstone'],
                    'weight_grams' => $pData['weight_grams'],
                    'stock_quantity' => max(2, (int) ($pData['stock_quantity'] ?? 3)),
                    'low_stock_threshold' => 1,
                    'is_featured' => $pData['is_featured'],
                    'is_new_arrival' => $pData['is_new_arrival'],
                    'is_bestseller' => $pData['is_bestseller'],
                    'is_active' => true,
                ]
            );

            // Images (Wipe old image relations and insert distinct unique image)
            ProductImage::where('product_id', $product->id)->delete();
            foreach ($pData['images'] as $index => $imgPath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imgPath,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                    'alt_text' => $product->name,
                ]);
            }

            // Variants
            foreach ($pData['variants'] as $vData) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'name' => $vData['name'],
                    ],
                    [
                        'sku' => $product->sku.'-'.strtoupper(substr(md5($vData['name']), 0, 4)),
                        'size' => $vData['size'] ?? null,
                        'metal_type' => $vData['metal_type'] ?? null,
                        'price' => $product->price,
                        'stock_quantity' => max(2, (int) ($vData['stock'] ?? 3)),
                        'is_active' => true,
                    ]
                );
            }

            // Attributes
            foreach ($pData['attributes'] as $attrKey => $attrVal) {
                ProductAttribute::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'attribute_name' => $attrKey,
                    ],
                    [
                        'attribute_value' => $attrVal,
                    ]
                );
            }

            // Verified Review
            Review::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $customer->id,
                ],
                [
                    'rating' => 5,
                    'title' => 'An incomparable masterpiece of fine jewelry',
                    'comment' => 'Received via TVA Armored Courier in their custom leather and suede vault presentation case. The fire, crystal transparency, and solid gold craftsmanship are truly magnificent.',
                    'is_verified_purchase' => true,
                    'is_approved' => true,
                ]
            );
        }

        $this->call(BridalCollectionSeeder::class);
    }
}
