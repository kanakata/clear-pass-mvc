<?php /* orders.php */
$sec       = \Core\Security\Security::getInstance();
$pageTitle = 'Orders';
require ROOT . '/app/Views/shared/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span><span>Orders</span>
    </div>
    <h1>Order Management</h1>
    <p><?= ucfirst($user['role']) ?> · All your orders in one place</p>
  </div>
</div>
<div class="container">
  <?php if (empty($orders)): ?>
    <div class="empty-state">
      <div class="empty-icon">📦</div>
      <h3>No orders yet</h3>
      <p><?= $user['role'] === 'hotel' ? 'Browse the marketplace to place your first order.' : 'Orders from hotels will appear here.' ?></p>
      <?php if ($user['role'] === 'hotel'): ?>
        <a href="<?= BASE_URL ?>/marketplace" class="btn btn-primary">Go to Marketplace</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="card fade-up">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th><?= $user['role'] === 'hotel' ? 'Farmer' : 'Hotel' ?></th>
              <th>Amount</th><th>Status</th><th>Date</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
              <td><strong>#<?= (int)$order['id'] ?></strong></td>
              <td><?= $sec->escape($order[$user['role'] === 'hotel' ? 'farmer_name' : 'hotel_name']) ?></td>
              <td><strong>KES <?= number_format((float)$order['total_amount'], 2) ?></strong></td>
              <td><span class="status status-<?= $sec->escape($order['status']) ?>"><?= $sec->escape($order['status']) ?></span></td>
              <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
              <td>
                <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                  <a href="<?= BASE_URL ?>/orders/<?= (int)$order['id'] ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-eye"></i> View
                  </a>
                  <?php if ($user['role'] === 'farmer' && !in_array($order['status'], ['completed','cancelled'])): ?>
                    <form method="POST" action="<?= BASE_URL ?>/orders/<?= (int)$order['id'] ?>/status" style="display:flex;gap:.3rem;margin:0;">
                      <?= $sec->csrfField() ?>
                      <select name="status" class="form-control" style="padding:.3rem .6rem;height:auto;font-size:.78rem;">
                        <?php foreach (['confirmed','processing','shipped','completed','cancelled'] as $s): ?>
                          <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                      </select>
                      <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                  <?php endif; ?>
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
<?php require ROOT . '/app/Views/shared/footer.php';
