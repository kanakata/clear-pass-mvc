<?php
$sec = \Core\Security\Security::getInstance();
$flash = $flash ?? [];
loadHeader("landing");
?>

    <div class="auth-page">
        <!-- LEFT PANEL -->
        <div class="auth-left">
            <div class="auth-left-content">
                <div class="auth-logo">🌾</div>
                <h1>Farm to Table,<br>Simplified</h1>
                <p>Connect hotels with local farmers. Source fresh produce directly. Build lasting partnerships.</p>
                <div class="auth-features">
                    <div class="auth-feature"><i class="fas fa-check-circle"></i> Direct farm-to-hotel sourcing</div>
                    <div class="auth-feature"><i class="fas fa-check-circle"></i> Real-time inventory & pricing</div>
                    <div class="auth-feature"><i class="fas fa-check-circle"></i> Secure order management</div>
                    <div class="auth-feature"><i class="fas fa-check-circle"></i> Integrated messaging system</div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-right">
            <?php if (!empty($flash)): ?>
                <?php foreach ($flash as $type => $msg): ?>
                    <div class="flash flash-<?= $sec->escape($type) ?>" style="margin-bottom:1.5rem;position:relative;animation:none;">
                        <i class="fas <?= $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                        <?= $sec->escape($msg) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="auth-form-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your AgriHotel Connect account</p>
            </div>

            <form method="POST" action="" novalidate>
                <?= $sec->csrfField() ?>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="you@business.com"
                            value="<?= $sec->escape(($_POST['email'] ?? '')) ?>"
                            required autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        Password
                        <a href="<?= BASE_URL ?>/auth/forgot" style="float:right;font-weight:400;font-size:.8rem;">Forgot password?</a>
                    </label>
                    <div class="input-icon">
                        <i class="fas fa-lock icon"></i>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full btn-lg" style="margin-top:.5rem;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>

            <div class="divider">or</div>

            <p class="text-center text-sm">
                Don't have an account?
                <a href="/register" style="font-weight:600;">Create one free</a>
            </p>
        </div>
    </div>
    <script src="<?= BASE_URL ?>/js/app.js"></script>
</body>

</html>
