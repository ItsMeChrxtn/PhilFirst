<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PhilFirst Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: '#198754' }
        }
      }
    }
  </script>

  <style>
    /* Sidebar links */
    .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:6px; color:#374151; transition:.15s; }
    .sidebar-link:hover { background:#f0fdf4; color:#198754; }
    .sidebar-link.active { background:#198754; color:#fff; }
    .sidebar-link i { width:18px; text-align:center; }
  </style>
</head>

<body class="bg-gray-100 text-gray-800">

<!-- ================= NAVBAR ================= -->
<header class="fixed top-0 left-0 right-0 h-14 bg-brand z-50 shadow">
  <div class="flex items-center justify-between h-full px-6 text-white">
    <div class="flex items-center gap-3 font-semibold"><i class="fa-solid fa-briefcase"></i> PhilFirst Admin</div>
    <div class="flex items-center gap-4 text-sm">
      <i class="fa-regular fa-bell"></i>
      <div class="flex items-center gap-2">
        <img src="https://ui-avatars.com/api/?name=Admin&background=ffffff&color=198754" class="w-7 h-7 rounded-full">
        Admin
      </div>
    </div>
  </div>
</header>

<!-- ================= LAYOUT ================= -->
<div class="flex pt-14 min-h-screen">

  <!-- ================= SIDEBAR ================= -->
  <aside class="w-64 bg-white border-r fixed top-14 bottom-0 left-0 overflow-y-auto">
    <nav class="px-4 py-6 text-sm space-y-1">
      <a href="dashboard.php" class="sidebar-link active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
      <a href="job_management.php" class="sidebar-link"><i class="fa-solid fa-briefcase"></i> Job Management</a>
      <a href="applicants.php" class="sidebar-link"><i class="fa-solid fa-users"></i> Applicants</a>
      <a href="application_processing.php" class="sidebar-link"><i class="fa-solid fa-clipboard-list"></i> Application Processing</a>
      <a href="users.php" class="sidebar-link"><i class="fa-solid fa-user-shield"></i> Users</a>
      <a href="reports.php" class="sidebar-link"><i class="fa-solid fa-file-lines"></i> Reports</a>
      <a href="settings.php" class="sidebar-link"><i class="fa-solid fa-gear"></i> Settings</a>
    </nav>
  </aside>

  <!-- ================= MAIN ================= -->
  <main class="flex-1 ml-64 p-8">
