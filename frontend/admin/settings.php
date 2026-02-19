<?php include 'header_clean.php'; ?>

<?php
$admin = $_SESSION['user'] ?? [];
$first = $admin['first_name'] ?? '';
$last = $admin['last_name'] ?? '';
$email = $admin['email'] ?? '';
$contact = $admin['contact'] ?? '';
$avatar = $admin['profile_picture'] ?? '';
$fullName = trim($first . ' ' . $last);
?>

<!-- ================= ADMIN SETTINGS HEADER ================= -->
<div class="bg-white shadow-lg rounded-2xl p-6 mb-6">
  <h2 class="text-2xl font-semibold text-gray-800 mb-2">Admin Settings</h2>
  <p class="text-gray-500">Update your profile details and change your password.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <!-- Admin Details -->
  <div class="bg-white shadow-lg rounded-2xl p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Admin Details</h3>
    <div id="profileMsg" class="hidden text-sm mb-4"></div>
    <form id="profileForm" class="space-y-4" enctype="multipart/form-data">
      <div class="flex items-center gap-4">
        <img id="profileAvatar" src="<?php echo $avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($fullName ?: 'Admin') . '&background=ffffff&color=198754'; ?>" class="w-16 h-16 rounded-full object-cover border" alt="avatar">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
          <input type="file" name="avatar" id="profileAvatarInput" accept="image/jpeg,image/png,image/webp" class="text-sm">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
          <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($first); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
          <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($last); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
        <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($contact); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" />
      </div>

      <div class="flex justify-end">
        <button type="submit" id="profileSaveBtn" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm">Save Details</button>
      </div>
    </form>
  </div>

  <!-- Change Password -->
  <div class="bg-white shadow-lg rounded-2xl p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Change Password</h3>
    <div id="passwordMsg" class="hidden text-sm mb-4"></div>
    <form id="passwordForm" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
        <input type="password" id="currentPassword" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" required />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
        <input type="password" id="newPassword" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" required />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
        <input type="password" id="confirmPassword" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm" required />
      </div>
      <div class="flex justify-end">
        <button type="submit" id="passwordSaveBtn" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-sm">Update Password</button>
      </div>
    </form>
  </div>
</div>

<script>
  function showMsg(el, text, isError){
    el.textContent = text;
    el.className = 'text-sm mb-4 ' + (isError ? 'text-red-600' : 'text-emerald-600');
    el.classList.remove('hidden');
  }

  document.getElementById('profileAvatarInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if(!file) return;
    const url = URL.createObjectURL(file);
    document.getElementById('profileAvatar').src = url;
  });

  document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = document.getElementById('profileMsg');
    const btn = document.getElementById('profileSaveBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
      const form = document.getElementById('profileForm');
      const fd = new FormData(form);
      const res = await fetch('../backend/update_profile.php', { method: 'POST', body: fd });
      const data = await res.json();
      if(data.success){
        showMsg(msg, 'Profile updated.', false);
      } else {
        showMsg(msg, data.message || 'Failed to update profile.', true);
      }
    } catch (err) {
      showMsg(msg, 'Failed to update profile.', true);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Save Details';
    }
  });

  document.getElementById('passwordForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = document.getElementById('passwordMsg');
    const btn = document.getElementById('passwordSaveBtn');
    const currentPassword = document.getElementById('currentPassword').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    if(newPassword !== confirmPassword){
      showMsg(msg, 'Passwords do not match.', true);
      return;
    }

    btn.disabled = true;
    btn.textContent = 'Updating...';

    try {
      const res = await fetch('../backend/change_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ currentPassword, newPassword })
      });
      const data = await res.json();
      if(data.success){
        showMsg(msg, 'Password updated.', false);
        document.getElementById('currentPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
      } else {
        showMsg(msg, data.message || 'Failed to update password.', true);
      }
    } catch (err) {
      showMsg(msg, 'Failed to update password.', true);
    } finally {
      btn.disabled = false;
      btn.textContent = 'Update Password';
    }
  });
</script>

<?php include 'footer.php'; ?>
