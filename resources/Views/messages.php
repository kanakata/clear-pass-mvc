<?php
$sec        = \Core\Security\Security::getInstance();
$pageTitle  = 'Messages';
$toId       = isset($_GET['to']) ? (int)$_GET['to'] : 0;
$userModel  = new \App\Models\User();
$toUser     = $toId ? $userModel->find($toId) : null;
require ROOT . '/app/Views/shared/header.php';
?>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb">
      <a href="<?= BASE_URL ?>/dashboard">Dashboard</a><span>/</span><span>Messages</span>
    </div>
    <h1>Messages</h1>
    <p><?= count($messages) ?> messages in your inbox</p>
    <div class="page-header-actions">
      <button class="btn btn-gold" id="composeBtn"><i class="fas fa-pen"></i> New Message</button>
    </div>
  </div>
</div>

<div class="container">
  <div class="card fade-up">
    <?php if (empty($messages)): ?>
      <div class="empty-state">
        <div class="empty-icon">✉️</div>
        <h3>No messages yet</h3>
        <p>Connect with <?= $user['role'] === 'hotel' ? 'farmers' : 'hotels' ?> by sending a message.</p>
        <button class="btn btn-primary" id="composeBtn2"><i class="fas fa-pen"></i> Compose</button>
      </div>
    <?php else: ?>
      <div class="message-list" style="padding:1rem;">
        <?php foreach ($messages as $msg): ?>
        <div class="message-item <?= !$msg['is_read'] ? 'unread' : '' ?>">
          <div class="msg-avatar">
            <?php if ($msg['sender_avatar']): ?>
              <img src="<?= BASE_URL ?>/uploads/<?= $sec->escape($msg['sender_avatar']) ?>"
                   style="width:44px;height:44px;border-radius:50%;object-fit:cover;" alt="">
            <?php else: ?>
              <i class="fas fa-<?= $msg['sender_role'] === 'hotel' ? 'hotel' : 'seedling' ?>"></i>
            <?php endif; ?>
          </div>
          <div class="msg-content">
            <div class="msg-header">
              <span class="msg-sender"><?= $sec->escape($msg['sender_name']) ?>
                <span class="role-badge role-<?= $sec->escape($msg['sender_role']) ?>" style="margin-left:.35rem;"><?= ucfirst($msg['sender_role']) ?></span>
              </span>
              <span class="msg-time"><?= date('M d, H:i', strtotime($msg['created_at'])) ?></span>
            </div>
            <div class="msg-subject"><?= $sec->escape($msg['subject']) ?></div>
            <div class="msg-preview"><?= $sec->escape($msg['body']) ?></div>
          </div>
          <div style="display:flex;flex-direction:column;gap:.35rem;">
            <?php if (!$msg['is_read']): ?>
              <span style="width:8px;height:8px;background:var(--green-500);border-radius:50%;display:block;"></span>
            <?php endif; ?>
            <button class="btn btn-outline btn-sm" onclick="replyTo(<?= (int)$msg['sender_id'] ?>, '<?= $sec->escapeJs($msg['sender_name']) ?>')">
              <i class="fas fa-reply"></i>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- COMPOSE MODAL -->
<div id="composeModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9000;align-items:center;justify-content:center;padding:1rem;">
  <div style="background:var(--white);border-radius:var(--radius-xl);width:100%;max-width:520px;overflow:hidden;box-shadow:var(--shadow-xl);">
    <div style="background:var(--green-900);padding:1.25rem 1.5rem;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="color:var(--white);font-size:1rem;">New Message</h3>
      <button id="modalClose" style="background:none;border:none;color:var(--white);font-size:1.2rem;cursor:pointer;">&times;</button>
    </div>
    <div style="padding:1.5rem;">
      <form method="POST" action="<?= BASE_URL ?>/messages/send">
        <?= $sec->csrfField() ?>

        <div class="form-group">
          <label class="form-label">Recipient ID</label>
          <input type="number" name="recipient_id" id="recipientInput" class="form-control"
                 placeholder="Enter user ID"
                 value="<?= $toId ?: '' ?>" min="1" required>
          <?php if ($toUser): ?>
            <div class="text-sm text-muted mt-1">Sending to: <strong><?= $sec->escape($toUser['business_name']) ?></strong></div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Subject</label>
          <input type="text" name="subject" class="form-control" placeholder="Re: Order enquiry…">
        </div>

        <div class="form-group">
          <label class="form-label">Message *</label>
          <textarea name="body" class="form-control" rows="5"
                    placeholder="Write your message here…" required id="msgBody"></textarea>
        </div>

        <div style="display:flex;gap:.75rem;margin-top:.25rem;">
          <button type="submit" class="btn btn-primary flex-1">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
          <button type="button" class="btn btn-outline" id="modalClose2">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function replyTo(id, name) {
  document.getElementById('recipientInput').value = id;
  document.getElementById('msgBody').placeholder = 'Reply to ' + name + '…';
  document.getElementById('composeModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
document.getElementById('composeBtn2') && document.getElementById('composeBtn2').addEventListener('click', function(){
  document.getElementById('composeModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
});
document.getElementById('modalClose2') && document.getElementById('modalClose2').addEventListener('click', function(){
  document.getElementById('composeModal').style.display = 'none';
  document.body.style.overflow = '';
});
<?php if ($toId): ?>
window.addEventListener('load', function() {
  document.getElementById('composeModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
});
<?php endif; ?>
</script>

<?php require ROOT . '/app/Views/shared/footer.php'; ?>
