<?php
$sec       = \Core\Security\Security::getInstance();
$pageTitle = 'Order #' . $order['id'];
require ROOT . '/app/Views/shared/header.php';
?>
<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span>
      <a href="<?= BASE_URL ?>/orders">Orders</a><span>/</span>
      <span>#<?= (int)$order['id'] ?></span>
    </div>
    <h1>Order #<?= (int)$order['id'] ?></h1>
    <p>Placed <?= date('F d, Y \a\t H:i', strtotime($order['created_at'])) ?></p>
  </div>
</div>
<div class="container">
  <div class="order-detail-grid">
    <div>
      <!-- Items -->
      <div class="card fade-up">
        <div class="card-header"><h3>Order Items</h3></div>
        <div class="table-wrap">
          <table class="table">
            <thead>
              <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
              <?php foreach ($order['items'] as $item): ?>
              <tr>
                <td><?= $sec->escape($item['product_name']) ?></td>
                <td><?= (int)$item['quantity'] ?> <?= $sec->escape($item['unit']) ?></td>
                <td>KES <?= number_format((float)$item['unit_price'], 2) ?></td>
                <td><strong>KES <?= number_format((float)$item['subtotal'], 2) ?></strong></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer" style="text-align:right;">
          <span style="font-size:1.2rem;font-weight:700;color:var(--green-600);">
            Total: KES <?= number_format((float)$order['total_amount'], 2) ?>
          </span>
        </div>
      </div>

      <?php if ($order['notes']): ?>
      <div class="card fade-up delay-1" style="margin-top:1.5rem;">
        <div class="card-header"><h3>Special Notes</h3></div>
        <div class="card-body">
          <p><?= $sec->escape($order['notes']) ?></p>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="fade-up delay-2">
      <!-- Status -->
      <div class="card">
        <div class="card-header"><h3>Status</h3></div>
        <div class="card-body">
          <span class="status status-<?= $sec->escape($order['status']) ?>" style="font-size:1rem;padding:.5rem 1rem;">
            <?= ucfirst($sec->escape($order['status'])) ?>
          </span>

          <?php if ($user['role'] === 'farmer' && !in_array($order['status'], ['completed','cancelled'])): ?>
          <form method="POST" action="<?= BASE_URL ?>/orders/<?= (int)$order['id'] ?>/status"
                style="margin-top:1.25rem;">
            <?= $sec->csrfField() ?>
            <div class="form-group">
              <label class="form-label">Update Status</label>
              <select name="status" class="form-control">
                <?php foreach (['confirmed','processing','shipped','completed','cancelled'] as $s): ?>
                  <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" class="btn btn-primary w-full">Update Status</button>
          </form>
          <?php endif; ?>
        </div>
      </div>

      <!-- Parties -->
      <div class="card" style="margin-top:1.25rem;">
        <div class="card-header"><h3>Parties</h3></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
          <div>
            <div style="font-size:.75rem;font-weight:600;text-transform:uppercase;color:var(--text-light);margin-bottom:.3rem;">Hotel</div>
            <div style="font-weight:600;"><?= $sec->escape($order['hotel_name']) ?></div>
            <div style="font-size:.85rem;color:var(--text-light);"><?= $sec->escape($order['hotel_email']) ?></div>
          </div>
          <div>
            <div style="font-size:.75rem;font-weight:600;text-transform:uppercase;color:var(--text-light);margin-bottom:.3rem;">Farmer</div>
            <div style="font-weight:600;"><?= $sec->escape($order['farmer_name']) ?></div>
            <div style="font-size:.85rem;color:var(--text-light);"><?= $sec->escape($order['farmer_email']) ?></div>
          </div>
        </div>
        <div class="card-footer">
          <?php
          $otherId = $user['id'] == $order['hotel_id'] ? $order['farmer_id'] : $order['hotel_id'];
          ?>
          <a href="<?= BASE_URL ?>/messages?to=<?= (int)$otherId ?>" class="btn btn-outline btn-sm w-full">
            <i class="fas fa-envelope"></i> Send Message
          </a>
        </div>
      </div>

      <a href="<?= BASE_URL ?>/orders" class="btn btn-outline" style="margin-top:1.25rem;">
        <i class="fas fa-arrow-left"></i> Back to Orders
      </a>
    </div>
  </div>
</div>
<?php require ROOT . '/app/Views/shared/footer.php'; ?>
