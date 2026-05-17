<?php
$sec       = \Core\Security\Security::getInstance();
$pageTitle = $product['name'];
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span>
      <a href="<?= BASE_URL ?>/marketplace">Marketplace</a><span>/</span>
      <span><?= $sec->escape($product['name']) ?></span>
    </div>
    <h1><?= $sec->escape($product['name']) ?></h1>
    <p>Sold by <?= $sec->escape($farmer['business_name']) ?></p>
  </div>
</div>

<div class="container">
  <div class="order-detail-grid">
    <!-- Product Info -->
    <div>
      <div class="card fade-up">
        <?php if ($product['image']): ?>
          <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($product['image']) ?>"
               alt="<?= $sec->escape($product['name']) ?>"
               style="width:100%;height:340px;object-fit:cover;">
        <?php else: ?>
          <div style="height:280px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:6rem;">
            🥦
          </div>
        <?php endif; ?>
        <div class="card-body">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <span class="product-category" style="position:static;"><?= $sec->escape($product['category']) ?></span>
            <span class="status <?= $product['stock_quantity'] > 0 ? 'status-completed' : 'status-cancelled' ?>">
              <?= $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock' ?>
            </span>
          </div>
          <h2 style="font-size:1.6rem;margin-bottom:.75rem;"><?= $sec->escape($product['name']) ?></h2>
          <p style="color:var(--text-mid);line-height:1.7;"><?= nl2br($sec->escape($product['description'])) ?></p>

          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--cream-dark);">
            <div>
              <div style="font-size:.75rem;color:var(--text-light);text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Unit</div>
              <div style="font-weight:700;margin-top:.25rem;"><?= $sec->escape($product['unit']) ?></div>
            </div>
            <div>
              <div style="font-size:.75rem;color:var(--text-light);text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Min. Order</div>
              <div style="font-weight:700;margin-top:.25rem;"><?= (int)$product['min_order'] ?> <?= $sec->escape($product['unit']) ?></div>
            </div>
            <div>
              <div style="font-size:.75rem;color:var(--text-light);text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Available</div>
              <div style="font-weight:700;margin-top:.25rem;"><?= (int)$product['stock_quantity'] ?> <?= $sec->escape($product['unit']) ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Farmer Info -->
      <div class="card fade-up delay-1" style="margin-top:1.5rem;">
        <div class="card-header"><h3>About the Farmer</h3></div>
        <div class="card-body">
          <div style="display:flex;align-items:center;gap:1rem;">
            <?php if ($farmer['avatar']): ?>
              <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($farmer['avatar']) ?>"
                   style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid var(--green-300);">
            <?php else: ?>
              <div style="width:60px;height:60px;border-radius:50%;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🌾</div>
            <?php endif; ?>
            <div>
              <div style="font-weight:700;font-size:1rem;"><?= $sec->escape($farmer['business_name']) ?></div>
              <?php if ($farmer['location']): ?>
                <div style="color:var(--text-light);font-size:.875rem;">
                  <i class="fas fa-map-marker-alt"></i> <?= $sec->escape($farmer['location']) ?>
                </div>
              <?php endif; ?>
              <?php if ($farmer['phone']): ?>
                <div style="color:var(--text-light);font-size:.875rem;">
                  <i class="fas fa-phone"></i> <?= $sec->escape($farmer['phone']) ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <?php if ($farmer['bio']): ?>
            <p style="margin-top:1rem;color:var(--text-mid);font-size:.875rem;"><?= $sec->escape($farmer['bio']) ?></p>
          <?php endif; ?>
          <?php if ($user['role'] === 'hotel'): ?>
            <a href="<?= BASE_URL ?>/messages?to=<?= (int)$farmer['id'] ?>"
               class="btn btn-outline btn-sm" style="margin-top:1rem;">
              <i class="fas fa-envelope"></i> Message Farmer
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Order Form -->
    <div class="fade-up delay-2">
      <div class="card" style="position:sticky;top:calc(var(--nav-h) + 1.5rem);">
        <div class="card-header" style="background:var(--green-900);">
          <h3 style="color:var(--white);">Place an Order</h3>
          <div style="font-size:1.5rem;color:var(--gold-400);font-family:var(--font-display);font-weight:700;">
            KES <?= number_format((float)$product['price_per_unit'], 2) ?>
            <span style="font-size:.8rem;color:rgba(255,255,255,.6);font-family:var(--font-body);font-weight:400;">
              / <?= $sec->escape($product['unit']) ?>
            </span>
          </div>
        </div>
        <div class="card-body">
          <?php if ($user['role'] === 'hotel' && $product['stock_quantity'] > 0): ?>
            <form method="POST" action="<?= BASE_URL ?>/orders/place">
              <?= $sec->csrfField() ?>
              <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
              <input type="hidden" id="productPrice" value="<?= (float)$product['price_per_unit'] ?>">

              <div class="form-group">
                <label class="form-label" for="orderQty">
                  Quantity (<?= $sec->escape($product['unit']) ?>)
                </label>
                <input type="number" id="orderQty" name="quantity" class="form-control"
                       min="<?= (int)$product['min_order'] ?>"
                       max="<?= (int)$product['stock_quantity'] ?>"
                       value="<?= (int)$product['min_order'] ?>"
                       required>
                <div class="text-sm text-muted mt-1">
                  Minimum: <?= (int)$product['min_order'] ?> · Maximum: <?= (int)$product['stock_quantity'] ?>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="notes">Special Notes (optional)</label>
                <textarea id="notes" name="notes" class="form-control" rows="3"
                          placeholder="Delivery instructions, specific requirements…"></textarea>
              </div>

              <div style="background:var(--green-50);border-radius:var(--radius-md);padding:1rem;margin-bottom:1.25rem;">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;color:var(--text-mid);">
                  <span>Estimated Total</span>
                  <span id="orderTotal" style="font-weight:700;color:var(--green-600);font-size:1.1rem;">
                    KES <?= number_format((float)$product['price_per_unit'] * $product['min_order'], 2) ?>
                  </span>
                </div>
              </div>

              <button type="submit" class="btn btn-gold w-full btn-lg">
                <i class="fas fa-shopping-cart"></i> Place Order
              </button>
            </form>
          <?php elseif ($user['role'] === 'farmer'): ?>
            <div class="empty-state" style="padding:1.5rem 0;">
              <div class="empty-icon" style="font-size:2rem;">ℹ️</div>
              <p>This is a farmer listing view. Switch to a hotel account to place orders.</p>
            </div>
          <?php else: ?>
            <div style="text-align:center;padding:1rem 0;">
              <div style="font-size:2rem;margin-bottom:.75rem;">😔</div>
              <p style="color:var(--text-mid);">This product is currently out of stock.</p>
              <a href="<?= BASE_URL ?>/messages?to=<?= (int)$farmer['id'] ?>"
                 class="btn btn-outline btn-sm" style="margin-top:.75rem;">
                <i class="fas fa-bell"></i> Notify Farmer
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top:1.5rem;">
    <a href="<?= BASE_URL ?>/marketplace" class="btn btn-outline">
      <i class="fas fa-arrow-left"></i> Back to Marketplace
    </a>
  </div>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
