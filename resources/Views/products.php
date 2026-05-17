<?php
$sec       = \Core\Security\Security::getInstance();
$pageTitle = 'My Products';
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span><span>My Products</span>
    </div>
    <h1>My Product Listings</h1>
    <p>Manage the produce you're offering to hotels</p>
    <div class="page-header-actions">
      <a href="<?= BASE_URL ?>/farmer/products/create" class="btn btn-gold">
        <i class="fas fa-plus"></i> Add New Product
      </a>
    </div>
  </div>
</div>

<div class="container">
  <?php if (empty($products)): ?>
    <div class="empty-state">
      <div class="empty-icon">🌱</div>
      <h3>No products listed yet</h3>
      <p>Start adding your farm produce to reach hotels looking for fresh, local ingredients.</p>
      <a href="<?= BASE_URL ?>/farmer/products/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Your First Product
      </a>
    </div>
  <?php else: ?>
    <div class="card fade-up">
      <div class="card-header">
        <h3><?= count($products) ?> Products</h3>
        <a href="<?= BASE_URL ?>/farmer/products/create" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Add Product
        </a>
      </div>
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Product</th><th>Category</th><th>Price / Unit</th>
              <th>Stock</th><th>Status</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:.75rem;">
                  <?php if ($p['image']): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($p['image']) ?>"
                         class="product-thumb" alt="">
                  <?php else: ?>
                    <div style="width:40px;height:40px;border-radius:8px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🥦</div>
                  <?php endif; ?>
                  <div>
                    <div style="font-weight:600;"><?= $sec->escape($p['name']) ?></div>
                    <div style="font-size:.78rem;color:var(--text-light);">Min order: <?= (int)$p['min_order'] ?> <?= $sec->escape($p['unit']) ?></div>
                  </div>
                </div>
              </td>
              <td><span class="product-category" style="position:static;"><?= $sec->escape($p['category']) ?></span></td>
              <td><strong>KES <?= number_format((float)$p['price_per_unit'], 2) ?></strong> / <?= $sec->escape($p['unit']) ?></td>
              <td>
                <span class="<?= $p['stock_quantity'] < 5 ? 'text-muted' : '' ?>" style="<?= $p['stock_quantity'] < 5 ? 'color:var(--danger);font-weight:600;' : '' ?>">
                  <?= (int)$p['stock_quantity'] ?> <?= $sec->escape($p['unit']) ?>
                </span>
              </td>
              <td>
                <span class="status <?= $p['is_active'] ? 'status-completed' : 'status-cancelled' ?>">
                  <?= $p['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
              </td>
              <td>
                <div style="display:flex;gap:.4rem;">
                  <a href="<?= BASE_URL ?>/farmer/products/<?= (int)$p['id'] ?>/edit"
                     class="btn btn-outline btn-sm">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form method="POST" action="<?= BASE_URL ?>/farmer/products/<?= (int)$p['id'] ?>/delete" style="margin:0;">
                    <?= $sec->csrfField() ?>
                    <button type="submit" class="btn btn-danger btn-sm"
                            data-confirm="Delete &quot;<?= $sec->escape($p['name']) ?>&quot;? This cannot be undone.">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
