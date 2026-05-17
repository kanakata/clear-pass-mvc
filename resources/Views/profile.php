<?php
$sec       = \Core\Security\Security::getInstance();
$pageTitle = 'My Profile';
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span><span>Profile</span>
    </div>
    <h1>My Profile</h1>
    <p>Manage your account information</p>
  </div>
</div>

<div class="container" style="max-width:800px;">
  <!-- Profile Header Card -->
  <div class="profile-header fade-up">
    <div class="profile-avatar-wrap">
      <?php if ($full['avatar']): ?>
        <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($full['avatar']) ?>"
             class="profile-avatar" alt="Avatar" id="avatarPreview">
      <?php else: ?>
        <img src="<?= BASE_URL ?>/images/avatar.svg"
             class="profile-avatar" alt="Avatar" id="avatarPreview">
      <?php endif; ?>
      <label for="avatarFile" class="profile-avatar-edit" title="Change photo">
        <i class="fas fa-camera"></i>
      </label>
    </div>
    <div class="profile-info">
      <h2><?= $sec->escape($full['business_name']) ?></h2>
      <p><?= $sec->escape($full['email']) ?></p>
      <div style="display:flex;gap:.5rem;margin-top:.5rem;flex-wrap:wrap;">
        <span class="role-badge role-<?= $user['role'] ?>"><?= ucfirst($user['role']) ?></span>
        <?php if ($full['location']): ?>
          <span style="font-size:.8rem;color:var(--text-light);">
            <i class="fas fa-map-marker-alt"></i> <?= $sec->escape($full['location']) ?>
          </span>
        <?php endif; ?>
        <?php if ($full['phone']): ?>
          <span style="font-size:.8rem;color:var(--text-light);">
            <i class="fas fa-phone"></i> <?= $sec->escape($full['phone']) ?>
          </span>
        <?php endif; ?>
      </div>
      <?php if ($full['last_login']): ?>
        <p class="text-sm text-muted" style="margin-top:.5rem;">
          Last login: <?= date('F d, Y H:i', strtotime($full['last_login'])) ?>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Edit Form -->
  <div class="card fade-up delay-1">
    <div class="card-header">
      <h3><i class="fas fa-edit" style="color:var(--green-600);margin-right:.5rem;"></i> Edit Information</h3>
    </div>
    <div class="card-body">
      <form method="POST" action="<?= BASE_URL ?>/profile/update"
            enctype="multipart/form-data" novalidate>
        <?= $sec->csrfField() ?>

        <!-- Hidden avatar file linked to avatar label above -->
        <input type="file" name="avatar" id="avatarFile"
               accept="image/*" data-preview="avatarPreview"
               style="display:none;">

        <div class="form-grid">
          <div class="form-group col-span-2">
            <label class="form-label">Business / Farm Name *</label>
            <div class="input-icon">
              <i class="fas fa-building icon"></i>
              <input type="text" name="business_name" class="form-control"
                     value="<?= $sec->escape($full['business_name']) ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Phone Number</label>
            <div class="input-icon">
              <i class="fas fa-phone icon"></i>
              <input type="tel" name="phone" class="form-control"
                     placeholder="+254 700 000 000"
                     value="<?= $sec->escape($full['phone'] ?? '') ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Location / County</label>
            <div class="input-icon">
              <i class="fas fa-map-marker-alt icon"></i>
              <input type="text" name="location" class="form-control"
                     placeholder="e.g. Nairobi, Kenya"
                     value="<?= $sec->escape($full['location'] ?? '') ?>">
            </div>
          </div>

          <div class="form-group col-span-2">
            <label class="form-label">Bio / About</label>
            <textarea name="bio" class="form-control" rows="4"
                      placeholder="Tell hotels / farmers about your business…"><?= $sec->escape($full['bio'] ?? '') ?></textarea>
          </div>
        </div>

        <div style="display:flex;gap:1rem;margin-top:.5rem;">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> Save Changes
          </button>
          <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline btn-lg">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Account Info (read-only) -->
  <div class="card fade-up delay-2" style="margin-top:1.5rem;">
    <div class="card-header">
      <h3><i class="fas fa-shield-alt" style="color:var(--green-600);margin-right:.5rem;"></i> Account Details</h3>
    </div>
    <div class="card-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" value="<?= $sec->escape($full['email']) ?>"
                 readonly style="background:var(--cream);cursor:not-allowed;">
          <p class="text-sm text-muted mt-1">Email cannot be changed. Contact support if needed.</p>
        </div>
        <div class="form-group">
          <label class="form-label">Account Role</label>
          <input type="text" class="form-control"
                 value="<?= ucfirst($sec->escape($full['role'])) ?>"
                 readonly style="background:var(--cream);cursor:not-allowed;">
        </div>
        <div class="form-group">
          <label class="form-label">Member Since</label>
          <input type="text" class="form-control"
                 value="<?= date('F d, Y', strtotime($full['created_at'])) ?>"
                 readonly style="background:var(--cream);cursor:not-allowed;">
        </div>
        <div class="form-group">
          <label class="form-label">Account Status</label>
          <input type="text" class="form-control"
                 value="<?= $full['is_active'] ? 'Active' : 'Suspended' ?>"
                 readonly style="background:var(--cream);cursor:not-allowed;">
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
