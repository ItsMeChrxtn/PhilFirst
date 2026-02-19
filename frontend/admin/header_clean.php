<?php
session_start();
if(!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin'){
  header('Location: ../index.php');
  exit;
}
// determine current file to mark active sidebar link
$currentFile = basename($_SERVER['PHP_SELF']);
$admin = $_SESSION['user'] ?? [];
$adminFirst = $admin['first_name'] ?? '';
$adminLast = $admin['last_name'] ?? '';
$adminEmail = $admin['email'] ?? '';
$adminAvatar = $admin['profile_picture'] ?? '';
$adminName = trim($adminFirst);
$adminName = $adminName !== '' ? $adminName : 'Admin';
$adminAvatarUrl = $adminAvatar ?: ('https://ui-avatars.com/api/?name=' . urlencode($adminName) . '&background=ffffff&color=198754');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin — PhilFirst</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: '#198754'
          }
        }
      }
    }
  </script>

  <style>
    .sidebar-link{ display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:8px; color:#374151; transition: transform .18s ease, box-shadow .18s ease, background .18s ease, color .18s ease; position:relative; }
    .sidebar-link i{ width:18px; text-align:center; }
    .sidebar-link:hover{ background:#ecfdf5; color:#16a34a; transform:translateX(6px) scale(1.02); box-shadow:0 8px 20px rgba(16,185,129,0.06); }
    .sidebar-link.active{ background:#16a34a; color:#ffffff; transform:none; box-shadow:0 10px 30px rgba(16,185,129,0.12); }
    .sidebar-link.active::before{ content:''; position:absolute; left:-10px; top:50%; transform:translateY(-50%); width:6px; height:44px; background:#16a34a; border-radius:6px; }
  </style>
</head>

<body class="bg-gray-100 text-gray-800">

<!-- ================= NAVBAR (styled like frontend header) ================= -->
<header class="bg-white border-b sticky top-0 z-50 shadow-sm">
  <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
    <a href="dashboard.php" class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold">PF</div>
      <div class="hidden sm:block">
        <h1 class="text-lg font-extrabold tracking-wide text-emerald-700">PhilFirst Admin</h1>
        <div class="text-xs text-neutral-400">Administration Portal</div>
      </div>
    </a>

    <div class="flex items-center gap-3">
      <!-- Admin Notification Bell -->
      <div class="relative">
        <button id="adminNotifBell" class="p-2 rounded-md hover:bg-emerald-50" title="Admin Alerts">
          <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
          <span id="adminBellBadge" class="notif-badge absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5 hidden">0</span>
        </button>
        <div id="adminNotifDropdown" class="hidden absolute right-0 mt-3 w-96 bg-white border rounded-xl shadow-xl z-50 overflow-hidden">
          <div class="px-4 py-3 border-b bg-emerald-50 flex items-center justify-between">
            <span class="font-semibold text-emerald-700">Admin Alerts</span>
            <button id="adminMarkAllRead" class="text-xs text-emerald-700 hover:underline">Mark all as read</button>
          </div>
          <div id="adminNotifContent" class="max-h-96 overflow-y-auto">
            <div class="p-4 text-sm text-neutral-500 text-center">Loading...</div>
          </div>
        </div>
      </div>

      <?php include __DIR__ . '/../partials/notification_widget.php'; ?>

      <div class="relative">
        <button id="userMenuBtn" class="flex items-center gap-3 px-3 py-2 rounded-xl border border-neutral-200 bg-white shadow-sm hover:shadow-lg transition">
          <img src="<?php echo htmlspecialchars($adminAvatarUrl); ?>" class="w-9 h-9 rounded-full object-cover" alt="avatar"> 
          <span class="hidden sm:inline-block text-sm font-medium text-neutral-800"><?php echo htmlspecialchars($adminName); ?></span>
          <svg class="w-3 h-3 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"/></svg>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-3 w-56 bg-white border rounded-2xl shadow-lg hidden z-50 overflow-hidden">
          <div class="flex items-center gap-3 px-4 py-3 bg-neutral-50 border-b">
            <img src="<?php echo htmlspecialchars($adminAvatarUrl); ?>" class="w-12 h-12 rounded-full object-cover" alt="avatar" />
            <div>
              <div class="text-sm font-semibold text-neutral-900"><?php echo htmlspecialchars($adminName); ?></div>
              <div class="text-xs text-neutral-500"><?php echo htmlspecialchars($adminEmail ?: 'Administrator'); ?></div>
            </div>
          </div>
          <div class="flex flex-col">
            <a href="settings.php" class="px-4 py-3 hover:bg-emerald-50 text-sm">⚙️ Settings</a>
            <a href="../backend/logout.php" class="text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">🚪 Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="flex">
  <aside id="sidebar" class="w-64 bg-white border-r min-h-screen fixed top-20 left-0 bottom-0 overflow-auto">
    <nav class="px-4 py-6 text-sm space-y-1">
      <a href="dashboard.php" class="<?= ($currentFile==='dashboard.php') ? 'sidebar-link active' : 'sidebar-link' ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
      <a href="job_management.php" class="<?= ($currentFile==='job_management.php') ? 'sidebar-link active' : 'sidebar-link' ?>"><i class="fa-solid fa-briefcase"></i> Job Management</a>
      <a href="applicants.php" class="<?= ($currentFile==='applicants.php') ? 'sidebar-link active' : 'sidebar-link' ?>"><i class="fa-solid fa-users"></i> Applicants
        <span id="applicantsBadge" class="ml-auto inline-flex items-center justify-center text-[10px] font-semibold bg-red-600 text-white rounded-full px-2 py-0.5 hidden">0</span>
      </a>
      <a href="schedule.php" class="<?= ($currentFile==='schedule.php') ? 'sidebar-link active' : 'sidebar-link' ?>"><i class="fa-solid fa-calendar-days"></i> Calendar Schedule
        <span id="scheduleBadge" class="ml-auto inline-flex items-center justify-center text-[10px] font-semibold bg-amber-500 text-white rounded-full px-2 py-0.5 hidden">0</span>
      </a>
      <a href="users.php" class="<?= ($currentFile==='users.php') ? 'sidebar-link active' : 'sidebar-link' ?>"><i class="fa-solid fa-user-shield"></i> Users</a>
      <a href="cms.php" class="<?= ($currentFile==='cms.php') ? 'sidebar-link active' : 'sidebar-link' ?>"><i class="fa-solid fa-file-lines"></i> Content Manager</a>
    </nav>
  </aside>

  <main id="main" class="flex-1 px-8 pt-2 ml-64 relative z-10">

    <script>
      (function(){
        const applicantsBadge = document.getElementById('applicantsBadge');
        const scheduleBadge = document.getElementById('scheduleBadge');
        const adminBellBadge = document.getElementById('adminBellBadge');
        const adminBellBadgeMobile = document.getElementById('adminBellBadgeMobile');
        const adminNotifBell = document.getElementById('adminNotifBell');
        const adminNotifDropdown = document.getElementById('adminNotifDropdown');
        const adminNotifContent = document.getElementById('adminNotifContent');
        const adminMarkAllRead = document.getElementById('adminMarkAllRead');
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        function setBadge(el, count){
          if(!el) return;
          if(count > 0){
            el.textContent = count;
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }

        // Toggle dropdown on bell click
        if(adminNotifBell){
          adminNotifBell.addEventListener('click', async (e) => {
            e.stopPropagation();
            adminNotifDropdown.classList.toggle('hidden');
            if(!adminNotifDropdown.classList.contains('hidden')){
              await loadAdminNotifications();
            }
          });
        }

        // Toggle admin user dropdown
        if(userMenuBtn && userDropdown){
          userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
          });
        }

        // Mark all as read
        if(adminMarkAllRead){
          adminMarkAllRead.addEventListener('click', async (e) => {
            e.stopPropagation();
            try{
              const res = await fetch('/backend/admin_mark_all_read.php', { method: 'POST' });
              const data = await res.json();
              if(data.success){
                await loadAdminBadges();
                await loadAdminNotifications();
              }
            }catch(e){
              // silent fail
            }
          });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
          if(adminNotifDropdown && !adminNotifDropdown.classList.contains('hidden')){
            adminNotifDropdown.classList.add('hidden');
          }
          if(userDropdown && !userDropdown.classList.contains('hidden')){
            userDropdown.classList.add('hidden');
          }
        });

        async function loadAdminNotifications(){
          try{
            const res = await fetch('/backend/admin_badges.php');
            const data = await res.json();
            if(!data.success) {
              adminNotifContent.innerHTML = '<div class="p-4 text-sm text-red-600 text-center">Error loading alerts</div>';
              return;
            }

            let html = '';

            // Pending Applicants
            if(data.pending_list && data.pending_list.length > 0){
              html += '<div class="border-b">';
              html += '<div class="px-4 py-2 bg-red-50 text-xs font-semibold text-red-700">Pending Applicants (' + data.pending_list.length + ')</div>';
              data.pending_list.forEach(app => {
                let appliedText = 'Date unknown';
                if(app.applied_at){
                  const d = new Date(app.applied_at);
                  if(!isNaN(d)) appliedText = d.toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
                }
                html += '<div class="px-4 py-3 border-b hover:bg-gray-50 cursor-pointer transition" onclick="window.location.href=\'applicants.php\';">';
                html += '<div class="text-sm font-medium text-neutral-800">' + (app.full_name || 'Unknown') + '</div>';
                html += '<div class="text-xs text-neutral-500 mt-1">' + (app.job_title || 'Position') + ' • ' + appliedText + '</div>';
                html += '</div>';
              });
              html += '</div>';
            }

            // Today's Interviews
            if(data.today_list && data.today_list.length > 0){
              html += '<div class="border-b">';
              html += '<div class="px-4 py-2 bg-amber-50 text-xs font-semibold text-amber-700">Today\'s Interviews (' + data.today_list.length + ')</div>';
              data.today_list.forEach(sched => {
                const time = new Date(sched.scheduled_at).toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});
                html += '<div class="px-4 py-3 border-b hover:bg-gray-50 cursor-pointer transition" onclick="window.location.href=\'schedule.php\';">';
                html += '<div class="text-sm font-medium text-neutral-800">' + time + ' • ' + (sched.applicant_name || 'Unknown') + '</div>';
                html += '<div class="text-xs text-neutral-500 mt-1">' + (sched.position || 'Position') + '</div>';
                html += '</div>';
              });
              html += '</div>';
            }

            if(!html){
              html = '<div class="p-4 text-sm text-neutral-500 text-center">No alerts</div>';
            }

            adminNotifContent.innerHTML = html;
          } catch(e){
            adminNotifContent.innerHTML = '<div class="p-4 text-sm text-red-600 text-center">Error loading alerts</div>';
          }
        }

        async function loadAdminBadges(){
          try{
            const res = await fetch('/backend/admin_badges.php');
            const data = await res.json();
            if(!data.success) return;
            setBadge(applicantsBadge, data.pending_applicants || 0);
            setBadge(scheduleBadge, data.today_interviews || 0);
            setBadge(adminBellBadge, data.total_alerts || 0);
            setBadge(adminBellBadgeMobile, data.total_alerts || 0);
          }catch(e){
            // silent fail for badges
          }
        }

        loadAdminBadges();
        setInterval(loadAdminBadges, 30000);
      })();
    </script>

    <!-- page content will be injected by individual views -->
