<?php session_start(); ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Account Settings</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style> body{ font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial; } .swal2-popup { animation-duration: 0.2s !important; } </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 text-neutral-800 antialiased">
<?php if(empty($_SESSION['user'])){ header('Location: /welcome/jobs'); exit; } ?>

<!-- Shared header -->
<?php include __DIR__ . '/partials/header.php'; ?>

<!-- Hero -->
<section class="relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-sky-600 to-emerald-600 -z-10"></div>
  <div class="absolute inset-0 opacity-20 -z-5" style="background-image: url('https://images.unsplash.com/photo-1521747116042-5a810fda9664?auto=format&fit=crop&w=1600&q=60'); background-size:cover; background-position:center; filter: blur(2px) brightness(.6);"></div>
  <div class="max-w-7xl mx-auto px-6 py-20 sm:py-24 text-white relative">
    <h1 class="text-4xl sm:text-5xl font-extrabold">Account Settings</h1>
    <p class="mt-3 text-lg max-w-2xl text-emerald-50/90">Manage your profile, update your contact details, and secure your account.</p>
    <div class="mt-6">
      <a href="/welcome/my-applications" class="inline-block btn-primary px-5 py-3 rounded-lg">My Applications</a>
    </div>
  </div>
</section>

<div class="max-w-3xl mx-auto p-6">
  
  

  <h1 class="text-2xl font-bold mb-4">Account Settings</h1>
  <div class="bg-white rounded-xl p-6 shadow mb-6">
    <h2 class="font-semibold mb-3">Profile</h2>
    <form id="profileForm" enctype="multipart/form-data">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-neutral-600">First name</label>
          <input name="firstName" id="firstName" pattern="[a-zA-Z\s'-]+" title="Letters, spaces, hyphens, and apostrophes only" class="w-full rounded border px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm text-neutral-600">Last name</label>
          <input name="lastName" id="lastName" pattern="[a-zA-Z\s'-]+" title="Letters, spaces, hyphens, and apostrophes only" class="w-full rounded border px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm text-neutral-600">Email</label>
          <input name="email" id="email" type="email" class="w-full rounded border px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm text-neutral-600">Phone</label>
          <input name="contact" id="contact" pattern="0\d{10}" title="11 digits starting with 0" maxlength="11" class="w-full rounded border px-3 py-2" />
        </div>
      </div>
      <div class="mt-4">
        <label class="block text-sm text-neutral-600">Profile picture</label>
        <div class="flex items-center gap-4 mt-2">
          <img id="avatarPreview" src="" alt="avatar" class="w-16 h-16 rounded-md object-cover border" />
          <input type="file" name="avatar" id="avatar" accept="image/*" />
        </div>
      </div>
      <div class="mt-4 flex gap-2">
        <button type="submit" class="px-4 py-2 bg-emerald-700 text-white rounded">Save profile</button>
        <button type="button" id="cancelProfile" class="px-4 py-2 rounded border">Cancel</button>
      </div>
    </form>
  </div>

  <div class="bg-white rounded-xl p-6 shadow">
    <h2 class="font-semibold mb-3">Change password</h2>
    <form id="passwordForm">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-neutral-600">Current password</label>
          <input name="currentPassword" id="currentPassword" type="password" class="w-full rounded border px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm text-neutral-600">New password</label>
          <input name="newPassword" id="newPassword" type="password" class="w-full rounded border px-3 py-2" />
        </div>
      </div>
      <div class="mt-4 flex gap-2">
        <button type="submit" class="px-4 py-2 bg-emerald-700 text-white rounded">Change password</button>
      </div>
    </form>
  </div>
</div>

<script>
// Validation helpers
function validateName(name) {
  const nameRegex = /^[a-zA-Z\s'-]+$/;
  return nameRegex.test(name.trim());
}

function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email.trim());
}

function validatePhone(phone) {
  const phoneRegex = /^0\d{10}$/;
  return phoneRegex.test(phone.trim());
}

// populate fields from DB (fallback to session)
<?php
$dbUser = null;
if(!empty($_SESSION['user']['id'])){
  try{
    require_once __DIR__ . '/../backend/config.php';
    $stmt = $pdo->prepare('SELECT id, first_name, last_name, email, contact, profile_picture FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([intval($_SESSION['user']['id'])]);
    $dbUser = $stmt->fetch();
  }catch(Exception $e){
    // ignore and fallback to session
  }
}
?>
const user = <?php echo json_encode($dbUser ?: ($_SESSION['user'] ?? [])); ?> || {};
document.getElementById('firstName').value = user.first_name || '';
document.getElementById('lastName').value = user.last_name || '';
document.getElementById('email').value = user.email || '';
document.getElementById('contact').value = user.contact || '';
const avatarPreview = document.getElementById('avatarPreview');
if(user.profile_picture){ avatarPreview.src = user.profile_picture; } else { avatarPreview.src = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect width=%22100%22 height=%22100%22 fill=%22%23e5e7eb%22/%3E%3Ccircle cx=%2250%22 cy=%2230%22 r=%2215%22 fill=%22%239ca3af%22/%3E%3Cellipse cx=%2250%22 cy=%2275%22 rx=%2225%22 ry=%2220%22 fill=%22%239ca3af%22/%3E%3C/svg%3E'; }

document.getElementById('avatar').addEventListener('change', (e)=>{
  const f = e.target.files && e.target.files[0];
  if(!f) return;
  const url = URL.createObjectURL(f);
  avatarPreview.src = url;
});

document.getElementById('profileForm').addEventListener('submit', async (ev)=>{
  ev.preventDefault();
  const firstName = document.getElementById('firstName').value;
  const lastName = document.getElementById('lastName').value;
  const email = document.getElementById('email').value;
  const contact = document.getElementById('contact').value;
  
  // Validate first name
  if(firstName && !validateName(firstName)) {
    Swal.fire({ icon: 'error', title: 'Invalid First Name', text: 'First name should contain letters, spaces, hyphens, or apostrophes only', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    return;
  }
  
  // Validate last name
  if(lastName && !validateName(lastName)) {
    Swal.fire({ icon: 'error', title: 'Invalid Last Name', text: 'Last name should contain letters, spaces, hyphens, or apostrophes only', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    return;
  }
  
  // Validate email
  if(email && !validateEmail(email)) {
    Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email address', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    return;
  }
  
  // Validate phone number
  if(contact && !validatePhone(contact)) {
    Swal.fire({ icon: 'error', title: 'Invalid Phone Number', text: 'Phone number must be 11 digits and start with 0 (e.g., 09171234567)', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    return;
  }
  
  const fd = new FormData(ev.target);
  Swal.fire({ title:'Saving...', text:'Please wait', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
  try{
    const res = await fetch('../backend/update_profile.php', { method: 'POST', credentials:'same-origin', body: fd });
    const j = await res.json();
    if(!j.success) return Swal.fire({ title:'Error', text: j.message || 'Unable to update', icon:'error', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    Swal.fire({ title:'Saved', text:'Profile updated', icon:'success', timer: 1500, timerProgressBar: true, showConfirmButton: false });
    // update avatar image(s) in navbar and preview (cache-bust to be safe)
    if(j.profile_picture){
      const src = j.profile_picture + '?v=' + Date.now();
      avatarPreview.src = src;
      document.querySelectorAll('.user-avatar').forEach(img=>{ img.src = src; });
    }
    // update contact input if returned
    if(j.contact !== undefined && j.contact !== null){
      const c = document.getElementById('contact'); if(c) c.value = j.contact;
    }
    // update first name display in header buttons
    if(j.first_name){
      // try spans inside userMenuBtn
      document.querySelectorAll('#userMenuBtn span, #userMenuBtnMobile span').forEach(el=> el.textContent = j.first_name);
      // fallback: replace text node inside buttons if no span present
      ['#userMenuBtn', '#userMenuBtnMobile'].forEach(sel=>{
        document.querySelectorAll(sel).forEach(btn=>{
          try{
            const sp = btn.querySelector('span');
            if(sp) return; // already handled
            // set first text node to the new name, preserve other children (like svg)
            for(let i=0;i<btn.childNodes.length;i++){
              const n = btn.childNodes[i];
              if(n.nodeType === Node.TEXT_NODE){ n.nodeValue = j.first_name + ' '; break; }
            }
          }catch(e){}
        });
      });
    }
  }catch(err){ console.error(err); Swal.fire({ title:'Error', text:'Server error', icon:'error', timer: 2000, timerProgressBar: true, showConfirmButton: false }); }
});

document.getElementById('passwordForm').addEventListener('submit', async (ev)=>{
  ev.preventDefault();
  const cur = document.getElementById('currentPassword').value;
  const nw = document.getElementById('newPassword').value;
  Swal.fire({ title:'Changing password...', text:'Please wait', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
  try{
    const res = await fetch('../backend/change_password.php', { method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ currentPassword: cur, newPassword: nw }) });
    const j = await res.json();
    if(!j.success) return Swal.fire({ title:'Error', text: j.message || 'Unable to change password', icon:'error', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    Swal.fire({ title:'Updated', text:'Password changed', icon:'success', timer: 1500, timerProgressBar: true, showConfirmButton: false });
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
  }catch(err){ console.error(err); Swal.fire({ title:'Error', text:'Server error', icon:'error', timer: 2000, timerProgressBar: true, showConfirmButton: false }); }
});

document.getElementById('cancelProfile').addEventListener('click', ()=> window.location.href = '/welcome/my-applications');
// small dropdown and logout handlers (enable user menu on this page)
document.addEventListener('DOMContentLoaded', ()=>{
  const btn = document.getElementById('userMenuBtn');
  const dd = document.getElementById('userDropdown');
  if(btn && dd) btn.addEventListener('click', ()=> dd.classList.toggle('hidden'));
  const btnM = document.getElementById('userMenuBtnMobile');
  const ddM = document.getElementById('userMobileDropdown');
  if(btnM && ddM) btnM.addEventListener('click', ()=> ddM.classList.toggle('hidden'));
  const logout = document.getElementById('logoutBtn');
  if(logout) logout.addEventListener('click', async ()=>{ try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({icon:'success', title:'Logged out', timer: 800, timerProgressBar: true, showConfirmButton:false}); window.location.href='/welcome/home'; }catch(e){ Swal.fire({ title:'Error', text:'Logout failed', icon:'error', timer: 1500, timerProgressBar: true, showConfirmButton: false }); } });
  const logoutMobile = document.getElementById('logoutBtnMobile');
  if(logoutMobile) logoutMobile.addEventListener('click', async ()=>{ try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({icon:'success', title:'Logged out', timer: 800, timerProgressBar: true, showConfirmButton:false}); window.location.href='/welcome/home'; }catch(e){ Swal.fire({ title:'Error', text:'Logout failed', icon:'error', timer: 1500, timerProgressBar: true, showConfirmButton: false }); } });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
