<?php
$sec   = \Core\Security\Security::getInstance();
$flash = $flash ?? [];
$old   = $old   ?? [];
loadHeader("register");
?>
<div class="auth-page">
  <div class="auth-left">
    <div class="auth-left-content">
      <div class="auth-logo">🌿</div>
      <h1>Join the Network</h1>
      <p>Whether you run a hotel or grow produce, AgriHotel Connect brings you together for fresh, sustainable partnerships.</p>
      <div class="auth-features">
        <div class="auth-feature"><i class="fas fa-hotel"></i> Hotels: Browse & order fresh local produce</div>
        <div class="auth-feature"><i class="fas fa-tractor"></i> Farmers: List & sell directly to hotels</div>
        <div class="auth-feature"><i class="fas fa-handshake"></i> Build long-term business relationships</div>
        <div class="auth-feature"><i class="fas fa-shield-alt"></i> Secure & verified transactions</div>
      </div>
    </div>
  </div>

  <div class="auth-right" style="max-width:520px;">
    <?php if (!empty($flash)): ?>
      <?php foreach ($flash as $type => $msg): ?>
      <div class="flash flash-<?= $sec->escape($type) ?>" style="margin-bottom:1.5rem;position:relative;animation:none;">
        <i class="fas fa-exclamation-circle"></i> <?= $sec->escape($msg) ?>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="auth-form-header">
      <h2>Create Account</h2>
      <p>Start connecting today — it's free</p>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/auth/register" novalidate>
      <?= $sec->csrfField() ?>

      <!-- Role Selection -->
      <div style="margin-bottom:1.25rem;">
        <label class="form-label">I am a…</label>
        <div class="role-selector">
          <input type="radio" name="role" id="role_hotel" value="hotel" class="role-option"
                 <?= (($old['role'] ?? '') === 'hotel') ? 'checked' : '' ?>>
          <label for="role_hotel" class="role-label">
            <i class="fas fa-hotel"></i>
            <span>Hotel / Restaurant</span>
            <small>I want to source produce</small>
          </label>

          <input type="radio" name="role" id="role_farmer" value="farmer" class="role-option"
                 <?= (($old['role'] ?? '') === 'farmer') ? 'checked' : '' ?>>
          <label for="role_farmer" class="role-label">
            <i class="fas fa-seedling"></i>
            <span>Farmer / Supplier</span>
            <small>I want to sell produce</small>
          </label>
        </div>
      </div>

      <div class="form-grid">
        <div class="form-group col-span-2">
          <label class="form-label" for="business_name">Business Name</label>
          <div class="input-icon">
            <i class="fas fa-building icon"></i>
            <input type="text" id="business_name" name="business_name" class="form-control"
                   placeholder="Your business name"
                   value="<?= $sec->escape($old['business_name'] ?? '') ?>" required>
          </div>
        </div>

        <div class="form-group col-span-2">
          <label class="form-label" for="email">Email Address</label>
          <div class="input-icon">
            <i class="fas fa-envelope icon"></i>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="you@business.com"
                   value="<?= $sec->escape($old['email'] ?? '') ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="phone">Phone Number</label>
          <div class="input-icon">
            <i class="fas fa-phone icon"></i>
            <input type="tel" id="phone" name="phone" class="form-control"
                   placeholder="+254 700 000000"
                   value="<?= $sec->escape($old['phone'] ?? '') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="location">Location / County</label>
          <div class="input-icon">
            <i class="fas fa-map-marker-alt icon"></i>
            <input type="text" id="location" name="location" class="form-control"
                   placeholder="e.g. Nairobi, Kenya"
                   value="<?= $sec->escape($old['location'] ?? '') ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-icon">
            <i class="fas fa-lock icon"></i>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Min. 8 chars" required autocomplete="new-password">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirm">Confirm Password</label>
          <div class="input-icon">
            <i class="fas fa-lock icon"></i>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control"
                   placeholder="Repeat password" required autocomplete="new-password">
          </div>
        </div>
      </div>

      <p class="text-sm text-muted" style="margin-bottom:1rem;">
        Password must be at least 8 characters with uppercase, lowercase, and a number.
      </p>

      <button type="submit" class="btn btn-primary w-full btn-lg">
        <i class="fas fa-user-plus"></i> Create Account
      </button>
    </form>

    <div class="divider">already have an account?</div>
    <p class="text-center text-sm">
      <a href="/landing" style="font-weight:600;">Sign in here</a>
    </p>
  </div>
</div>
<?php loadFooter() ?>
