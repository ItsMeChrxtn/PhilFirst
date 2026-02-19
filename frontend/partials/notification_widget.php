<?php if(!empty($_SESSION['user'])): ?>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Don't show user notifications for admins - they have their own admin bell
$isAdmin = false;
if (!empty($_SESSION['user']) && is_array($_SESSION['user'])) {
  $isAdmin = strtolower($_SESSION['user']['role'] ?? '') === 'admin';
}

if (!$isAdmin) {
  // include DB connection
  try { require_once __DIR__ . '/../../backend/config.php'; } catch (Exception $e) { $pdo = null; }

  /**
   * @var \PDO|null $pdo
   */

  $userId = null;
  if (!empty($_SESSION['user'])){
      $u = $_SESSION['user'];
      if (is_array($u)) $userId = $u['id'] ?? $u['user_id'] ?? null;
      elseif (is_int($u)) $userId = $u;
      elseif (is_string($u) && ctype_digit($u)) $userId = (int)$u;
  }

  $notifications = [];
  $unreadCount = 0;
  // only query when we have a real PDO instance and a user id
  if (($pdo ?? null) instanceof PDO && !empty($userId)){
    try{
      $sql = "SELECT `id`, `application_id`, `status`, `message`, `is_read`, `created_at`, `resolved_job_title`, `display_status` FROM `notifications` WHERE user_id = ? ORDER BY created_at DESC LIMIT 50";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([intval($userId)]);
      $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
      foreach ($notifications as $n) if (empty($n['is_read'])) $unreadCount++;
    }catch(Exception $e){
      $notifications = [];
      $unreadCount = 0;
    }
  }

  function esc($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}
?>
<?php if(!$isAdmin): ?>
  <div class="hidden md:block mr-3 relative">
    <button class="notif-bell p-2 rounded-md hover:bg-emerald-50" title="Notifications">
      <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
      <?php if ($unreadCount>0): ?>
        <span class="notif-badge absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5"><?php echo intval($unreadCount); ?></span>
      <?php else: ?>
        <span class="notif-badge absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5 hidden">0</span>
      <?php endif; ?>
    </button>

  <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-96 bg-white border rounded-xl shadow-xl z-50 overflow-hidden">
    <div class="px-4 py-3 border-b flex items-center justify-between">
      <span class="font-semibold text-emerald-700">Notifications</span>
      <button onclick="markAllRead()" class="text-xs text-emerald-600 hover:underline">Mark all as read</button>
    </div>
    <div id="notifList" class="max-h-96 overflow-y-auto">
      <?php if (empty($notifications)): ?>
        <div class="p-4 text-sm text-neutral-500 text-center">No notifications</div>
      <?php else: ?>
        <?php foreach ($notifications as $n): ?>
          <?php
            $title = $n['resolved_job_title'] ?: ($n['message'] ?: 'Notification');
            $status = $n['display_status'] ?: $n['status'] ?: '';
            $dt = $n['created_at'] ? date('M j, Y g:ia', strtotime($n['created_at'])) : '';
            $unreadClass = empty($n['is_read']) ? 'bg-emerald-50' : '';
          ?>
          <div class="px-4 py-3 border-b <?php echo $unreadClass; ?>" data-notif-id="<?php echo esc($n['id']); ?>" data-application-id="<?php echo esc($n['application_id']); ?>" style="cursor: pointer;">
            <div class="text-sm text-neutral-800 font-medium"><?php echo esc($title); ?> - <?php echo esc($status); ?></div>
            <div class="text-xs text-neutral-500 mt-1"><?php echo esc($dt); ?></div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

  <!-- mobile notification button -->
  <div class="mt-2 px-3 md:hidden">
    <button id="notifBtnMobile" class="notif-bell p-2 rounded-md hover:bg-emerald-50 w-full text-left flex items-center gap-2" title="Notifications">
      <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
      <span class="flex-1 text-sm text-neutral-700">Notifications</span>
      <?php if ($unreadCount>0): ?>
        <span id="notifCountMobile" class="notif-badge absolute -top-1 -right-3 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5"><?php echo intval($unreadCount); ?></span>
      <?php else: ?>
        <span id="notifCountMobile" class="notif-badge absolute -top-1 -right-3 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5 hidden">0</span>
      <?php endif; ?>
    </button>
  </div>
<?php endif; ?>
<?php endif; ?>
