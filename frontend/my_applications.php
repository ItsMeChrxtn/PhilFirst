<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>My Applications — Phil-First</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body{ font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    /* Cards */
    #appsList article{ box-shadow: 0 6px 18px rgba(16,24,40,0.06); border: 1px solid rgba(16,24,40,0.04); transition: transform .18s ease, box-shadow .18s ease; }
    #appsList article:hover{ transform: translateY(-6px); box-shadow: 0 12px 30px rgba(16,24,40,0.08); }
    /* Avatar */
    .avatar-circle{ width:48px;height:48px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;color:#065f46;background:linear-gradient(180deg,#ecfdf5,#d1fae5); }
    /* Status badges */
    .status-badge{ padding: .25rem .65rem; font-weight:700; border-radius:9999px; border-width:1px; display:inline-block; }
    .status-pending{ background:#fff7ed; color:#92400e; border-color:#fde68a; }
    .status-accepted{ background:#ecfdf5; color:#065f46; border-color:#bbf7d0; }
    .status-rejected{ background:#fee2e2; color:#991b1b; border-color:#fecaca; }
    /* Buttons */
    .btn-primary{ background: linear-gradient(90deg,#065f46 0%, #059669 100%); color: #fff; border: none; box-shadow: 0 6px 18px rgba(5,150,105,0.12); }
    .btn-primary:hover{ transform: translateY(-1px); box-shadow: 0 12px 30px rgba(5,150,105,0.16); }
    .btn-outline{ background:#fff;border:1px solid rgba(16,24,40,0.06); color:#0f172a; }
    .btn-resume{ background:#0f766e;color:#fff;border-radius:8px;padding:.45rem .7rem;display:inline-flex;align-items:center;gap:.5rem;box-shadow:0 6px 18px rgba(15,118,110,0.08); }
    .btn-resume svg{ opacity: .95 }
    /* Overview cards */
    .overview-grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:.75rem; margin-top:.5rem }
    .overview-card{ padding:.7rem; border-radius:12px; background:linear-gradient(180deg,#ffffff,#f7fffb); box-shadow:0 6px 18px rgba(6,95,70,0.04); display:flex; align-items:center; gap:.75rem; border:1px solid rgba(6,95,70,0.04); }
    .overview-icon{ width:44px; height:44px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-weight:700; }
    .icon-pending{ background:linear-gradient(90deg,#f59e0b,#f97316); }
    .icon-accepted{ background:linear-gradient(90deg,#10b981,#059669); }
    .icon-interview{ background:linear-gradient(90deg,#06b6d4,#0891b2); }
    .icon-rejected{ background:linear-gradient(90deg,#ef4444,#dc2626); }
    .overview-num{ font-size:1.1rem; font-weight:800; color:#064e3b; }
    // apply current filter (will set active button state)
    try{ setFilter(currentFilter); }catch(e){}
    .overview-label{ font-size:.82rem; color:#6b7280; }
    .progress{ height:6px; background:#e6f3ef; border-radius:999px; overflow:hidden; }
    .progress > i{ display:block; height:100%; background:linear-gradient(90deg,#065f46,#10b981); width:0%; }
    /* Pagination */
    .page-btn{ padding:.4rem .6rem;border-radius:.5rem;border:1px solid rgba(15,23,42,0.06); background:#fff; }
    .page-btn.active{ background:#065f46;color:#fff;border-color:transparent; box-shadow:0 8px 22px rgba(6,95,70,0.08); }
    /* Sidebar subtle depth */
    aside{ background-image: linear-gradient(180deg, rgba(6,95,70,0.02), transparent); }
    /* Small utility */
    .line-clamp-3{ display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    @media (prefers-reduced-motion: reduce){ #appsList article{ transition: none; } }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 text-neutral-800 antialiased">
<?php if (empty($_SESSION['user'])){ header('Location: /welcome/jobs'); exit; } ?>

<!-- Shared header -->
<?php include __DIR__ . '/partials/header.php'; ?>
<!-- Hero -->
<section class="relative overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 to-sky-500 -z-10"></div>
  <div class="absolute inset-0 opacity-20 -z-5" style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1600&q=60'); background-size:cover; background-position:center; filter: blur(2px) brightness(.6);"></div>
  <div class="max-w-7xl mx-auto px-6 py-20 sm:py-28 text-white relative">
    <h1 class="text-4xl sm:text-5xl font-extrabold">My Applications</h1>
    <p class="mt-3 text-lg max-w-2xl text-emerald-50/90">Track the status of jobs you applied to and manage your applications with ease.</p>
    <div class="mt-6">
      <a href="/welcome/jobs" class="inline-block btn-primary px-5 py-3 rounded-lg">Browse Jobs</a>
    </div>
  </div>
</section>

<main class="max-w-7xl mx-auto p-6">

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Sidebar / summary -->
    <aside class="col-span-1 bg-white rounded-2xl p-6 shadow-sm">
      <h2 class="text-sm font-semibold text-neutral-700">Overview</h2>
      <div class="mt-4 space-y-3">
        <div class="flex items-center justify-between">
          <div class="text-sm text-neutral-500">Total applications</div>
          <div id="totalCount" class="text-lg font-bold text-emerald-700">—</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-neutral-500">Pending</div>
          <div id="pendingCount" class="text-sm font-semibold text-yellow-700">—</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-neutral-500">Accepted</div>
          <div id="acceptedCount" class="text-sm font-semibold text-emerald-700">—</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-neutral-500">For interview</div>
          <div id="forInterviewCount" class="text-sm font-semibold text-emerald-700">—</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-neutral-500">Rejected</div>
          <div id="rejectedCount" class="text-sm font-semibold text-neutral-700">—</div>
        </div>
      </div>
      <div class="mt-6">
        <h3 class="text-sm font-semibold text-neutral-700">Quick actions</h3>
        <div class="mt-3 flex flex-col gap-2">
          <button id="refreshBtn" class="w-full text-left px-4 py-2 rounded-lg border bg-emerald-50 text-emerald-700">Refresh</button>
          <a href="/welcome/jobs" class="w-full text-center px-4 py-2 rounded-lg border bg-white text-neutral-700">Browse more jobs</a>
        </div>
      </div>
    </aside>

    <!-- Applications list -->
    <section class="col-span-2">
      <div class="mb-4 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <button id="filter-all" onclick="setFilter('all')" class="px-3 py-1 rounded-md btn-outline">All</button>
          <button id="filter-pending" onclick="setFilter('pending')" class="px-3 py-1 rounded-md btn-outline">Pending</button>
          <button id="filter-accepted" onclick="setFilter('accepted')" class="px-3 py-1 rounded-md btn-outline">Accepted</button>
          <button id="filter-interview" onclick="setFilter('for interview')" class="px-3 py-1 rounded-md btn-outline">For Interview</button>
          <button id="filter-rejected" onclick="setFilter('rejected')" class="px-3 py-1 rounded-md btn-outline">Rejected</button>
        </div>
        <div class="text-sm text-neutral-500">Filter applications by status</div>
      </div>
      <div id="appsList" class="space-y-4">
        <div id="appsPlaceholder" class="bg-white rounded-2xl shadow p-6 text-center text-neutral-400 flex items-center justify-center gap-3">
          <svg class="animate-spin h-5 w-5 text-emerald-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
          <span>Loading your applications…</span>
        </div>
      </div>
      <div id="appsPagination" class="mt-6 flex justify-center items-center"></div>
    </section>
  </div>
</main>

<script>
let appRows = [];
let currentFilter = 'all';
// Show pagination when applications > 5
let pageSize = 5;
let currentPage = 1;

async function loadMyApplications(){
  try{
    const list = document.getElementById('appsList');
    const pag = document.getElementById('appsPagination');
    list.innerHTML = '';
    pag.innerHTML = '';

    const res = await fetch('../backend/get_applications.php', { credentials:'same-origin' });
    // read as text first so we can show raw server output when it's not valid JSON
    const text = await res.text();
    let j;
    const contentType = (res.headers.get('content-type') || '').toLowerCase();
    if (contentType.includes('application/json')) {
      try {
        j = JSON.parse(text);
      } catch (e) {
        console.error('Failed to parse JSON from get_applications:', e, text);
        list.innerHTML = `<div class="bg-white rounded-2xl shadow p-6 text-center text-red-600">Server returned invalid JSON. See console for details.</div><pre class="mt-3 p-3 bg-neutral-50 border rounded text-xs text-neutral-700">${escapeHtml(text)}</pre>`;
        return;
      }
    } else {
      // server returned HTML or other text (likely a PHP error/notice). Show it and bail.
      console.error('Non-JSON response from get_applications:', text);
      list.innerHTML = `<div class="bg-white rounded-2xl shadow p-6 text-center text-red-600">Server error: unexpected response. Showing server output below.</div><pre class="mt-3 p-3 bg-neutral-50 border rounded text-xs text-neutral-700">${escapeHtml(text)}</pre>`;
      return;
    }
    console.log('get_applications response', j);
    // If `?debug_apps=1` is present, show raw JSON on the page for quick debugging
    try{ if(window.location.search.indexOf('debug_apps=1') !== -1){ const pre = document.createElement('pre'); pre.style.maxHeight='300px'; pre.style.overflow='auto'; pre.className='p-3 bg-neutral-50 border rounded text-xs text-neutral-700 mt-3'; pre.textContent = JSON.stringify(j, null, 2); document.getElementById('appsList').prepend(pre); } }catch(e){}

    if(!j.success){
      if(j.requires_login){
        await Swal.fire({icon:'info', title:'Please sign in', text:'Sign in to view your applications'});
        window.location.href = '/welcome/jobs';
        return;
      }
      list.innerHTML = `<div class="bg-white rounded-2xl shadow p-6 text-center text-red-600">${j.message||'Unable to load applications'}</div>`;
      return;
    }
    appRows = j.data || [];
    updateOverview();
    if(!appRows.length){
      list.innerHTML = `<div class="bg-white rounded-2xl shadow p-6 text-center text-neutral-500">You have not applied to any jobs yet.</div>`;
      return;
    }
    currentPage = 1;
    renderApps();
    renderPagination();
  }catch(err){
    console.error(err);
    document.getElementById('appsList').innerHTML = `<div class="bg-white rounded-2xl shadow p-6 text-center text-red-600">Network or server error</div>`;
  }
}

function updateOverview(){
  const total = appRows.length;
  const pending = appRows.filter(r=>((r.status||'').toLowerCase())==='pending').length;
  const accepted = appRows.filter(r=>{ const s = (r.status||'').toString().toLowerCase(); return s==='accepted'; }).length;
  const forInterview = appRows.filter(r=>{ const s = (r.status||'').toString().toLowerCase(); return s==='approved' || s==='for interview'; }).length;
  const rejected = appRows.filter(r=>{ const s = (r.status||'').toString().toLowerCase(); return s==='rejected' || s==='declined'; }).length;
  document.getElementById('totalCount').textContent = total;
  document.getElementById('pendingCount').textContent = pending;
  document.getElementById('acceptedCount').textContent = accepted;
  const fiEl = document.getElementById('forInterviewCount'); if(fiEl) fiEl.textContent = forInterview;
  document.getElementById('rejectedCount').textContent = rejected;

  // update progress bars (if present) to show relative distribution
  const pct = (n)=> total ? Math.round((n/total)*100) : 0;
  const setBar = (id, n) => {
    const el = document.getElementById(id);
    if(el) el.style.width = pct(n) + '%';
    const lbl = document.getElementById(id + '-label');
    if(lbl) lbl.textContent = pct(n) + '%';
  };
  setBar('pendingBar', pending);
  setBar('acceptedBar', accepted);
  setBar('forInterviewBar', forInterview);
  setBar('rejectedBar', rejected);
}

function renderApps(){
  const list = document.getElementById('appsList');
  list.innerHTML = '';
  // apply client-side status filter
  const filtered = appRows.filter(r=>{
    if(!currentFilter || currentFilter === 'all') return true;
    const s = ((r.status||'').toString()||'').toLowerCase();
    let norm = s;
    if(norm === 'approved') norm = 'for interview';
    if(norm === 'declined') norm = 'rejected';
    return norm === currentFilter;
  });
  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);
  pageItems.forEach(r=>{
    const applied = r.created_at ? new Date(r.created_at).toLocaleString() : '';
    const status = (r.status||'pending').toLowerCase();
    const resume = r.resume_path ? `<button type="button" class="btn-resume" onclick="openResume('../${r.resume_path}')" title="View resume"><svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4' viewBox='0 0 20 20' fill='currentColor'><path d='M8 2a2 2 0 00-2 2v8a2 2 0 002 2h4a2 2 0 002-2V4a2 2 0 00-2-2H8z'/><path d='M8 0h4a4 4 0 014 4v8a4 4 0 01-4 4H8a4 4 0 01-4-4V4a4 4 0 014-4z' fill-opacity='0.2'/></svg><span class="text-sm">Resume</span></button>` : '';
    const jobTitle = r.resolved_job_title || r.job_title || r.job || '—';
    const location = r.resolved_location || r.location || r.job_location || '—';
    const company = r.client || r.company || '-';
    const excerpt = (r.message || r.note || '').toString().slice(0,180);
    // normalize status values and map 'approved' to 'for interview'
    let norm = status;
    if(norm === 'approved') norm = 'for interview';
    if(norm === 'declined') norm = 'rejected';
    const badgeClass = norm==='pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : ((norm==='accepted' || norm==='for interview') ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-red-100 text-red-800 border-red-200');

    const article = document.createElement('article');
    article.className = 'bg-white rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden border border-transparent hover:border-emerald-50 p-4';
    article.innerHTML = `
      <div class="md:flex items-start gap-4">
        <div class="md:w-24 flex-shrink-0 flex items-center justify-center p-3 rounded-lg">
          <div class="avatar-circle">${escapeHtml((company||'')[0]||'J')}</div>
        </div>
        <div class="flex-1">
          <div class="flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-emerald-800 leading-tight">${escapeHtml(jobTitle)}</h3>
              <div class="text-sm text-neutral-500 mt-1">${escapeHtml(company)} • ${escapeHtml(location)}</div>
            </div>
            <div class="text-right">
              <div class="status-badge px-3 py-1 rounded-full border ${badgeClass} text-sm inline-block">${escapeHtml(norm)}</div>
              <div class="text-xs text-neutral-400 mt-1">Applied: ${escapeHtml(applied)}</div>
            </div>
          </div>
          ${excerpt?`<p class="text-sm text-neutral-600 mt-3 line-clamp-3">${escapeHtml(excerpt)}${(r.message||'').length>180? '…':''}</p>`:''}

            <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
              ${resume}
              <button onclick="confirmDelete(${r.id})" class="ml-2 inline-flex items-center gap-2 px-3 py-1 border rounded text-sm text-red-600 hover:bg-red-50">Delete</button>
            </div>
            <div>
              <a href="jobs.php?job_id=${encodeURIComponent(r.job_id || r.id)}" class="inline-flex items-center gap-2 px-3 py-1 border rounded text-sm text-neutral-600 hover:bg-emerald-50">View Job</a>
            </div>
          </div>
        </div>
      </div>
    `;
    // Add a subtle left accent based on status for clearer scanning
    let accent = '#f59e0b'; // pending
    if(norm === 'accepted' || norm === 'for interview') accent = '#059669';
    else if(norm === 'rejected') accent = '#dc2626';
    article.style.borderLeft = '4px solid ' + accent;
    article.style.paddingLeft = '1rem';
    try{ if(r.id || r.application_id) article.id = 'app-' + (r.id || r.application_id); }catch(e){}
    list.appendChild(article);
  });
  // after rendering, if a query param requests a specific application, scroll to and highlight it
  try{
    const params = new URLSearchParams(window.location.search);
    const aid = params.get('application_id') || params.get('id');
    if(aid){
      const el = document.getElementById('app-' + aid);
      if(el){
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.classList.add('ring-4', 'ring-emerald-200');
        setTimeout(()=>{ el.classList.remove('ring-4', 'ring-emerald-200'); }, 3000);
      }
    }
  }catch(e){}
}

function renderPagination(){
  const pag = document.getElementById('appsPagination');
  pag.innerHTML = '';
  // pagination should reflect filtered set
  const filteredCount = appRows.filter(r=>{
    if(!currentFilter || currentFilter === 'all') return true;
    const s = ((r.status||'').toString()||'').toLowerCase();
    let norm = s;
    if(norm === 'approved') norm = 'for interview';
    if(norm === 'declined') norm = 'rejected';
    return norm === currentFilter;
  }).length;
  const total = Math.max(1, Math.ceil(filteredCount / pageSize));
  if(total <= 1) return;

  const createBtn = (text, disabled, onClick, extraClass='')=>{
    const btn = document.createElement('button');
    btn.className = 'mx-1 page-btn px-3 py-1 rounded ' + extraClass + (disabled? ' opacity-50 cursor-not-allowed':'');
    btn.textContent = text;
    if(!disabled) btn.addEventListener('click', onClick);
    return btn;
  };

  pag.appendChild(createBtn('Prev', currentPage===1, ()=>{ currentPage--; renderApps(); renderPagination(); }));

  const maxButtons = 7;
  let start = Math.max(1, currentPage - Math.floor(maxButtons/2));
  let end = start + maxButtons - 1;
  if(end > total){ end = total; start = Math.max(1, end - maxButtons + 1); }
  for(let i=start;i<=end;i++){
    const cls = i===currentPage ? 'active' : '';
    pag.appendChild(createBtn(String(i), false, ()=>{ currentPage = i; renderApps(); renderPagination(); }, cls));
  }

  pag.appendChild(createBtn('Next', currentPage===total, ()=>{ currentPage++; renderApps(); renderPagination(); }));
}

function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g,c=>({'&':'&amp;','"':'&quot;','\'':'&#39;','<':'&lt;','>':'&gt;'})[c]); }

loadMyApplications();

function setFilter(f){
  currentFilter = f || 'all';
  currentPage = 1;
  // map filter to button ids
  const map = { 'all':'filter-all', 'pending':'filter-pending', 'accepted':'filter-accepted', 'for interview':'filter-interview', 'rejected':'filter-rejected' };
  Object.values(map).forEach(id=>{ const el = document.getElementById(id); if(el){ el.classList.remove('btn-primary'); el.classList.add('btn-outline'); } });
  const activeId = map[currentFilter] || map['all'];
  const activeEl = document.getElementById(activeId);
  if(activeEl){ activeEl.classList.remove('btn-outline'); activeEl.classList.add('btn-primary'); }
  renderApps();
  renderPagination();
}

// small dropdown handling for header and logout handlers
document.addEventListener('DOMContentLoaded', ()=>{
  const btn = document.getElementById('userMenuBtn');
  const dd = document.getElementById('userDropdown');
  if(btn && dd) btn.addEventListener('click', ()=> dd.classList.toggle('hidden'));
  const logout = document.getElementById('logoutBtn');
  if(logout) logout.addEventListener('click', async ()=>{ Swal.fire({ title:'Logging out...', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } }); try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({icon:'success', title:'Logged out', timer: 800, timerProgressBar: true, showConfirmButton:false}); window.location.href='/welcome/home'; }catch(e){ Swal.fire({ title:'Error', text:'Logout failed', icon:'error', timer: 1500, timerProgressBar: true, showConfirmButton: false }); } });
  const logoutMobile = document.getElementById('logoutBtnMobile');
  if(logoutMobile) logoutMobile.addEventListener('click', async ()=>{ Swal.fire({ title:'Logging out...', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } }); try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({icon:'success', title:'Logged out', timer: 800, timerProgressBar: true, showConfirmButton:false}); window.location.href='/welcome/home'; }catch(e){ Swal.fire({ title:'Error', text:'Logout failed', icon:'error', timer: 1500, timerProgressBar: true, showConfirmButton: false }); } });
  const refresh = document.getElementById('refreshBtn');
  if(refresh) refresh.addEventListener('click', ()=> loadMyApplications());
});

function openResume(path){
  // show resume inside a modal using SweetAlert2; path should be relative URL to the file
  Swal.fire({ title:'Loading resume...', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
  const img = new Image();
  img.onload = ()=>{
    const iframe = `<div style="width:100%;height:70vh;max-height:70vh"><iframe src="${path}" style="width:100%;height:100%;border:0;border-radius:8px"></iframe></div>`;
    Swal.fire({
      title: 'View Resume',
      html: iframe,
      width: '80%',
      showCloseButton: true,
      showConfirmButton: false
    });
  };
  img.onerror = ()=>{
    Swal.fire({ title:'Resume', html: `<div style="width:100%;height:70vh;max-height:70vh"><iframe src="${path}" style="width:100%;height:100%;border:0;border-radius:8px"></iframe></div>`, width: '80%', showCloseButton: true, showConfirmButton: false });
  };
  img.src = path;
}

async function confirmDelete(id){
  const r = await Swal.fire({ title:'Delete application?', text:'This will remove your application permanently.', icon:'warning', showCancelButton:true, confirmButtonText:'Delete' });
  if(!r.isConfirmed) return;
  Swal.fire({ title:'Deleting...', text:'Please wait', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
  try{
    const res = await fetch('../backend/delete_application.php', { method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id }) });
    const j = await res.json();
    if(j.success){ Swal.fire({ title:'Deleted', text:'Application removed', icon:'success', timer: 1500, timerProgressBar: true, showConfirmButton: false }); loadMyApplications(); }
    else Swal.fire({ title:'Error', text: j.message || 'Unable to delete', icon:'error', timer: 2000, timerProgressBar: true, showConfirmButton: false });
  }catch(err){ console.error(err); Swal.fire({ title:'Error', text:'Server error', icon:'error', timer: 2000, timerProgressBar: true, showConfirmButton: false }); }
}
// Live polling: refresh applications list when backend data changes (no page reload)
(function(){
  let _appPollIntervalMs = 15000;
  let _appPollTimer = null;
  let _appPollInFlight = false;
  async function pollOnce(){
    if(_appPollInFlight) return;
    _appPollInFlight = true;
    try{
      const res = await fetch('../backend/get_applications.php', { credentials:'same-origin' });
      if(!res.ok) return;
      const text = await res.text();
      let j = null;
      try{ j = JSON.parse(text); }catch(e){ return; }
      if(!j || !j.success) return;
      const newData = j.data || [];

      // detect changes vs current appRows (by id and key fields)
      const oldMap = new Map(appRows.map(r=>[String(r.id||r.application_id||''), r]));
      let changed = false;
      if(newData.length !== appRows.length) changed = true;
      else{
        for(const nd of newData){
          const id = String(nd.id||nd.application_id||'');
          const old = oldMap.get(id);
          if(!old){ changed = true; break; }
          if(String((nd.status||'')).trim() !== String((old.status||'')).trim() || String((nd.message||'')).trim() !== String((old.message||'')).trim() || String((nd.created_at||'')).trim() !== String((old.created_at||'')).trim() || String((nd.resolved_job_title||'')).trim() !== String((old.resolved_job_title||'')).trim()){
            changed = true; break;
          }
        }
      }

      if(changed){
        appRows = newData;
        updateOverview();
        renderApps();
        renderPagination();
      }
    }catch(e){ console.error('[app-poll] error', e); }
    finally{ _appPollInFlight = false; }
  }
  function start(){ if(_appPollTimer) return; pollOnce(); _appPollTimer = setInterval(pollOnce, _appPollIntervalMs); }
  function stop(){ if(!_appPollTimer) return; clearInterval(_appPollTimer); _appPollTimer = null; }
  document.addEventListener('visibilitychange', ()=>{ if(document.hidden) stop(); else start(); });
  start();
})();
</script>
<?php include __DIR__ . '/partials/footer.php'; ?>
