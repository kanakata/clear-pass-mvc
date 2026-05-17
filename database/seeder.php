<?php

/**
 * AgriHotel Connect — Database Seeder
 * Run: php database/seeder.php
 *
 * Creates all tables and inserts realistic demo data
 * with properly hashed passwords.
 */

define('ROOT', dirname(__DIR__));

$cfg = require ROOT . '/config/database.php';
$dsn = "mysql:host={$cfg['host']};port={$cfg['port']};charset={$cfg['charset']}";

try {
    $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✓ Connected to MySQL\n";
} catch (PDOException $e) {
    die("✗ Connection failed: " . $e->getMessage() . "\n");
}

// Create DB if missing
$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$cfg['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `{$cfg['dbname']}`");
echo "✓ Database `{$cfg['dbname']}` ready\n";

// Run schema
$schema = file_get_contents(ROOT . '/database/schema_clean.sql');
foreach (array_filter(array_map('trim', explode(';', $schema))) as $stmt) {
    if ($stmt) $pdo->exec($stmt);
}
echo "✓ Schema applied\n";

// ── Password hashes ───────────────────────────────────────
$adminPwd  = password_hash('Admin@1234',  PASSWORD_ARGON2ID, ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 1]);
$hotelPwd  = password_hash('Hotel@1234',  PASSWORD_ARGON2ID, ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 1]);
$farmerPwd = password_hash('Farmer@1234', PASSWORD_ARGON2ID, ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 1]);

// ── Clear existing demo data ──────────────────────────────
$pdo->exec("DELETE FROM order_items");
$pdo->exec("DELETE FROM orders");
$pdo->exec("DELETE FROM messages");
$pdo->exec("DELETE FROM products");
$pdo->exec("DELETE FROM users");
$pdo->exec("ALTER TABLE users      AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE products   AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE orders     AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE order_items AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE messages   AUTO_INCREMENT = 1");
echo "✓ Tables cleared\n";

// ── Users ────────────────────────────────────────────────
$users = [
    // Admin
    [
        'email' => 'admin@agrihotel.co.ke',
        'password' => $adminPwd,
        'business_name' => 'AgriHotel Admin',
        'role' => 'admin',
        'phone' => '+254 700 000000',
        'location' => 'Nairobi, Kenya',
        'bio' => 'Platform administrator.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-90 days')),
    ],
    // Hotels
    [
        'email' => 'serena@hotel.co.ke',
        'password' => $hotelPwd,
        'business_name' => 'Serena Hotel Nairobi',
        'role' => 'hotel',
        'phone' => '+254 720 100001',
        'location' => 'Nairobi, Kenya',
        'bio' => '5-star hotel committed to sourcing fresh, local produce for our world-class kitchen.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-80 days')),
    ],
    [
        'email' => 'fairmont@hotel.co.ke',
        'password' => $hotelPwd,
        'business_name' => 'Fairmont The Norfolk',
        'role' => 'hotel',
        'phone' => '+254 720 100002',
        'location' => 'Nairobi, Kenya',
        'bio' => 'Historic luxury hotel with a passion for authentic, farm-to-table dining experiences.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-75 days')),
    ],
    [
        'email' => 'safari@lodge.co.ke',
        'password' => $hotelPwd,
        'business_name' => 'Ol Pejeta Safari Lodge',
        'role' => 'hotel',
        'phone' => '+254 720 100003',
        'location' => 'Laikipia, Kenya',
        'bio' => 'Eco-lodge that prides itself on supporting local communities through direct farm sourcing.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-70 days')),
    ],
    // Farmers
    [
        'email' => 'kiambu@farm.co.ke',
        'password' => $farmerPwd,
        'business_name' => 'Kiambu Fresh Farms',
        'role' => 'farmer',
        'phone' => '+254 722 200001',
        'location' => 'Kiambu, Kenya',
        'bio' => 'Family-run organic farm with 20+ years growing premium vegetables and herbs. GAP certified.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-85 days')),
    ],
    [
        'email' => 'nakuru@dairy.co.ke',
        'password' => $farmerPwd,
        'business_name' => 'Nakuru Valley Dairy',
        'role' => 'farmer',
        'phone' => '+254 722 200002',
        'location' => 'Nakuru, Kenya',
        'bio' => 'Pasture-raised dairy farm supplying fresh milk, yoghurt and artisan cheese to premium hotels.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-78 days')),
    ],
    [
        'email' => 'meru@organics.co.ke',
        'password' => $farmerPwd,
        'business_name' => 'Meru Organic Orchards',
        'role' => 'farmer',
        'phone' => '+254 722 200003',
        'location' => 'Meru, Kenya',
        'bio' => 'Specialising in tropical and exotic fruits. All produce grown without synthetic pesticides.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-65 days')),
    ],
    [
        'email' => 'eldoret@grains.co.ke',
        'password' => $farmerPwd,
        'business_name' => 'Eldoret Grain Cooperative',
        'role' => 'farmer',
        'phone' => '+254 722 200004',
        'location' => 'Eldoret, Kenya',
        'bio' => 'Cooperative of 35 smallholder farmers supplying premium grains, maize and wheat flour.',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-55 days')),
    ],
];

$userStmt = $pdo->prepare(
    "INSERT INTO users (email,password,business_name,role,phone,location,bio,is_active,created_at)
     VALUES (:email,:password,:business_name,:role,:phone,:location,:bio,:is_active,:created_at)"
);
$userIds = [];
foreach ($users as $u) {
    $userStmt->execute($u);
    $userIds[$u['email']] = (int) $pdo->lastInsertId();
}
echo "✓ " . count($users) . " users seeded\n";

// Aliases
$adminId   = $userIds['admin@agrihotel.co.ke'];
$serenaId  = $userIds['serena@hotel.co.ke'];
$fairmontId = $userIds['fairmont@hotel.co.ke'];
$safariId  = $userIds['safari@lodge.co.ke'];
$kiambuId  = $userIds['kiambu@farm.co.ke'];
$nakuruId  = $userIds['nakuru@dairy.co.ke'];
$meruId    = $userIds['meru@organics.co.ke'];
$eldoretId = $userIds['eldoret@grains.co.ke'];

// ── Products ─────────────────────────────────────────────
$products = [
    // Kiambu Fresh Farms
    ['farmer_id' => $kiambuId, 'name' => 'Organic Tomatoes',      'category' => 'vegetables', 'price_per_unit' => 85.00,  'stock_quantity' => 500, 'unit' => 'kg',  'min_order' => 10, 'description' => 'Sun-ripened, organic tomatoes grown without synthetic pesticides. Perfect for soups, sauces and salads. Harvested twice weekly for maximum freshness.'],
    ['farmer_id' => $kiambuId, 'name' => 'French Beans',           'category' => 'vegetables', 'price_per_unit' => 120.00, 'stock_quantity' => 200, 'unit' => 'kg',  'min_order' => 5,  'description' => 'Tender, extra-fine French beans. Export quality. Hand-picked daily and available year-round.'],
    ['farmer_id' => $kiambuId, 'name' => 'Baby Spinach',           'category' => 'vegetables', 'price_per_unit' => 95.00,  'stock_quantity' => 150, 'unit' => 'kg',  'min_order' => 3,  'description' => 'Crisp, nutrient-rich baby spinach leaves. Triple-washed and ready to plate. Ideal for salads and smoothies.'],
    ['farmer_id' => $kiambuId, 'name' => 'Fresh Basil Bunches',    'category' => 'herbs',      'price_per_unit' => 50.00,  'stock_quantity' => 300, 'unit' => 'bunch', 'min_order' => 10, 'description' => 'Aromatic sweet basil, freshly cut each morning. Grown under shade nets to maintain tender leaves and intense aroma.'],
    ['farmer_id' => $kiambuId, 'name' => 'Broccoli Heads',         'category' => 'vegetables', 'price_per_unit' => 110.00, 'stock_quantity' => 180, 'unit' => 'kg',  'min_order' => 5,  'description' => 'Premium broccoli crowns with tight florets. Grown in the cool Kiambu highlands for superior quality.'],

    // Nakuru Valley Dairy
    ['farmer_id' => $nakuruId, 'name' => 'Fresh Whole Milk',       'category' => 'dairy',      'price_per_unit' => 65.00,  'stock_quantity' => 1000, 'unit' => 'litre', 'min_order' => 20, 'description' => 'Farm-fresh whole milk from pasture-raised Friesian cows. Tested daily for quality. Delivered chilled within 4 hours of milking.'],
    ['farmer_id' => $nakuruId, 'name' => 'Artisan Cheddar Cheese', 'category' => 'dairy',      'price_per_unit' => 850.00, 'stock_quantity' => 80,  'unit' => 'kg',  'min_order' => 2,  'description' => 'Aged 6-month cheddar made from our own farm milk. Rich, complex flavour with a firm texture. Perfect for cheese boards and cooking.'],
    ['farmer_id' => $nakuruId, 'name' => 'Greek-Style Yoghurt',    'category' => 'dairy',      'price_per_unit' => 180.00, 'stock_quantity' => 200, 'unit' => 'kg',  'min_order' => 5,  'description' => 'Thick, creamy Greek yoghurt. High protein, no additives or preservatives. Available in plain and honey variants.'],
    ['farmer_id' => $nakuruId, 'name' => 'Free-Range Eggs',        'category' => 'eggs',       'price_per_unit' => 18.00,  'stock_quantity' => 500, 'unit' => 'piece', 'min_order' => 30, 'description' => 'Large, golden-yolk eggs from free-range hens. Rich in omega-3 and naturally vibrant. Collected twice daily.'],

    // Meru Organic Orchards
    ['farmer_id' => $meruId,   'name' => 'Avocado Hass',           'category' => 'fruits',     'price_per_unit' => 45.00,  'stock_quantity' => 400, 'unit' => 'piece', 'min_order' => 24, 'description' => 'Premium Hass avocados from Meru highlands. Creamy, buttery flesh with superior shelf life. Ready-to-ripen delivered or ripe on request.'],
    ['farmer_id' => $meruId,   'name' => 'Passion Fruits',         'category' => 'fruits',     'price_per_unit' => 12.00,  'stock_quantity' => 600, 'unit' => 'piece', 'min_order' => 50, 'description' => 'Intensely fragrant purple passion fruits bursting with juice. Perfect for cocktails, desserts and fresh juices.'],
    ['farmer_id' => $meruId,   'name' => 'Pink Lady Apples',       'category' => 'fruits',     'price_per_unit' => 90.00,  'stock_quantity' => 250, 'unit' => 'kg',  'min_order' => 10, 'description' => 'Crisp, sweet-tart Pink Lady apples grown at altitude for superior flavour. Excellent for dessert platters and apple crumbles.'],
    ['farmer_id' => $meruId,   'name' => 'Fresh Strawberries',     'category' => 'fruits',     'price_per_unit' => 350.00, 'stock_quantity' => 100, 'unit' => 'kg',  'min_order' => 2,  'description' => 'Sweet, plump strawberries harvested at peak ripeness. No cold storage — field to kitchen within 12 hours. Limited seasonal availability.'],

    // Eldoret Grain Cooperative
    ['farmer_id' => $eldoretId, 'name' => 'Whole Wheat Flour',      'category' => 'grains',     'price_per_unit' => 65.00,  'stock_quantity' => 2000, 'unit' => 'kg',  'min_order' => 25, 'description' => 'Stone-ground whole wheat flour from our cooperative. High fibre, rich in nutrients. Ideal for artisan breads and pastries.'],
    ['farmer_id' => $eldoretId, 'name' => 'White Maize Meal',       'category' => 'grains',     'price_per_unit' => 50.00,  'stock_quantity' => 3000, 'unit' => 'kg',  'min_order' => 50, 'description' => 'Finely milled white maize meal (ugali flour). Consistent grind, no additives. Supplied in 10kg, 25kg and 50kg bags.'],
    ['farmer_id' => $eldoretId, 'name' => 'Basmati Rice',           'category' => 'grains',     'price_per_unit' => 140.00, 'stock_quantity' => 800, 'unit' => 'kg',  'min_order' => 20, 'description' => 'Long-grain aromatic basmati rice grown in the Rift Valley. Aged 12 months for superior aroma and non-sticky texture.'],
    ['farmer_id' => $eldoretId, 'name' => 'Dry Red Kidney Beans',   'category' => 'grains',     'price_per_unit' => 95.00,  'stock_quantity' => 600, 'unit' => 'kg',  'min_order' => 10, 'description' => 'Large, meaty kidney beans. High protein, no artificial treatment. Soaking time 8 hours for best results.'],
];

$pStmt = $pdo->prepare(
    "INSERT INTO products (farmer_id,name,category,price_per_unit,stock_quantity,unit,min_order,description,is_active,created_at)
     VALUES (:farmer_id,:name,:category,:price_per_unit,:stock_quantity,:unit,:min_order,:description,1,:created_at)"
);
$productIds = [];
foreach ($products as $i => $p) {
    $p['created_at'] = date('Y-m-d H:i:s', strtotime('-' . (60 - $i * 3) . ' days'));
    $pStmt->execute($p);
    $productIds[] = (int) $pdo->lastInsertId();
}
echo "✓ " . count($products) . " products seeded\n";

// ── Orders & Items ────────────────────────────────────────
$orderData = [
    ['hotel_id' => $serenaId,  'farmer_id' => $kiambuId, 'prod_idx' => 0, 'qty' => 50,  'status' => 'completed', 'days' => 45],
    ['hotel_id' => $serenaId,  'farmer_id' => $nakuruId, 'prod_idx' => 5, 'qty' => 100, 'status' => 'completed', 'days' => 40],
    ['hotel_id' => $fairmontId, 'farmer_id' => $kiambuId, 'prod_idx' => 1, 'qty' => 30,  'status' => 'shipped',   'days' => 10],
    ['hotel_id' => $fairmontId, 'farmer_id' => $meruId,   'prod_idx' => 9, 'qty' => 48,  'status' => 'confirmed', 'days' => 5],
    ['hotel_id' => $safariId,  'farmer_id' => $eldoretId, 'prod_idx' => 14, 'qty' => 100, 'status' => 'processing', 'days' => 7],
    ['hotel_id' => $safariId,  'farmer_id' => $nakuruId, 'prod_idx' => 6, 'qty' => 5,   'status' => 'pending',   'days' => 2],
    ['hotel_id' => $serenaId,  'farmer_id' => $meruId,   'prod_idx' => 10, 'qty' => 96,  'status' => 'completed', 'days' => 30],
    ['hotel_id' => $fairmontId, 'farmer_id' => $eldoretId, 'prod_idx' => 15, 'qty' => 200, 'status' => 'completed', 'days' => 25],
    ['hotel_id' => $serenaId,  'farmer_id' => $kiambuId, 'prod_idx' => 2, 'qty' => 20,  'status' => 'pending',   'days' => 1],
    ['hotel_id' => $safariId,  'farmer_id' => $meruId,   'prod_idx' => 11, 'qty' => 100, 'status' => 'shipped',   'days' => 8],
];

$oStmt = $pdo->prepare(
    "INSERT INTO orders (hotel_id,farmer_id,total_amount,status,notes,created_at,updated_at)
     VALUES (?,?,?,?,?,?,?)"
);
$iStmt = $pdo->prepare(
    "INSERT INTO order_items (order_id,product_id,quantity,unit_price,subtotal)
     VALUES (?,?,?,?,?)"
);

foreach ($orderData as $od) {
    $prodId    = $productIds[$od['prod_idx']];
    $unitPrice = $products[$od['prod_idx']]['price_per_unit'];
    $subtotal  = $od['qty'] * $unitPrice;
    $created   = date('Y-m-d H:i:s', strtotime('-' . $od['days'] . ' days'));

    $oStmt->execute([
        $od['hotel_id'],
        $od['farmer_id'],
        $subtotal,
        $od['status'],
        'Standard delivery to hotel receiving bay.',
        $created,
        $created
    ]);
    $orderId = (int) $pdo->lastInsertId();
    $iStmt->execute([$orderId, $prodId, $od['qty'], $unitPrice, $subtotal]);
}
echo "✓ " . count($orderData) . " orders seeded\n";

// ── Messages ─────────────────────────────────────────────
$msgs = [
    [$serenaId,  $kiambuId, 'Tomato order enquiry',       'Hi! We are interested in placing a weekly standing order for your organic tomatoes. Could you confirm availability for 50kg every Monday?', 0, '-20 days'],
    [$kiambuId,  $serenaId, 'Re: Tomato order enquiry',   'Thank you for reaching out! We can absolutely accommodate a weekly order of 50kg. We harvest on Sundays so delivery Monday morning works perfectly.', 1, '-19 days'],
    [$fairmontId, $nakuruId, 'Dairy products interest',    'Hello, we are revamping our breakfast menu and would love to feature your artisan cheddar. Can you send us a sample and a pricelist?', 0, '-15 days'],
    [$nakuruId, $fairmontId, 'Re: Dairy products interest', 'Delighted to hear that! I will arrange a complimentary sample box of our cheddar and yoghurt for your chef to assess. Expect delivery by end of week.', 1, '-14 days'],
    [$safariId,  $meruId,   'Avocado bulk order',         'We host large events and regularly need 200+ avocados. What is your capacity and lead time for bulk orders?', 0, '-5 days'],
    [$serenaId,  $eldoretId, 'Grain supply enquiry',       'We are looking for a reliable supplier for our kitchen. Your basmati rice looks excellent. What are your delivery terms?', 0, '-3 days'],
];

$mStmt = $pdo->prepare(
    "INSERT INTO messages (sender_id,recipient_id,subject,body,is_read,created_at)
     VALUES (?,?,?,?,?,?)"
);
foreach ($msgs as $m) {
    $mStmt->execute([$m[0], $m[1], $m[2], $m[3], $m[4], date('Y-m-d H:i:s', strtotime($m[5]))]);
}
echo "✓ " . count($msgs) . " messages seeded\n";

echo "\n";
echo "══════════════════════════════════════════════\n";
echo "  AgriHotel Connect — Seeding Complete! 🌾\n";
echo "══════════════════════════════════════════════\n\n";
echo "  Demo Accounts (all roles):\n\n";
echo "  ADMIN\n";
echo "    Email   : admin@agrihotel.co.ke\n";
echo "    Password: Admin@1234\n\n";
echo "  HOTEL (Serena)\n";
echo "    Email   : serena@hotel.co.ke\n";
echo "    Password: Hotel@1234\n\n";
echo "  HOTEL (Fairmont)\n";
echo "    Email   : fairmont@hotel.co.ke\n";
echo "    Password: Hotel@1234\n\n";
echo "  FARMER (Kiambu)\n";
echo "    Email   : kiambu@farm.co.ke\n";
echo "    Password: Farmer@1234\n\n";
echo "  FARMER (Nakuru Dairy)\n";
echo "    Email   : nakuru@dairy.co.ke\n";
echo "    Password: Farmer@1234\n\n";
echo "══════════════════════════════════════════════\n";
