<?php 
session_start(); 
$publicJobsUrl = getenv('PHILFIRST_PUBLIC_JOBS_URL') ?: '/welcome/jobs';

// Load CMS content
require_once __DIR__ . '/../backend/config.php';
require_once __DIR__ . '/../backend/cms_helper.php';

$cms = new CMSHelper($pdo);

// Fetch all images from CMS
$hero_bg_1 = $cms->getPageBySlug('hero-bg-1')['content'] ?? 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=1600&q=80';
$hero_bg_2 = $cms->getPageBySlug('hero-bg-2')['content'] ?? 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1600&q=80';
$hero_bg_3 = $cms->getPageBySlug('hero-bg-3')['content'] ?? 'https://images.unsplash.com/photo-1581092795368-32ffdb3f8ebc?auto=format&fit=crop&w=1600&q=80';

// Why HR card images
$why_hr_1_img = $cms->getPageBySlug('why-hr-1-img')['content'] ?? 'https://images.pexels.com/photos/7144228/pexels-photo-7144228.jpeg';
$why_hr_2_img = $cms->getPageBySlug('why-hr-2-img')['content'] ?? 'https://plus.unsplash.com/premium_photo-1770123618996-53cfd9eac78b?w=600&auto=format&fit=crop&q=60';
$why_hr_3_img = $cms->getPageBySlug('why-hr-3-img')['content'] ?? 'https://plus.unsplash.com/premium_photo-1682310144714-cb77b1e6d64a?w=600&auto=format&fit=crop&q=60';
$why_hr_4_img = $cms->getPageBySlug('why-hr-4-img')['content'] ?? 'https://www.bing.com/th/id/OIP.vj3U_3qh9Ex23BY4QdEjPgHaDs?w=323&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2';
$why_hr_5_img = $cms->getPageBySlug('why-hr-5-img')['content'] ?? 'https://images.unsplash.com/photo-1507099985932-87a4520ed1d5?w=600&auto=format&fit=crop&q=60';
$why_hr_6_img = $cms->getPageBySlug('why-hr-6-img')['content'] ?? 'https://images.unsplash.com/flagged/photo-1570551502611-c590dc45f644?w=600&auto=format&fit=crop&q=60';

// Organization chart photos
$org_president_img = $cms->getPageBySlug('org-president-img')['content'] ?? 'https://ui-avatars.com/api/?name=Rodel+Torio';
$org_ceo_img = $cms->getPageBySlug('org-ceo-img')['content'] ?? 'https://ui-avatars.com/api/?name=Carlitos+Ruiz';
$org_gm_img = $cms->getPageBySlug('org-gm-img')['content'] ?? 'https://ui-avatars.com/api/?name=Sharon+Alcantara';
$org_secretary_img = $cms->getPageBySlug('org-secretary-img')['content'] ?? 'https://ui-avatars.com/api/?name=Hazel+Caluya';
$org_hr_supervisor_img = $cms->getPageBySlug('org-hr-supervisor-img')['content'] ?? 'https://ui-avatars.com/api/?name=Marilyn+Pabalate';
$org_accounting_img = $cms->getPageBySlug('org-accounting-img')['content'] ?? 'https://ui-avatars.com/api/?name=Christina+Ricamara';
$org_marketing_img = $cms->getPageBySlug('org-marketing-img')['content'] ?? 'https://ui-avatars.com/api/?name=Adelyn+Broquiza';
$org_hr_coordinator_img = $cms->getPageBySlug('org-hr-coordinator-img')['content'] ?? 'https://ui-avatars.com/api/?name=Robert+Cuasay';
$org_accounting_asst_img = $cms->getPageBySlug('org-accounting-asst-img')['content'] ?? 'https://ui-avatars.com/api/?name=Arlene+Carbonilla';
$org_housekeeping_img = $cms->getPageBySlug('org-housekeeping-img')['content'] ?? 'https://ui-avatars.com/api/?name=Jocelyn+Estrada';
$org_exec_housekeeper_img = $cms->getPageBySlug('org-exec-housekeeper-img')['content'] ?? 'https://ui-avatars.com/api/?name=Tata+Ayunan';
$org_branch_staff_img = $cms->getPageBySlug('org-branch-staff-img')['content'] ?? 'https://ui-avatars.com/api/?name=Branch+Staff';

// Hero section content
$hero_tagline = $cms->getPageBySlug('hero-tagline')['content'] ?? 'Trusted HR & Recruitment Partner';
$hero_title = $cms->getPageBySlug('hero-title')['content'] ?? 'Unlock the Potential of Your People';
$hero_description = $cms->getPageBySlug('hero-description')['content'] ?? 'Empower your workforce for success and growth through strategic, people-first HR solutions aligned with your goals.';

// Contact section content
$contact_title = $cms->getPageBySlug('contact-title')['content'] ?? 'Contact Us';
$contact_description = $cms->getPageBySlug('contact-description')['content'] ?? 'Get in touch with Phil-First HR & Services. You may reach us through the details below or visit our office location.';
$contact_phone = $cms->getPageBySlug('contact-phone')['content'] ?? '+63 917 123 4567';
$contact_email = $cms->getPageBySlug('contact-email')['content'] ?? 'info@philfirst.ph';
$contact_address = $cms->getPageBySlug('contact-address')['content'] ?? '123 PhilFirst St., Pasig City, Metro Manila, Philippines';
$contact_hours = $cms->getPageBySlug('contact-hours')['content'] ?? 'Monday – Friday<br>9:00 AM – 6:00 PM';
$contact_note = $cms->getPageBySlug('contact-note')['content'] ?? 'For inquiries, please contact us during office hours.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Phil-First HR & Services</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-neutral-50 text-neutral-800 antialiased">

<!-- Shared header -->
<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ================= HERO ================= -->

<section class="relative h-screen overflow-hidden">

  <!-- Background Slider -->
  <div class="absolute inset-0 z-0">
    <div id="bgSlider" class="flex h-full w-full transition-transform duration-1000 ease-in-out">
      <img src="<?php echo htmlspecialchars($hero_bg_1); ?>"
           class="w-full h-full object-cover flex-shrink-0" />
      <img src="<?php echo htmlspecialchars($hero_bg_2); ?>"
           class="w-full h-full object-cover flex-shrink-0" />
      <img src="<?php echo htmlspecialchars($hero_bg_3); ?>"
           class="w-full h-full object-cover flex-shrink-0" />
    </div>
  </div>

  <!-- Overlay -->
  <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-black/20 z-10"></div>

  <!-- Content -->
  <div class="relative z-20 max-w-7xl mx-auto px-6 h-full flex items-center">
    <div class="max-w-xl space-y-6 text-white">

      <p class="text-sm uppercase tracking-widest text-emerald-400 font-semibold">
        <?php echo htmlspecialchars($hero_tagline); ?>
      </p>

      <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
        <?php 
          $title_parts = explode(' of ', $hero_title);
          if(count($title_parts) == 2) {
            echo htmlspecialchars($title_parts[0]) . ' <br /><span class="text-emerald-400">of ' . htmlspecialchars($title_parts[1]) . '</span>';
          } else {
            echo htmlspecialchars($hero_title);
          }
        ?>
      </h1>

      <p class="text-base md:text-lg text-neutral-200">
        <?php echo htmlspecialchars($hero_description); ?>
      </p>

      <div class="flex gap-4 flex-wrap pt-4">
        <a href="<?php echo htmlspecialchars($publicJobsUrl); ?>"
           class="px-8 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-lg">
          Explore Careers
        </a>
        <a href="#about"
           class="px-8 py-3 rounded-full border border-white/40 text-white hover:bg-white/10 transition">
          About Us
        </a>
      </div>
    </div>
  </div>

  <!-- Slider Controls -->
  <div class="absolute z-30 bottom-10 right-10 flex gap-3">
    <button onclick="prevSlide()" class="w-11 h-11 rounded-full bg-white/80 hover:bg-white transition shadow">
      &#10094;
    </button>
    <button onclick="nextSlide()" class="w-11 h-11 rounded-full bg-white/80 hover:bg-white transition shadow">
      &#10095;
    </button>
  </div>

</section>

<script>
  let index = 0;
  const slider = document.getElementById('bgSlider');
  const slides = slider.children.length;

  function updateSlide() {
    slider.style.transform = `translateX(-${index * 100}%)`;
  }

  function nextSlide() {
    index = (index + 1) % slides;
    updateSlide();
  }

  function prevSlide() {
    index = (index - 1 + slides) % slides;
    updateSlide();
  }

  setInterval(nextSlide, 6000);
</script>



<!-- Login / Register Modals -->
<div id="loginModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
    <div class="flex justify-between items-start mb-2">
      <div>
        <h3 class="text-xl font-bold">Login</h3>
        <p class="text-sm text-neutral-500 mt-1">Sign in to apply and view your applications.</p>
      </div>
      <button onclick="closeLogin()" class="text-2xl text-neutral-500 hover:text-neutral-800">&times;</button>
    </div>
    <form id="loginForm" class="space-y-3 text-sm">
      <label class="block text-xs font-medium text-neutral-600">Email</label>
      <input name="email" type="email" placeholder="halimbawa@domain.com" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      <label class="block text-xs font-medium text-neutral-600">Password</label>
      <input name="password" type="password" placeholder="Ilagay ang password" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      <div class="text-xs text-neutral-500">Don't have an account? <button type="button" onclick="openRegisterFromLogin()" class="text-emerald-700 underline">Register</button></div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeLogin()" class="px-3 py-2 rounded-xl bg-neutral-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 text-white">Login</button>
      </div>
    </form>
  </div>
</div>

<div id="registerModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl">
    <div class="flex justify-between items-start mb-2">
      <div>
        <h3 class="text-xl font-bold">Register</h3>
        <p class="text-sm text-neutral-500 mt-1">Create an account to apply and receive updates from us.</p>
      </div>
      <button onclick="closeRegister()" class="text-2xl text-neutral-500 hover:text-neutral-800">&times;</button>
    </div>
    <form id="registerForm" class="space-y-3 text-sm">
      <input type="hidden" name="role" value="applicant">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div>
          <label class="block text-xs font-medium text-neutral-600">First name <span class="text-neutral-400">(Unang Pangalan)</span></label>
          <input name="firstName" placeholder="Juan" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
        </div>
        <div>
          <label class="block text-xs font-medium text-neutral-600">Last name <span class="text-neutral-400">(Apelyido)</span></label>
          <input name="lastName" placeholder="Dela Cruz" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Contact # <span class="text-neutral-400">(Telepono)</span></label>
        <input name="contact" placeholder="0917xxxxxxx" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Email <span class="text-neutral-400">(Email)</span></label>
        <input name="email" type="email" placeholder="halimbawa@domain.com" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Password <span class="text-neutral-400">(Password)</span></label>
        <input name="password" type="password" placeholder="Gumawa ng password" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeRegister()" class="px-3 py-2 rounded-xl bg-neutral-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 text-white">Register</button>
      </div>
    </form>
  </div>
</div>

<!-- Slider Script -->
<script>
  const slider = document.querySelector('.slider');
  const slides = document.querySelectorAll('.slider img');
  const prev = document.getElementById('prev');
  const next = document.getElementById('next');
  let index = 0;

  function showSlide(i) {
    if(i < 0) index = slides.length - 1;
    else if(i >= slides.length) index = 0;
    else index = i;
    slider.style.transform = `translateX(-${index * 100}%)`;
  }

  prev.addEventListener('click', () => showSlide(index - 1));
  next.addEventListener('click', () => showSlide(index + 1));

  setInterval(() => showSlide(index + 1), 5000);
</script>

<script>
  // Modal helpers
  function openLogin(){ document.getElementById('loginModal').classList.remove('hidden'); }
  function closeLogin(){ document.getElementById('loginModal').classList.add('hidden'); }
  function openRegister(){ document.getElementById('registerModal').classList.remove('hidden'); }
  function closeRegister(){ document.getElementById('registerModal').classList.add('hidden'); }
  function openRegisterFromLogin(){ closeLogin(); openRegister(); }

  document.addEventListener('DOMContentLoaded', ()=>{
    const btn = document.getElementById('openLogin');
    const btnM = document.getElementById('openLoginMobile');
    if(btn) btn.addEventListener('click', openLogin);
    if(btnM) btnM.addEventListener('click', openLogin);
    // user menu toggles and logout
    const userBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    if(userBtn && userDropdown){
      userBtn.addEventListener('click', ()=> userDropdown.classList.toggle('hidden'));
    }
    const userBtnM = document.getElementById('userMenuBtnMobile');
    const userDropdownM = document.getElementById('userMobileDropdown');
    if(userBtnM && userDropdownM){
      userBtnM.addEventListener('click', ()=> userDropdownM.classList.toggle('hidden'));
    }
    const logoutBtn = document.getElementById('logoutBtn');
    if(logoutBtn) logoutBtn.addEventListener('click', async ()=>{
      try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers: {'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({ icon:'success', title:'Logged out', text:'You have been logged out', timer:1200, showConfirmButton:false }); window.location.href='/welcome/home'; }catch(e){ Swal.fire('Error','Logout failed','error'); }
    });
    const logoutBtnM = document.getElementById('logoutBtnMobile');
    if(logoutBtnM) logoutBtnM.addEventListener('click', async ()=>{
      try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers: {'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({ icon:'success', title:'Logged out', text:'You have been logged out', timer:1200, showConfirmButton:false }); window.location.href='/welcome/home'; }catch(e){ Swal.fire('Error','Logout failed','error'); }
    });

    // login handler -> POST to backend/login.php
    const lf = document.getElementById('loginForm');
    if(lf) lf.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const form = new FormData(lf);
      const body = { email: form.get('email'), password: form.get('password') };
      try{
        const res = await fetch('../backend/login.php', { method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
        const j = await res.json();
        if(j.success){
          closeLogin();
          await Swal.fire({ icon: 'success', title: 'Login successful', text: 'You are now logged in', timer: 1400, showConfirmButton: false });
          if(j.role === 'admin'){ window.location.href = '/welcome/admin/dashboard'; } else { window.location.href = '/welcome/jobs'; }
        } else {
          Swal.fire({ icon: 'error', title: 'Login failed', text: j.message || 'Invalid credentials' });
        }
      }catch(err){ console.error(err); Swal.fire({ icon: 'error', title: 'Error', text: 'Login error' }); }
    });

    // register handler -> POST to backend/register.php (default role=applicant server-side)
    const rf = document.getElementById('registerForm');
    if(rf) rf.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const f = new FormData(rf);
      const body = { firstName: f.get('firstName'), lastName: f.get('lastName'), contact: f.get('contact'), email: f.get('email'), password: f.get('password') };
      try{
        const res = await fetch('../backend/register.php', { method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
        const j = await res.json();
        if(j.success){
          closeRegister();
          await Swal.fire({ icon: 'success', title: 'Account created', text: 'Your account was created successfully', timer: 1500, showConfirmButton: false });
          window.location.href = '/welcome/jobs';
        } else {
          Swal.fire({ icon: 'error', title: 'Registration failed', text: j.message || 'Unable to register' });
        }
      }catch(err){ console.error(err); Swal.fire({ icon: 'error', title: 'Error', text: 'Registration error' }); }
    });

    const cf = document.getElementById('contactForm');
    if(cf) cf.addEventListener('submit', (e)=>{ e.preventDefault(); alert('Message sent (demo)'); cf.reset(); });
  });
</script>

<!-- Optional Animation Styles -->
<style>
  .animate-fade-in {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeInUp 0.8s forwards;
  }
  .animate-fade-in.delay-100 { animation-delay: 0.1s; }
  .animate-fade-in.delay-200 { animation-delay: 0.2s; }
  .animate-fade-in.delay-300 { animation-delay: 0.3s; }

  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-pulse-slow {
    animation: pulse 8s infinite alternate;
  }

  @keyframes pulse {
    from { transform: scale(1); opacity: 0.3; }
    to { transform: scale(1.2); opacity: 0.5; }
  }
</style>


<!-- ================= WHY HR IS IMPORTANT ================= -->
<section id="why" class="bg-gray-50 py-28">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center mb-20">
      Why Human Resources is Important?
    </h2>

    <div class="grid md:grid-cols-3 gap-12">

      <!-- Card 1 -->
      <div class="relative group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transform transition duration-500 hover:scale-105">
        <div class="h-64 md:h-56 w-full overflow-hidden rounded-t-3xl relative">
          <img src="<?php echo htmlspecialchars($why_hr_1_img); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
               alt="Recruitment">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
        <div class="absolute -top-6 left-6 bg-gradient-to-tr from-amber-400 to-amber-600 text-white font-bold w-14 h-14 rounded-full flex items-center justify-center text-lg shadow-xl z-10">
          1
        </div>
        <div class="absolute -top-2 left-16 bg-white w-12 h-12 rounded-full flex items-center justify-center text-amber-500 shadow-lg z-10 group-hover:animate-bounce">
          <i class="fa-solid fa-user-plus"></i>
        </div>
        <div class="p-6 text-center bg-white">
          <h3 class="text-gray-900 text-xl md:text-2xl font-semibold mb-3">Recruitment & Retention</h3>
          <p class="text-gray-600 text-sm md:text-base">
            HR attracts, hires, and retains top talent, ensuring the right people are in the right roles to drive company success.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="relative group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transform transition duration-500 hover:scale-105">
        <div class="h-64 md:h-56 w-full overflow-hidden rounded-t-3xl relative">
          <img src="<?php echo htmlspecialchars($why_hr_2_img); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
               alt="Training">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
        <div class="absolute -top-6 left-6 bg-gradient-to-tr from-amber-400 to-amber-600 text-white font-bold w-14 h-14 rounded-full flex items-center justify-center text-lg shadow-xl z-10">
          2
        </div>
        <div class="absolute -top-2 left-16 bg-white w-12 h-12 rounded-full flex items-center justify-center text-amber-500 shadow-lg z-10 group-hover:animate-bounce">
          <i class="fa-solid fa-chalkboard-teacher"></i>
        </div>
        <div class="p-6 text-center bg-white">
          <h3 class="text-gray-900 text-xl md:text-2xl font-semibold mb-3">Training & Development</h3>
          <p class="text-gray-600 text-sm md:text-base">
            HR organizes training programs to enhance skills, boost productivity, and prepare employees for leadership roles.
          </p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="relative group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transform transition duration-500 hover:scale-105">
        <div class="h-64 md:h-56 w-full overflow-hidden rounded-t-3xl relative">
          <img src="<?php echo htmlspecialchars($why_hr_3_img); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
               alt="Satisfaction">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
        <div class="absolute -top-6 left-6 bg-gradient-to-tr from-amber-400 to-amber-600 text-white font-bold w-14 h-14 rounded-full flex items-center justify-center text-lg shadow-xl z-10">
          3
        </div>
        <div class="absolute -top-2 left-16 bg-white w-12 h-12 rounded-full flex items-center justify-center text-amber-500 shadow-lg z-10 group-hover:animate-bounce">
          <i class="fa-solid fa-smile"></i>
        </div>
        <div class="p-6 text-center bg-white">
          <h3 class="text-gray-900 text-xl md:text-2xl font-semibold mb-3">Employee Satisfaction</h3>
          <p class="text-gray-600 text-sm md:text-base">
            HR creates a positive work environment, manages benefits, and resolves conflicts to improve morale and retention.
          </p>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="relative group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transform transition duration-500 hover:scale-105">
        <div class="h-64 md:h-56 w-full overflow-hidden rounded-t-3xl relative">
          <img src="<?php echo htmlspecialchars($why_hr_4_img); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
               alt="Compliance">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
        <div class="absolute -top-6 left-6 bg-gradient-to-tr from-amber-400 to-amber-600 text-white font-bold w-14 h-14 rounded-full flex items-center justify-center text-lg shadow-xl z-10">
          4
        </div>
        <div class="absolute -top-2 left-16 bg-white w-12 h-12 rounded-full flex items-center justify-center text-amber-500 shadow-lg z-10 group-hover:animate-bounce">
          <i class="fa-solid fa-gavel"></i>
        </div>
        <div class="p-6 text-center bg-white">
          <h3 class="text-gray-900 text-xl md:text-2xl font-semibold mb-3">Compliance</h3>
          <p class="text-gray-600 text-sm md:text-base">
            HR ensures compliance with labor laws, develops policies, and prevents potential lawsuits or penalties.
          </p>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="relative group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transform transition duration-500 hover:scale-105">
        <div class="h-64 md:h-56 w-full overflow-hidden rounded-t-3xl relative">
          <img src="<?php echo htmlspecialchars($why_hr_5_img); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
               alt="Performance">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
        <div class="absolute -top-6 left-6 bg-gradient-to-tr from-amber-400 to-amber-600 text-white font-bold w-14 h-14 rounded-full flex items-center justify-center text-lg shadow-xl z-10">
          5
        </div>
        <div class="absolute -top-2 left-16 bg-white w-12 h-12 rounded-full flex items-center justify-center text-amber-500 shadow-lg z-10 group-hover:animate-bounce">
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="p-6 text-center bg-white">
          <h3 class="text-gray-900 text-xl md:text-2xl font-semibold mb-3">Performance Management</h3>
          <p class="text-gray-600 text-sm md:text-base">
            HR implements performance appraisal systems to evaluate employees, identify improvements, and recognize top performers.
          </p>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="relative group bg-white rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transform transition duration-500 hover:scale-105">
        <div class="h-64 md:h-56 w-full overflow-hidden rounded-t-3xl relative">
          <img src="<?php echo htmlspecialchars($why_hr_6_img); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
               alt="Strategy">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>
        <div class="absolute -top-6 left-6 bg-gradient-to-tr from-amber-400 to-amber-600 text-white font-bold w-14 h-14 rounded-full flex items-center justify-center text-lg shadow-xl z-10">
          6
        </div>
        <div class="absolute -top-2 left-16 bg-white w-12 h-12 rounded-full flex items-center justify-center text-amber-500 shadow-lg z-10 group-hover:animate-bounce">
          <i class="fa-solid fa-rocket"></i>
        </div>
        <div class="p-6 text-center bg-white">
          <h3 class="text-gray-900 text-xl md:text-2xl font-semibold mb-3">Strategic Management</h3>
          <p class="text-gray-600 text-sm md:text-base">
            HR aligns workforce with company goals, manages organizational changes, and helps in strategic decision-making.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>




<!-- ================= ABOUT / MISSION / VISION / VALUES ================= -->
<section id="about" class="bg-gray-50 py-32 relative overflow-hidden">
  <!-- Decorative background blobs -->
  <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-100 rounded-full filter blur-3xl opacity-30 animate-pulse-slow"></div>
  <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-emerald-200 rounded-full filter blur-3xl opacity-20 animate-pulse-slow"></div>

  <div class="max-w-7xl mx-auto px-6 space-y-20">

    <!-- HEADER: OUR CODE OF ETHICS -->
    <div class="text-center max-w-3xl mx-auto">
      <h2 class="text-5xl md:text-6xl font-extrabold text-gray-800 mb-6">Our Code of Ethics</h2>
      <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
        Our Code of Ethics sets the principles and standards that guide our conduct and decisions as an organization.
      </p>
    </div>

    <!-- CARDS: MISSION / VISION / VALUES -->
    <div class="grid md:grid-cols-3 gap-12">

      <!-- MISSION -->
      <div class="group relative rounded-3xl p-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 shadow-2xl hover:shadow-3xl transform hover:-translate-y-4 transition-all duration-500">
        <!-- Icon circle -->
        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-500">
          <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.567 3-3.5S13.657 1 12 1 9 2.567 9 4.5 10.343 8 12 8zM6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"></path>
          </svg>
        </div>
        <h3 class="mt-14 text-2xl font-bold text-emerald-700 text-center mb-4">MISSION</h3>
        <p class="text-gray-600 text-center text-base leading-relaxed">
          Become a trusted partner helping clients maximize performance by aligning human resources with strategic objectives through ethical and effective HR services.
        </p>
      </div>

      <!-- VISION -->
      <div class="group relative rounded-3xl p-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 shadow-2xl hover:shadow-3xl transform hover:-translate-y-4 transition-all duration-500 mt-12 md:mt-0">
        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-500">
          <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3-1.567 3-3.5S13.657 1 12 1 9 2.567 9 4.5 10.343 8 12 8zM6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"></path>
          </svg>
        </div>
        <h3 class="mt-14 text-2xl font-bold text-emerald-700 text-center mb-4">VISION</h3>
        <p class="text-gray-600 text-center text-base leading-relaxed">
          Empower businesses with a skilled, motivated workforce aligned to strategic goals; be recognized as a leading HR agency prioritizing growth and well-being.
        </p>
      </div>

      <!-- VALUES -->
      <div class="group relative rounded-3xl p-12 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 shadow-2xl hover:shadow-3xl transform hover:-translate-y-4 transition-all duration-500 mt-24 md:mt-0">
        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-500">
          <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h3 class="mt-14 text-2xl font-bold text-emerald-700 text-center mb-4">VALUES</h3>
        <p class="text-gray-600 text-center text-base leading-relaxed">
          Respect · Integrity · Equality · Development · Work-Life Balance · Engagement · Improvement
        </p>
      </div>

    </div>
  </div>
</section>


<!-- ================= HOW IT WORKS ================= -->
<section id="how" class="bg-neutral-50 py-28 relative overflow-hidden">
  <!-- Decorative background shapes -->
  <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-100 rounded-full opacity-40 animate-pulse-slow"></div>
  <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-200 rounded-full opacity-30 animate-pulse-slow"></div>

  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-20 relative inline-block">
      How It Works – Recruitment and Selection Criteria
      <span class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-emerald-600 rounded-full"></span>
    </h2>

    <div class="relative max-w-6xl mx-auto">
      <!-- Timeline line -->
      <div class="hidden md:block absolute top-1/2 left-0 right-0 h-1 bg-emerald-200 z-0"></div>

      <div class="flex flex-col md:flex-row md:justify-between gap-12 relative z-10">
        <!-- Step Card -->
        <div class="flex-1 bg-white p-8 rounded-2xl shadow-xl text-center transform transition hover:-translate-y-2 hover:shadow-2xl">
          <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-lg">1</div>
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14v7"></path></svg>
            <h4 class="font-semibold text-emerald-700 text-lg">Job Posting</h4>
            <p class="text-sm text-neutral-600">Resumes collected via social media, job fairs, walk-ins, or email.</p>
          </div>
        </div>

        <div class="flex-1 bg-white p-8 rounded-2xl shadow-xl text-center transform transition hover:-translate-y-2 hover:shadow-2xl">
          <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-lg">2</div>
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M6 21h12"></path></svg>
            <h4 class="font-semibold text-emerald-700 text-lg">Screening</h4>
            <p class="text-sm text-neutral-600">Shortlist candidates who meet the basic job requirements.</p>
          </div>
        </div>

        <div class="flex-1 bg-white p-8 rounded-2xl shadow-xl text-center transform transition hover:-translate-y-2 hover:shadow-2xl">
          <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-lg">3</div>
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
            <h4 class="font-semibold text-emerald-700 text-lg">Interview / Tests</h4>
            <p class="text-sm text-neutral-600">Interviews in-person, phone, or video; include assessments as needed.</p>
          </div>
        </div>

        <div class="flex-1 bg-white p-8 rounded-2xl shadow-xl text-center transform transition hover:-translate-y-2 hover:shadow-2xl">
          <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-lg">4</div>
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 0a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4a2 2 0 012-2h12z"></path></svg>
            <h4 class="font-semibold text-emerald-700 text-lg">Job Offer</h4>
            <p class="text-sm text-neutral-600">Offer includes salary, benefits, start date; client final interview as needed.</p>
          </div>
        </div>

        <div class="flex-1 bg-white p-8 rounded-2xl shadow-xl text-center transform transition hover:-translate-y-2 hover:shadow-2xl">
          <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-lg">5</div>
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5v14"></path></svg>
            <h4 class="font-semibold text-emerald-700 text-lg">Onboarding</h4>
            <p class="text-sm text-neutral-600">Complete paperwork, orientation, training, and integration for new hires.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .animate-pulse-slow {
    animation: pulse 8s infinite alternate;
  }

  @keyframes pulse {
    from { transform: scale(1); opacity: 0.3; }
    to { transform: scale(1.2); opacity: 0.5; }
  }
</style>


<!-- ================= TEAM & ORG CHART ================= -->

<section id="team" class="bg-gray-50 py-24">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <div class="text-center max-w-3xl mx-auto mb-8">
      <h2 class="text-5xl md:text-6xl font-extrabold text-gray-800 mb-6">Who We Are</h2>
      <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
        Phil-First Human Resources & Services, Inc. delivers professional HR and staffing solutions across industries. We focus on ethical hiring practices, workforce excellence, and building long-term partnerships with both clients and candidates.
      </p>
    </div>

    <h2 class="text-4xl font-bold mb-20 text-gray-900">Organization Chart</h2>

    <!-- LEVEL 1: PRESIDENT -->
    <div class="flex justify-center mb-20 relative">
      <div class="relative flex items-center gap-5 bg-white shadow-2xl rounded-xl p-6 w-96 hover:scale-105 transition-transform">
        <div class="w-1 bg-gradient-to-b from-emerald-500 to-green-300 rounded-full absolute left-0 top-0 h-full"></div>
        <img src="<?php echo htmlspecialchars($org_president_img); ?>" class="w-16 h-16 rounded-full">
        <div class="text-left">
          <h4 class="font-semibold text-lg text-gray-900">Rodel P. Torio</h4>
          <p class="text-sm text-emerald-600">President</p>
        </div>
      </div>
    </div>

    <!-- LEVEL 2: CEO / GM / Secretary -->
    <div class="relative mb-20 flex justify-center gap-12">
      <!-- Connector curve -->
      <svg class="absolute top-0 w-full h-16 left-0" xmlns="http://www.w3.org/2000/svg">
        <path d="M50,0 C50,40 950,40 950,0" stroke="#CBD5E1" stroke-width="2" fill="transparent"/>
      </svg>

      <div class="flex flex-col items-center">
        <div class="w-1 h-16 bg-gray-300"></div>
        <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-5 border-l-4 border-emerald-500 w-80 hover:scale-105 transition-transform">
          <img src="<?php echo htmlspecialchars($org_ceo_img); ?>" class="w-12 h-12 rounded-full">
          <div class="text-left">
            <h4 class="font-semibold text-gray-900">Carlitos S. Ruiz</h4>
            <p class="text-sm text-emerald-600">CEO</p>
          </div>
        </div>
      </div>

      <div class="flex flex-col items-center">
        <div class="w-1 h-16 bg-gray-300"></div>
        <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-5 border-l-4 border-emerald-500 w-80 hover:scale-105 transition-transform">
          <img src="<?php echo htmlspecialchars($org_gm_img); ?>" class="w-12 h-12 rounded-full">
          <div class="text-left">
            <h4 class="font-semibold text-gray-900">Sharon C. Alcantara</h4>
            <p class="text-sm text-emerald-600">GM / Treasurer</p>
          </div>
        </div>
      </div>

      <div class="flex flex-col items-center">
        <div class="w-1 h-16 bg-gray-300"></div>
        <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-5 border-l-4 border-emerald-500 w-80 hover:scale-105 transition-transform">
          <img src="<?php echo htmlspecialchars($org_secretary_img); ?>" class="w-12 h-12 rounded-full">
          <div class="text-left">
            <h4 class="font-semibold text-gray-900">Hazel Grace D. Caluya</h4>
            <p class="text-sm text-emerald-600">Corporate Secretary</p>
          </div>
        </div>
      </div>
    </div>

    <!-- LEVEL 3: HR / Accounting / Marketing -->
    <div class="relative mb-20 grid md:grid-cols-3 gap-12 justify-center">
      <!-- Connector -->
      <svg class="absolute top-0 left-1/2 transform -translate-x-1/2 w-3/4 h-16" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,16 C96,0 576,0 672,16" stroke="#CBD5E1" stroke-width="2" fill="transparent"/>
      </svg>

      <div class="flex flex-col items-center">
        <div class="w-1 h-16 bg-gray-300"></div>
        <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-5 border-l-4 border-emerald-500 w-72 hover:scale-105 transition-transform">
          <img src="<?php echo htmlspecialchars($org_hr_supervisor_img); ?>" class="w-12 h-12 rounded-full">
          <div class="text-left">
            <h4 class="font-semibold text-gray-900">Marilyn Pabalate</h4>
            <p class="text-sm text-emerald-600">HR Supervisor</p>
          </div>
        </div>
      </div>

      <div class="flex flex-col items-center">
        <div class="w-1 h-16 bg-gray-300"></div>
        <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-5 border-l-4 border-emerald-500 w-72 hover:scale-105 transition-transform">
          <img src="<?php echo htmlspecialchars($org_accounting_img); ?>" class="w-12 h-12 rounded-full">
          <div class="text-left">
            <h4 class="font-semibold text-gray-900">Ma. Christina Ricamara</h4>
            <p class="text-sm text-emerald-600">Accounting Officer</p>
          </div>
        </div>
      </div>

      <div class="flex flex-col items-center">
        <div class="w-1 h-16 bg-gray-300"></div>
        <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-5 border-l-4 border-emerald-500 w-72 hover:scale-105 transition-transform">
          <img src="<?php echo htmlspecialchars($org_marketing_img); ?>" class="w-12 h-12 rounded-full">
          <div class="text-left">
            <h4 class="font-semibold text-gray-900">Adelyn Broquiza</h4>
            <p class="text-sm text-emerald-600">Marketing Officer</p>
          </div>
        </div>
      </div>
    </div>

    <!-- LEVEL 4: Staff -->
    <div class="grid md:grid-cols-5 gap-10 justify-center">
      <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-4 border-l-4 border-emerald-500 w-64 hover:scale-105 transition-transform">
        <img src="<?php echo htmlspecialchars($org_hr_coordinator_img); ?>" class="w-12 h-12 rounded-full">
        <div class="text-left">
          <h4 class="font-semibold text-gray-900">Robert James Cuasay</h4>
          <p class="text-sm text-emerald-600">HR Coordinator</p>
        </div>
      </div>
      <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-4 border-l-4 border-emerald-500 w-64 hover:scale-105 transition-transform">
        <img src="<?php echo htmlspecialchars($org_accounting_asst_img); ?>" class="w-12 h-12 rounded-full">
        <div class="text-left">
          <h4 class="font-semibold text-gray-900">Ma. Arlene Carbonilla</h4>
          <p class="text-sm text-emerald-600">Accounting Assistant</p>
        </div>
      </div>
      <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-4 border-l-4 border-emerald-500 w-64 hover:scale-105 transition-transform">
        <img src="<?php echo htmlspecialchars($org_housekeeping_img); ?>" class="w-12 h-12 rounded-full">
        <div class="text-left">
          <h4 class="font-semibold text-gray-900">Jocelyn Estrada</h4>
          <p class="text-sm text-emerald-600">Housekeeping Supervisor</p>
        </div>
      </div>
      <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-4 border-l-4 border-emerald-500 w-64 hover:scale-105 transition-transform">
        <img src="<?php echo htmlspecialchars($org_exec_housekeeper_img); ?>" class="w-12 h-12 rounded-full">
        <div class="text-left">
          <h4 class="font-semibold text-gray-900">Tata Ayunan</h4>
          <p class="text-sm text-emerald-600">Executive Housekeeper</p>
        </div>
      </div>
      <div class="flex items-center gap-4 bg-white shadow-lg rounded-xl p-4 border-l-4 border-emerald-500 w-64 hover:scale-105 transition-transform">
        <img src="<?php echo htmlspecialchars($org_branch_staff_img); ?>" class="w-12 h-12 rounded-full">
        <div class="text-left">
          <h4 class="font-semibold text-gray-900">Branch Staff</h4>
          <p class="text-sm text-emerald-600">Pangasinan Branch</p>
        </div>
      </div>
    </div>

  </div>
</section>


<!-- ================= CONTACT ================= -->
<!-- CONTACT (moved to bottom) -->


<!-- ================= FOOTER ================= -->
<section id="services" class="bg-neutral-50 py-32">
  <div class="max-w-7xl mx-auto px-6">

    <!-- Header -->
    <div class="max-w-3xl mb-24 text-center">
      <h2 class="text-4xl md:text-5xl font-bold text-neutral-900 leading-tight">
        Our HR Services
      </h2>
      <p class="mt-4 text-neutral-600 text-lg">
        Strategic human resource solutions crafted to enhance operational efficiency, compliance, and workforce excellence.
      </p>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16">

      <!-- Service Item -->
      <div class="relative bg-white border border-neutral-200 rounded-3xl p-10 hover:shadow-2xl transition duration-300 group">
        <div class="absolute -top-10 left-10 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-20 flex items-center justify-center text-4xl font-bold text-white">
          01
        </div>
        <h3 class="text-xl font-semibold text-neutral-900 mb-4 group-hover:text-emerald-600 transition">
          Recruitment & Staffing
        </h3>
        <p class="text-neutral-600 text-sm leading-relaxed">
          End-to-end recruitment services including talent sourcing, screening, interviewing, and placement tailored to your business goals.
        </p>
      </div>

      <!-- Service Item -->
      <div class="relative bg-white border border-neutral-200 rounded-3xl p-10 hover:shadow-2xl transition duration-300 group">
        <div class="absolute -top-10 left-10 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-20 flex items-center justify-center text-4xl font-bold text-white">
          02
        </div>
        <h3 class="text-xl font-semibold text-neutral-900 mb-4 group-hover:text-emerald-600 transition">
          Payroll Management
        </h3>
        <p class="text-neutral-600 text-sm leading-relaxed">
          Accurate, compliant payroll processing including salaries, deductions, taxes, benefits, and reporting.
        </p>
      </div>

      <!-- Service Item -->
      <div class="relative bg-white border border-neutral-200 rounded-3xl p-10 hover:shadow-2xl transition duration-300 group">
        <div class="absolute -top-10 left-10 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-20 flex items-center justify-center text-4xl font-bold text-white">
          03
        </div>
        <h3 class="text-xl font-semibold text-neutral-900 mb-4 group-hover:text-emerald-600 transition">
          Benefits Administration
        </h3>
        <p class="text-neutral-600 text-sm leading-relaxed">
          Management of employee benefits, insurance, leaves, and incentive programs with full compliance.
        </p>
      </div>

      <!-- Service Item -->
      <div class="relative bg-white border border-neutral-200 rounded-3xl p-10 hover:shadow-2xl transition duration-300 group">
        <div class="absolute -top-10 left-10 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-20 flex items-center justify-center text-4xl font-bold text-white">
          04
        </div>
        <h3 class="text-xl font-semibold text-neutral-900 mb-4 group-hover:text-emerald-600 transition">
          Training & Development
        </h3>
        <p class="text-neutral-600 text-sm leading-relaxed">
          Structured programs to upskill employees, improve performance, and boost workforce capability.
        </p>
      </div>

      <!-- Service Item -->
      <div class="relative bg-white border border-neutral-200 rounded-3xl p-10 hover:shadow-2xl transition duration-300 group">
        <div class="absolute -top-10 left-10 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-20 flex items-center justify-center text-4xl font-bold text-white">
          05
        </div>
        <h3 class="text-xl font-semibold text-neutral-900 mb-4 group-hover:text-emerald-600 transition">
          Performance Management
        </h3>
        <p class="text-neutral-600 text-sm leading-relaxed">
          Design and implementation of performance evaluation frameworks that align outcomes with business objectives.
        </p>
      </div>

      <!-- Service Item -->
      <div class="relative bg-white border border-neutral-200 rounded-3xl p-10 hover:shadow-2xl transition duration-300 group">
        <div class="absolute -top-10 left-10 w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 opacity-20 flex items-center justify-center text-4xl font-bold text-white">
          06
        </div>
        <h3 class="text-xl font-semibold text-neutral-900 mb-4 group-hover:text-emerald-600 transition">
          Employee Relations
        </h3>
        <p class="text-neutral-600 text-sm leading-relaxed">
          Proactive engagement and conflict-resolution strategies to foster a collaborative and positive workplace culture.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ================= CONTACT (Details + Map Only) ================= -->
<section id="contact" class="bg-gradient-to-b from-white to-emerald-50 py-20">
  <div class="max-w-7xl mx-auto px-6">
    
    <!-- Header -->
    <div class="max-w-2xl mb-12">
      <h2 class="text-4xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($contact_title); ?></h2>
      <p class="text-neutral-600">
        <?php echo htmlspecialchars($contact_description); ?>
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">

      <!-- Contact Details -->
      <div class="bg-white rounded-2xl border shadow-sm p-8 flex flex-col justify-between">
        <div>
          <h3 class="text-xl font-semibold text-gray-900 mb-6">Contact Information</h3>

          <div class="space-y-5 text-sm text-neutral-700">
            <div>
              <p class="font-medium text-gray-900">Phone</p>
              <a href="tel:<?php echo str_replace([' ', '+'], '', $contact_phone); ?>" class="text-emerald-700 hover:underline">
                <?php echo htmlspecialchars($contact_phone); ?>
              </a>
            </div>

            <div>
              <p class="font-medium text-gray-900">Email</p>
              <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="text-emerald-700 hover:underline">
                <?php echo htmlspecialchars($contact_email); ?>
              </a>
            </div>

            <div>
              <p class="font-medium text-gray-900">Office Address</p>
              <p><?php echo htmlspecialchars($contact_address); ?></p>
            </div>

            <div>
              <p class="font-medium text-gray-900">Office Hours</p>
              <p><?php echo $contact_hours; ?></p>
            </div>
          </div>
        </div>

        <div class="mt-8 pt-6 border-t text-xs text-neutral-500">
          <?php echo htmlspecialchars($contact_note); ?>
        </div>
      </div>

      <!-- Map -->
      <div class="bg-white rounded-2xl border shadow-sm p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 px-2">
          Our Location
        </h3>

        <div class="w-full h-80 rounded-xl overflow-hidden border">
          <iframe
            src="https://www.google.com/maps?q=Phil-First+HR+Services+Pasig+City&output=embed"
            class="w-full h-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
          </iframe>
        </div>

        <p class="text-xs text-neutral-500 mt-3 px-2">
          Map shown is for reference purposes only.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ================= AUTO-RELOAD SCRIPT ================= -->
<script>
(function() {
  // Track last update timestamp
  let lastUpdate = null;
  let isReloading = false;
  
  // Check for updates every 5 seconds
  async function checkForUpdates() {
    if (isReloading) return;
    
    try {
      const response = await fetch('/backend/cms_updates.php');
      const data = await response.json();
      
      if (data.success) {
        const currentUpdate = data.last_update;
        
        // First time - just store the timestamp
        if (lastUpdate === null) {
          lastUpdate = currentUpdate;
          return;
        }
        
        // Content was updated
        if (currentUpdate > lastUpdate) {
          isReloading = true;
          
          // Reload page after short delay
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      }
    } catch (error) {
      console.error('Failed to check for updates:', error);
    }
  }
  
  // Start checking for updates
  checkForUpdates();
  setInterval(checkForUpdates, 5000); // Check every 5 seconds
})();
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
