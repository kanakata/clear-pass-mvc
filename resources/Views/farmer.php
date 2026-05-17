<?php
$sec       = \Core\Security\Security::getInstance();
$pageTitle = 'Dashboard';
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <span>Home</span><span>/</span><span>Dashboard</span>
    </div>
    <h1>Farmer Dashboard</h1>
    <p>Welcome back, <?= $sec->escape($user['business_name']) ?>! Manage your produce & orders.</p>
    <div class="page-header-actions">
      <a href="<?= BASE_URL ?>/farmer/products/create" class="btn btn-gold">
        <i class="fas fa-plus"></i> Add New Product
      </a>
      <a href="<?= BASE_URL ?>/orders" class="btn btn-outline-white">
        <i class="fas fa-box"></i> View Orders
      </a>
    </div>
  </div>
</div>

<div class="container">
  <div class="stats-grid fade-up">
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-seedling"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= count($rp) ?></div>
        <div class="stat-label">Listed Products</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon gold"><i class="fas fa-money-bill-wave"></i></div>
      <div class="stat-info">
        <div class="stat-value">KES <?= number_format((float)($stats['total_revenue'] ?? 0)) ?></div>
        <div class="stat-label">Total Earned</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-hourglass-half"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= (int)($stats['pending_orders'] ?? 0) ?></div>
        <div class="stat-label">Pending Orders</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= (int)($stats['completed_orders'] ?? 0) ?></div>
        <div class="stat-label">Completed</div>
      </div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="fade-up delay-1">
    <!-- Products Overview -->
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-leaf" style="color:var(--green-600);margin-right:.5rem;"></i> My Products</h3>
        <a href="<?= BASE_URL ?>/farmer/products" class="btn btn-outline btn-sm">Manage</a>
      </div>
      <?php if (empty($rp)): ?>
        <div class="empty-state" style="padding:2rem;">
          <div class="empty-icon">🌱</div>
          <h3>No products listed</h3>
          <a href="<?= BASE_URL ?>/farmer/products/create" class="btn btn-primary btn-sm">Add Product</a>
        </div>
      <?php else: ?>
        <div style="padding:0;">
          <?php foreach (array_slice($rp, 0, 5) as $p): ?>
          <div style="display:flex;align-items:center;gap:1rem;padding:.85rem 1.25rem;border-bottom:1px solid var(--cream-dark);">
            <?php if ($p['image']): ?>
              <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($p['image']) ?>" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
            <?php else: ?>
              <div style="width:40px;height:40px;border-radius:8px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🥦</div>
            <?php endif; ?>
            <div style="flex:1;min-width:0;">
              <div style="font-weight:600;font-size:.875rem;"><?= $sec->escape($p['name']) ?></div>
              <div style="font-size:.78rem;color:var(--text-light);">Stock: <?= (int)$p['stock_quantity'] ?> <?= $sec->escape($p['unit']) ?></div>
            </div>
            <div style="font-weight:700;color:var(--green-600);font-size:.9rem;">KES <?= number_format((float)$p['price_per_unit'], 2) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Recent Orders -->
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-receipt" style="color:var(--green-600);margin-right:.5rem;"></i> Recent Orders</h3>
        <a href="<?= BASE_URL ?>/orders" class="btn btn-outline btn-sm">View All</a>
      </div>
      <?php if (empty($ro)): ?>
        <div class="empty-state" style="padding:2rem;">
          <div class="empty-icon">📭</div>
          <h3>No orders yet</h3>
          <p class="text-sm">Once hotels order your products, they'll appear here.</p>
        </div>
      <?php else: ?>
        <div style="padding:0;">
          <?php foreach (array_slice($ro, 0, 5) as $order): ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.25rem;border-bottom:1px solid var(--cream-dark);">
            <div>
              <div style="font-weight:600;font-size:.875rem;">#<?= (int)$order['id'] ?> · <?= $sec->escape($order['hotel_name']) ?></div>
              <div style="font-size:.78rem;color:var(--text-light);"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
            </div>
            <div style="text-align:right;">
              <div style="font-weight:700;font-size:.875rem;">KES <?= number_format((float)$order['total_amount'], 2) ?></div>
              <span class="status status-<?= $sec->escape($order['status']) ?>"><?= $sec->escape($order['status']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($unread > 0): ?>
  <div class="card fade-up delay-2" style="margin-top:1.5rem;border-left:4px solid var(--green-500);">
    <div class="card-body" style="display:flex;align-items:center;gap:1rem;">
      <div style="font-size:2rem;">✉️</div>
      <div>
        <strong><?= $unread ?> unread message<?= $unread > 1 ? 's' : '' ?> from hotels</strong>
        <p class="text-muted text-sm">Respond promptly to secure deals.</p>
      </div>
      <a href="<?= BASE_URL ?>/messages" class="btn btn-primary" style="margin-left:auto;">Open Messages</a>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
