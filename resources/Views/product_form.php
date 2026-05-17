<?php
$sec       = \Core\Security\Security::getInstance();
$isEdit    = isset($product);
$pageTitle = $isEdit ? 'Edit Product' : 'Add Product';
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span>
      <a href="<?= BASE_URL ?>/farmer/products">Products</a><span>/</span>
      <span><?= $isEdit ? 'Edit' : 'Add New' ?></span>
    </div>
    <h1><?= $isEdit ? 'Edit Product' : 'Add New Product' ?></h1>
    <p><?= $isEdit ? 'Update your listing details' : 'List your produce on the marketplace' ?></p>
  </div>
</div>

<div class="container" style="max-width:800px;">
  <div class="card fade-up">
    <div class="card-body">
      <form method="POST"
            action="<?= $isEdit ? BASE_URL.'/farmer/products/'.(int)$product['id'].'/update' : BASE_URL.'/farmer/products' ?>"
            enctype="multipart/form-data"
            novalidate>
        <?= $sec->csrfField() ?>

        <div class="form-grid">
          <div class="form-group col-span-2">
            <label class="form-label">Product Name *</label>
            <input type="text" name="name" class="form-control"
                   placeholder="e.g. Fresh Organic Tomatoes"
                   value="<?= $sec->escape($product['name'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Category *</label>
            <select name="category" class="form-control" required>
              <option value="">Select category…</option>
              <?php
              $cats = ['vegetables','fruits','grains','dairy','meat','herbs','spices','eggs','other'];
              foreach ($cats as $cat):
                $sel = (($product['category'] ?? '') === $cat) ? 'selected' : '';
              ?>
                <option value="<?= $cat ?>" <?= $sel ?>><?= ucfirst($cat) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Unit of Measure *</label>
            <select name="unit" class="form-control" required>
              <option value="">Select unit…</option>
              <?php
              $units = ['kg','g','litre','piece','dozen','bunch','crate','bag','box','ton'];
              foreach ($units as $u):
                $sel = (($product['unit'] ?? '') === $u) ? 'selected' : '';
              ?>
                <option value="<?= $u ?>" <?= $sel ?>><?= ucfirst($u) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Price per Unit (KES) *</label>
            <div class="input-icon">
              <i class="fas fa-money-bill icon"></i>
              <input type="number" name="price_per_unit" class="form-control"
                     placeholder="0.00" step="0.01" min="0"
                     value="<?= $sec->escape($product['price_per_unit'] ?? '') ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Stock Quantity *</label>
            <input type="number" name="stock_quantity" class="form-control"
                   placeholder="Available stock" min="0"
                   value="<?= $sec->escape($product['stock_quantity'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Minimum Order Quantity</label>
            <input type="number" name="min_order" class="form-control"
                   placeholder="1" min="1"
                   value="<?= $sec->escape($product['min_order'] ?? '1') ?>">
          </div>

          <div class="form-group col-span-2">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"
                      placeholder="Describe your product — freshness, farming method, harvest date…"><?= $sec->escape($product['description'] ?? '') ?></textarea>
          </div>

          <!-- Image Upload -->
          <div class="form-group col-span-2">
            <label class="form-label">Product Image</label>
            <div style="display:flex;align-items:flex-start;gap:1.5rem;flex-wrap:wrap;">
              <?php if (!empty($product['image'])): ?>
                <img id="imgPreview"
                     src="<?= BASE_URL ?>/uploads/<?= $sec->escape($product['image']) ?>"
                     style="width:120px;height:120px;object-fit:cover;border-radius:var(--radius-md);border:2px solid var(--cream-dark);">
              <?php else: ?>
                <img id="imgPreview" src="" alt=""
                     style="width:120px;height:120px;object-fit:cover;border-radius:var(--radius-md);border:2px solid var(--cream-dark);display:none;">
              <?php endif; ?>
              <div style="flex:1;">
                <input type="file" name="image" id="imageFile" accept="image/*"
                       class="form-control" data-preview="imgPreview"
                       style="padding:.5rem;">
                <p class="text-sm text-muted mt-1">
                  JPG, PNG or WebP · Max 5MB. A good image boosts your listing significantly.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div style="display:flex;gap:1rem;margin-top:.5rem;">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-<?= $isEdit ? 'save' : 'plus-circle' ?>"></i>
            <?= $isEdit ? 'Save Changes' : 'List Product' ?>
          </button>
          <a href="<?= BASE_URL ?>/farmer/products" class="btn btn-outline btn-lg">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
