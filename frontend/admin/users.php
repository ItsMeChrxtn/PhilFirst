<?php include 'header_clean.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ================= USERS HEADER (styled like Job Management) ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-8">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-transparent"></div>
  <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow">
          <i class="fa-solid fa-users"></i>
        </span>
        User Management
      </h2>
      <p class="text-sm text-neutral-500 mt-1">Manage admin and staff accounts, roles, and permissions.</p>
    </div>
    <!--<button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
      <i class="fa-solid fa-plus"></i>
      Create User
    </button>
    -->
  </div>
</div>

<!-- ================= USERS TABLE (job_management style) ================= -->
<div class="rounded-2xl border border-neutral-200 bg-white shadow-sm mb-6">
  <div class="p-4 md:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

    <!-- Role Pills -->
    <div id="statusPillsUsers" class="relative flex flex-wrap gap-2">
      <div id="statusIndicatorUsers" class="absolute rounded-full bg-emerald-600 transition-all duration-300" style="height:36px; width:0; left:0; top:0; z-index:0;"></div>
      <button class="userStatusBtn selected relative z-10 px-4 py-2 rounded-full text-sm font-medium text-white transition" data-role="all">All</button>
      <button class="userStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 transition" data-role="administrator">Administrator</button>
      <button class="userStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 transition" data-role="applicant">Applicant</button>
    </div>

    <!-- Search -->
    <div class="flex w-full lg:w-auto gap-2">
      <div class="relative w-full lg:w-80">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm"></i>
        <input id="userSearchInput" placeholder="Search name, email, phone" class="w-full rounded-xl border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:outline-none transition" />
      </div>
      <button id="userSearchApply" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">Apply</button>
      <button id="userSearchClear" class="rounded-xl bg-neutral-100 px-4 py-2.5 text-sm hover:bg-neutral-200 transition">Clear</button>
    </div>
  </div>
</div>
<div class="rounded-2xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
  <table class="min-w-full">
    <thead class="bg-neutral-50 border-b border-neutral-200">
      <tr>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Name</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Role</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Email</th>
        <th class="px-6 py-4"></th>
      </tr>
    </thead>
    <tbody id="userTableBody" class="divide-y divide-neutral-200">
      
    </tbody>
  </table>
</div>

<?php include 'footer.php'; ?>

<style>
  .action-btn{ position: relative; display: inline-block; }
  .action-tooltip{ position: absolute; right: 100%; top: 50%; transform: translateY(-50%) translateX(-8px); background: rgba(31,41,55,0.95); color: #fff; padding: 6px 8px; border-radius: 6px; font-size: 12px; white-space: nowrap; opacity: 0; pointer-events: none; transition: opacity .12s ease, transform .12s ease; }
  .action-btn:hover .action-tooltip{ opacity: 1; transform: translateY(-50%) translateX(-12px); }
  /* small caret */
  .action-tooltip::after{ content: ''; position: absolute; right: -6px; top: 50%; transform: translateY(-50%); width:0;height:0;border-left:6px solid rgba(31,41,55,0.95);border-top:6px solid transparent;border-bottom:6px solid transparent; }
</style>

<script>
// Load users from backend and render into the table body
async function loadUsers(){
  try{
    const res = await fetch('../../backend/get_users.php', { credentials: 'same-origin' });
    const j = await res.json().catch(()=>null);
    if(!j || !j.success){ console.error('Failed to load users', j); return; }
    const rows = j.data || [];
    const tbody = document.querySelector('#userTableBody');
    if(!tbody) return;
    tbody.innerHTML = '';
    rows.forEach(u=>{
      const name = ((u.first_name||'') + ' ' + (u.last_name||'')).trim() || '—';
      const email = u.email || '';
      const phone = u.contact || u.phone || u.phone_number || '';
      const created = u.created_at || '';
      const avatar = u.profile_picture ? `<img src="${u.profile_picture}" alt="" class="w-8 h-8 rounded-full object-cover">` : `<div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700">${(u.first_name||'')[0]||'?'}${(u.last_name||'')[0]||''}</div>`;
      const tr = document.createElement('tr');
      tr.className = 'hover:bg-neutral-50';
      tr.dataset.userId = u.id;
      // determine role display and button disabled states
      const role = (u.role||'').toString().toLowerCase();
      const isApplicant = role === 'applicant' || role === 'user';
      const roleLabel = role === 'admin' ? 'Administrator' : (role === 'hr' ? 'HR' : (isApplicant ? 'Applicant' : escapeHtml(role)));
      const makeAdminDisabled = role === 'admin';
      const makeUserDisabled = role === 'applicant';

      tr.innerHTML = `
            <td class="px-6 py-4 font-medium text-neutral-800 flex items-center gap-3">${avatar}<div>${escapeHtml(name)}<div class="text-xs text-neutral-400">${escapeHtml(created)}</div></div></td>
            <td class="px-6 py-4 text-neutral-600">${escapeHtml(roleLabel)}</td>
            <td class="px-6 py-4 text-neutral-600">${escapeHtml(email)}</td>
            <td class="px-6 py-4 text-right flex items-center gap-2 justify-end">
              <button title="View" class="viewUserBtn w-10 h-10 flex items-center justify-center rounded-full bg-blue-50 hover:bg-blue-100 shadow-lg transition-transform transform hover:scale-110" onclick="viewUser(${u.id})"><i class="fa-solid fa-eye text-blue-700"></i></button>
              <button ${makeAdminDisabled ? 'disabled aria-disabled="true"' : ''} title="Make Admin" class="w-10 h-10 flex items-center justify-center rounded-full ${makeAdminDisabled ? 'bg-emerald-300 text-white opacity-60 pointer-events-none' : 'bg-emerald-800 hover:bg-emerald-900'} shadow-lg transition-transform transform hover:scale-110" ${makeAdminDisabled ? '' : `onclick="changeRole(${u.id}, 'admin')"`}><i class="fa-solid fa-user-shield text-white"></i></button>
            
              <button ${makeUserDisabled ? 'disabled aria-disabled="true"' : ''} title="Make Applicant" class="w-10 h-10 flex items-center justify-center rounded-full ${makeUserDisabled ? 'bg-neutral-200 text-neutral-400 opacity-60 pointer-events-none' : 'bg-neutral-100 hover:bg-neutral-200'} shadow-lg transition-transform transform hover:scale-110" ${makeUserDisabled ? '' : `onclick="changeRole(${u.id}, 'applicant')"`}><i class="fa-solid fa-user text-neutral-700"></i></button>
              <button title="Delete" class="w-10 h-10 flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 shadow-lg transition-transform transform hover:scale-110" onclick="deleteUser(${u.id})"><i class="fa-solid fa-trash text-white"></i></button>
            </td>
        `;
      tbody.appendChild(tr);
    });
  }catch(e){ console.error('Error loading users', e); }
}
function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g,c=>({'&':'&amp;','"':'&quot;',"'":"&#39;",'<':'&lt;','>':'&gt;'}[c])); }
document.addEventListener('DOMContentLoaded', loadUsers);
</script>
<!-- View User Modal -->
<div id="viewUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">User details</h3>
      <button onclick="(function(el){el.classList.add('hidden');el.classList.remove('flex');})(document.getElementById('viewUserModal'))" class="text-2xl">&times;</button>
    </div>
    <div id="viewUserContent" class="space-y-2 text-sm text-neutral-700">
      <!-- populated dynamically -->
    </div>
    <div class="flex justify-end mt-4">
      <button onclick="(function(el){el.classList.add('hidden');el.classList.remove('flex');})(document.getElementById('viewUserModal'))" class="px-4 py-2 rounded-xl border">Close</button>
    </div>
  </div>
</div>

<script>
async function viewUser(id){
  try{
    const res = await fetch('../../backend/get_user.php?id='+encodeURIComponent(id), { credentials:'same-origin' });
    const j = await res.json().catch(()=>null);
    if(!j || !j.success) return Swal.fire('Error','Unable to load user','error');
    const u = j.data;
    const cont = document.getElementById('viewUserContent');
    cont.innerHTML = `
      <div class="flex items-center gap-3">
        ${u.profile_picture ? `<img src="${u.profile_picture}" class="w-16 h-16 rounded-full object-cover">` : ''}
        <div>
          <div class="font-medium text-neutral-800">${escapeHtml((u.first_name||'')+' '+(u.last_name||''))}</div>
          <div class="text-xs text-neutral-400">Joined: ${escapeHtml(u.created_at||'')}</div>
        </div>
      </div>
      <div><strong>Email:</strong> ${escapeHtml(u.email||'')}</div>
      <div><strong>Phone:</strong> ${escapeHtml(u.contact||'')}</div>
      <div><strong>Role:</strong> ${escapeHtml(u.role||'')}</div>
    `;
    const modal = document.getElementById('viewUserModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }catch(e){ console.error(e); Swal.fire('Error','Server error','error'); }
}

async function changeRole(id, role){
  try{
    const ok = await Swal.fire({ title: 'Change role?', text: `Set role to "${role}"?`, showCancelButton:true, confirmButtonText:'Yes' });
    if(!ok.isConfirmed) return;
    const res = await fetch('../../backend/update_user_role.php', { method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ user_id:id, role: role }) });
    const j = await res.json().catch(()=>null);
    if(!j || !j.success) return Swal.fire('Error', j && j.message ? j.message : 'Failed', 'error');
    Swal.fire('Updated','Role updated','success');
    loadUsers();
  }catch(e){ console.error(e); Swal.fire('Error','Server error','error'); }
}

async function deleteUser(id){
  try{
    const ok = await Swal.fire({ title:'Delete user?', text:'This action cannot be undone.', showCancelButton:true, confirmButtonText:'Delete' });
    if(!ok.isConfirmed) return;
    const res = await fetch('../../backend/delete_user.php', { method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ user_id:id }) });
    const j = await res.json().catch(()=>null);
    if(!j || !j.success) return Swal.fire('Error', j && j.message ? j.message : 'Failed', 'error');
    Swal.fire('Deleted','User removed','success');
    loadUsers();
  }catch(e){ console.error(e); Swal.fire('Error','Server error','error'); }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const statusPills = document.querySelectorAll('.userStatusBtn');
  const statusPillsWrap = document.getElementById('statusPillsUsers');
  const indicator = document.getElementById('statusIndicatorUsers');
  const searchInput = document.getElementById('userSearchInput');
  const applyBtn = document.getElementById('userSearchApply');
  const clearBtn = document.getElementById('userSearchClear');

  function moveIndicator(btn){ if(!btn || !indicator || !statusPillsWrap) return; const rBtn = btn.getBoundingClientRect(); const rParent = statusPillsWrap.getBoundingClientRect(); const left = rBtn.left - rParent.left + statusPillsWrap.scrollLeft; indicator.style.width = rBtn.width + 'px'; indicator.style.height = rBtn.height + 'px'; indicator.style.transform = 'translateX('+left+'px)'; }

  if(statusPills && statusPills.length){ statusPills.forEach((b,i)=>{ if(i===0) b.classList.add('selected'); b.addEventListener('click', ()=>{ statusPills.forEach(x=>{ x.classList.remove('selected'); x.classList.remove('text-white'); x.classList.add('text-neutral-700'); }); b.classList.add('selected'); b.classList.remove('text-neutral-700'); b.classList.add('text-white'); moveIndicator(b); applyUserFilters(); }); }); setTimeout(()=>{ const sel = document.querySelector('.userStatusBtn.selected'); if(sel) moveIndicator(sel); },50); window.addEventListener('resize', ()=>{ const sel = document.querySelector('.userStatusBtn.selected'); if(sel) moveIndicator(sel); }); }

  if(applyBtn) applyBtn.addEventListener('click', applyUserFilters);
  if(clearBtn) clearBtn.addEventListener('click', function(){ if(searchInput) searchInput.value=''; const first = document.querySelector('.userStatusBtn[data-role="all"]'); if(first){ first.click(); } applyUserFilters(); });

  function getSelectedRole(){ const b = document.querySelector('.userStatusBtn.selected'); return b ? (b.dataset.role||'all') : 'all'; }

  function applyUserFilters(){ const q = (searchInput && searchInput.value || '').toLowerCase().trim(); const role = getSelectedRole(); const tbody = document.getElementById('userTableBody'); if(!tbody) return; Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{ const rowRole = (tr.dataset.role || (tr.querySelectorAll('td')[1] ? tr.querySelectorAll('td')[1].textContent.trim().toLowerCase() : '')).toString().toLowerCase(); const hay = (tr.innerText||'').toLowerCase(); const roleOk = role === 'all' ? true : (rowRole === role); const textOk = !q ? true : hay.includes(q); tr.style.display = (roleOk && textOk) ? '' : 'none'; }); }

});
</script>
