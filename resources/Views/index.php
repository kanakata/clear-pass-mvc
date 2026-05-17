<?php
$sec       = \Core\Security\Security::getInstance();
$pageTitle = 'Marketplace';
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span><span>Marketplace</span>
    </div>
    <h1>Fresh Produce Marketplace</h1>
    <p><?= number_format($total) ?> products from local farmers across Kenya</p>
  </div>
</div>

<div class="container">
  <!-- FILTERS -->
  <form method="GET" action="<?= BASE_URL ?>/marketplace">
    <div class="filter-bar fade-up">
      <div class="form-group">
        <label class="form-label">Search</label>
        <div class="input-icon">
          <i class="fas fa-search icon"></i>
          <input type="text" name="search" class="form-control"
                 placeholder="Search produce…"
                 value="<?= $sec->escape($filters['search'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Category</label>
        <select name="category" class="form-control">
          <option value="">All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $sec->escape($cat['category']) ?>"
              <?= ($filters['category'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
              <?= $sec->escape(ucfirst($cat['category'])) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Min Price (KES)</label>
        <input type="number" name="min_price" class="form-control" placeholder="0"
               value="<?= $sec->escape($filters['min_price'] ?? '') ?>" min="0">
      </div>
      <div class="form-group">
        <label class="form-label">Max Price (KES)</label>
        <input type="number" name="max_price" class="form-control" placeholder="Any"
               value="<?= $sec->escape($filters['max_price'] ?? '') ?>" min="0">
      </div>
      <div class="form-group" style="align-self:flex-end;">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-filter"></i> Filter
        </button>
        <a href="<?= BASE_URL ?>/marketplace" class="btn btn-outline" style="margin-left:.5rem;">
          <i class="fas fa-times"></i> Clear
        </a>
      </div>
    </div>
  </form>

  <!-- GRID -->
  <?php if (empty($products)): ?>
    <div class="empty-state">
      <div class="empty-icon">🔍</div>
      <h3>No products found</h3>
      <p>Try adjusting your filters or check back later for new listings.</p>
      <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary">Clear Filters</a>
    </div>
  <?php else: ?>
    <div class="product-grid">
      <?php foreach ($products as $i => $product): ?>
      <div class="product-card fade-up" style="animation-delay: <?= min($i * 0.05, 0.4) ?>s;">
        <a href="<?= BASE_URL ?>/marketplace/<?= (int)$product['id'] ?>">
          <div class="product-img-wrap">
            <?php if ($product['image']): ?>
              <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($product['image']) ?>"
                   alt="<?= $sec->escape($product['name']) ?>" loading="lazy">
            <?php else: ?>
              <div class="product-img-placeholder">
                <?php
                $icons = ['vegetables'=>'🥦','fruits'=>'🍎','grains'=>'🌾','dairy'=>'🥛','meat'=>'🥩','herbs'=>'🌿'];
                echo $icons[strtolower($product['category'])] ?? '🛒';
                ?>
              </div>
            <?php endif; ?>
            <span class="product-category"><?= $sec->escape($product['category']) ?></span>
          </div>
        </a>
        <div class="product-body">
          <a href="<?= BASE_URL ?>/marketplace/<?= (int)$product['id'] ?>">
            <div class="product-name"><?= $sec->escape($product['name']) ?></div>
          </a>
          <div class="product-farmer">
            <i class="fas fa-map-marker-alt"></i>
            <?= $sec->escape($product['farmer_name']) ?>
            <?php if ($product['farmer_location']): ?>
              · <?= $sec->escape($product['farmer_location']) ?>
            <?php endif; ?>
          </div>
          <div class="product-price">
            KES <?= number_format((float)$product['price_per_unit'], 2) ?>
            <span>/ <?= $sec->escape($product['unit']) ?></span>
          </div>
          <div class="product-stock <?= $product['stock_quantity'] < 10 ? 'low' : '' ?>">
            <?php if ($product['stock_quantity'] < 1): ?>
              <i class="fas fa-times-circle"></i> Out of stock
            <?php elseif ($product['stock_quantity'] < 10): ?>
              <i class="fas fa-exclamation-circle"></i> Low stock: <?= (int)$product['stock_quantity'] ?> left
            <?php else: ?>
              <i class="fas fa-check-circle" style="color:var(--success);"></i> <?= (int)$product['stock_quantity'] ?> <?= $sec->escape($product['unit']) ?> available
            <?php endif; ?>
          </div>
          <?php if ($user['role'] === 'hotel'): ?>
          <div class="product-actions">
            <a href="<?= BASE_URL ?>/marketplace/<?= (int)$product['id'] ?>" class="btn btn-primary btn-sm" style="flex:1;">
              <i class="fas fa-shopping-cart"></i> Order Now
            </a>
            <a href="<?= BASE_URL ?>/messages?to=<?= (int)$product['farmer_id'] ?>" class="btn btn-outline btn-sm">
              <i class="fas fa-comment"></i>
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- PAGINATION -->
    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?<?= http_build_query(array_merge($filters, ['page' => $page - 1])) ?>" class="page-btn">
          <i class="fas fa-chevron-left"></i>
        </a>
      <?php else: ?>
        <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
      <?php endif; ?>

      <?php for ($p = max(1, $page - 2); $p <= min($pages, $page + 2); $p++): ?>
        <a href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>"
           class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor; ?>

      <?php if ($page < $pages): ?>
        <a href="?<?= http_build_query(array_merge($filters, ['page' => $page + 1])) ?>" class="page-btn">
          <i class="fas fa-chevron-right"></i>
        </a>
      <?php else: ?>
        <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
