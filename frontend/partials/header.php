<?php
// Professional shared header partial
// Expects session available and optional $_SESSION['user'] with id, first_name, profile_picture
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$u_first = htmlspecialchars($_SESSION['user']['first_name'] ?? ($_SESSION['user']['email'] ?? 'User'));
$u_avatar = htmlspecialchars($_SESSION['user']['profile_picture'] ?? '');
$publicLandingUrl = getenv('PHILFIRST_PUBLIC_LANDING_URL') ?: '/welcome';
$publicFrontendHomeUrl = getenv('PHILFIRST_PUBLIC_FRONTEND_URL') ?: '/welcome/home';
$publicJobsUrl = getenv('PHILFIRST_PUBLIC_JOBS_URL') ?: '/welcome/jobs';
$publicMyApplicationsUrl = getenv('PHILFIRST_PUBLIC_MY_APPLICATIONS_URL') ?: '/welcome/my-applications';
$publicSettingsUrl = getenv('PHILFIRST_PUBLIC_SETTINGS_URL') ?: '/welcome/settings';
?>
<header class="bg-white border-b sticky top-0 z-50 shadow-sm">
  <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
    <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>" class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold">PF</div>
      <div class="hidden sm:block">
        <h1 class="text-lg font-extrabold tracking-wide text-emerald-700">Phil-First HR & Services</h1>
        <div class="text-xs text-neutral-400">People. Process. Performance.</div>
      </div>
    </a>

    <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
      <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>" class="hover:text-emerald-700">Home</a>
      <a href="<?php echo htmlspecialchars($publicJobsUrl); ?>" class="hover:text-emerald-700">Careers</a>
      <?php if(!empty($_SESSION['user'])): ?>
        <a href="<?php echo htmlspecialchars($publicMyApplicationsUrl); ?>" class="hover:text-emerald-700">My Applications</a>
      <?php endif; ?>
      <div class="relative">
        <button id="aboutToggle" class="hover:text-emerald-700">About</button>
        <div id="aboutDropdown" class="absolute right-0 mt-2 w-64 bg-white border rounded shadow-lg hidden z-50">
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#why" class="block px-4 py-2 hover:bg-neutral-100">Why Human Resources is Important?</a>
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#team" class="block px-4 py-2 hover:bg-neutral-100">Who We Are?</a>
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#about" class="block px-4 py-2 hover:bg-neutral-100">Our Code of Ethics</a>
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#how" class="block px-4 py-2 hover:bg-neutral-100">How It Works – Recruitment and Selection Criteria</a>
        </div>
      </div>
      <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#services" class="hover:text-emerald-700">Services</a>
      <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#contact" class="hover:text-emerald-700">Contact</a>
    </nav>

    <div class="flex items-center gap-3">
      <?php include __DIR__ . '/notification_widget.php'; ?>

      <?php if(!empty($_SESSION['user'])): ?>
        <div class="relative">
          <button id="userMenuBtn" class="flex items-center gap-3 px-3 py-2 rounded-xl border border-neutral-200 bg-white shadow-sm hover:shadow-lg transition">
            <img src="<?php echo $u_avatar ?: 'assets/js/blank-avatar.png'; ?>" class="w-9 h-9 rounded-full object-cover" alt="avatar" />
            <span class="hidden sm:inline-block text-sm font-medium text-neutral-800"><?php echo $u_first; ?></span>
            <svg class="w-3 h-3 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"/></svg>
          </button>
          <div id="userDropdown" class="absolute right-0 mt-3 w-56 bg-white border rounded-2xl shadow-lg hidden z-50 overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-3 bg-neutral-50 border-b">
              <img src="<?php echo $u_avatar ?: 'assets/js/blank-avatar.png'; ?>" class="w-12 h-12 rounded-full object-cover" alt="avatar" />
              <div>
                <div class="text-sm font-semibold text-neutral-900"><?php echo $u_first; ?></div>
                <div class="text-xs text-neutral-500">Manage your account</div>
              </div>
            </div>
            <div class="flex flex-col">
              <a href="<?php echo htmlspecialchars($publicSettingsUrl); ?>" class="px-4 py-3 hover:bg-emerald-50 text-sm">⚙️ Settings</a>
              <button id="logoutBtn" class="text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">🚪 Logout</button>
            </div>
          </div>
        </div>
      <?php else: ?>
        <button id="openLogin" class="text-sm px-4 py-2 rounded-md border border-emerald-700 text-emerald-700 hover:bg-emerald-50">Login</button>
      <?php endif; ?>

      <!-- mobile toggle -->
      <button id="mobileToggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-emerald-700 hover:bg-emerald-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
      </button>
    </div>
  </div>

  <!-- Mobile navigation -->
  <div id="mobileNav" class="md:hidden hidden border-t bg-white">
    <div class="px-4 pt-4 pb-6 space-y-1">
      <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>" class="block px-3 py-2 rounded hover:bg-neutral-100">Home</a>
      <a href="<?php echo htmlspecialchars($publicJobsUrl); ?>" class="block px-3 py-2 rounded hover:bg-neutral-100">Careers</a>
      <div>
        <button id="mobileAboutToggle" class="w-full text-left px-3 py-2 rounded hover:bg-neutral-100 flex items-center justify-between">About
          <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"/></svg>
        </button>
        <div id="mobileAboutDropdown" class="hidden pl-4 mt-1 space-y-1">
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#why" class="block px-3 py-2 rounded hover:bg-neutral-100">Why Human Resources is Important?</a>
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#team" class="block px-3 py-2 rounded hover:bg-neutral-100">Who We Are?</a>
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#about" class="block px-3 py-2 rounded hover:bg-neutral-100">Our Code of Ethics</a>
          <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#how" class="block px-3 py-2 rounded hover:bg-neutral-100">How It Works – Recruitment and Selection Criteria</a>
        </div>
      </div>
      <?php if(!empty($_SESSION['user'])): ?>
        <a href="<?php echo htmlspecialchars($publicMyApplicationsUrl); ?>" class="block px-3 py-2 rounded hover:bg-neutral-100">My Applications</a>
        <a href="<?php echo htmlspecialchars($publicSettingsUrl); ?>" class="block px-3 py-2 rounded hover:bg-neutral-100">Settings</a>
      <?php else: ?>
        <a href="<?php echo htmlspecialchars($publicLandingUrl); ?>" class="hidden"></a>
      <?php endif; ?>
      <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#services" class="block px-3 py-2 rounded hover:bg-neutral-100">Services</a>
      <a href="<?php echo htmlspecialchars($publicFrontendHomeUrl); ?>#contact" class="block px-3 py-2 rounded hover:bg-neutral-100">Contact</a>
    </div>
  </div>
</header>

<!-- header small styling is handled via Tailwind classes in markup -->

<?php
// end header partial
?>

<!-- header behavior is handled centrally in script.js -->
