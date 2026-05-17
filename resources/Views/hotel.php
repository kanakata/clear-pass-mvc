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
    <h1>Hotel Dashboard</h1>
    <p>Welcome back, <?= $sec->escape($user['business_name']) ?>! Here's your overview.</p>
    <div class="page-header-actions">
      <a href="<?= BASE_URL ?>/marketplace" class="btn btn-gold">
        <i class="fas fa-store"></i> Browse Marketplace
      </a>
      <a href="<?= BASE_URL ?>/orders" class="btn btn-outline-white">
        <i class="fas fa-box"></i> View Orders
      </a>
    </div>
  </div>
</div>

<div class="container">
  <!-- STATS -->
  <div class="stats-grid fade-up">
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-shopping-bag"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= (int)($stats['total_orders'] ?? 0) ?></div>
        <div class="stat-label">Total Orders</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon gold"><i class="fas fa-coins"></i></div>
      <div class="stat-info">
        <div class="stat-value">KES <?= number_format((float)($stats['total_revenue'] ?? 0)) ?></div>
        <div class="stat-label">Total Spent</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-clock"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= (int)($stats['pending_orders'] ?? 0) ?></div>
        <div class="stat-label">Pending Orders</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
      <div class="stat-info">
        <div class="stat-value"><?= (int)($stats['completed_orders'] ?? 0) ?></div>
        <div class="stat-label">Completed</div>
      </div>
    </div>
  </div>

  <!-- Welcome Banner -->
  <div class="welcome-banner fade-up delay-1">
    <h2>Find Fresh, Local Produce 🌿</h2>
    <p>Browse hundreds of listings from verified local farmers. Place orders directly and track delivery in real-time.</p>
    <a href="<?= BASE_URL ?>/marketplace" class="btn btn-gold" style="margin-top:1.25rem;">
      <i class="fas fa-search"></i> Explore the Marketplace
    </a>
  </div>

  <!-- Recent Orders -->
  <div class="card fade-up delay-2">
    <div class="card-header">
      <h3><i class="fas fa-history" style="color:var(--green-600);margin-right:.5rem;"></i> Recent Orders</h3>
      <a href="<?= BASE_URL ?>/orders" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div class="table-wrap">
      <?php if (empty($ro)): ?>
        <div class="empty-state">
          <div class="empty-icon">📦</div>
          <h3>No orders yet</h3>
          <p>Browse the marketplace and place your first order.</p>
          <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary">Go to Marketplace</a>
        </div>
      <?php else: ?>
        <table class="table">
          <thead>
            <tr>
              <th>#</th><th>Farmer</th><th>Amount</th><th>Status</th><th>Date</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (array_slice($ro, 0, 8) as $order): ?>
            <tr>
              <td><strong>#<?= (int)$order['id'] ?></strong></td>
              <td><?= $sec->escape($order['farmer_name']) ?></td>
              <td><strong>KES <?= number_format((float)$order['total_amount'], 2) ?></strong></td>
              <td><span class="status status-<?= $sec->escape($order['status']) ?>"><?= $sec->escape($order['status']) ?></span></td>
              <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
              <td><a href="<?= BASE_URL ?>/orders/<?= (int)$order['id'] ?>" class="btn btn-outline btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- Unread Messages -->
  <?php if ($unread > 0): ?>
  <div class="card fade-up delay-3" style="margin-top:1.5rem;border-left:4px solid var(--green-500);">
    <div class="card-body" style="display:flex;align-items:center;gap:1rem;">
      <div style="font-size:2rem;">✉️</div>
      <div>
        <strong>You have <?= $unread ?> unread message<?= $unread > 1 ? 's' : '' ?></strong>
        <p class="text-muted text-sm">Stay connected with your farmers.</p>
      </div>
      <a href="<?= BASE_URL ?>/messages" class="btn btn-primary" style="margin-left:auto;">View Messages</a>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
