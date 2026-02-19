<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Phil-First HR & Services — Careers Portal</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 text-neutral-800 antialiased">
<script>window.currentUserId = <?php echo json_encode($_SESSION['user']['id'] ?? null); ?>;</script>

<!-- Shared header -->
<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ================= HERO ================= -->
<section class="relative overflow-hidden">
  <!-- Background Image -->
  <div class="absolute inset-0">
    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1600&q=80" 
         alt="Career background" 
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/80 to-teal-500/80"></div>
  </div>

  <div class="relative max-w-7xl mx-auto px-4 py-20">
    <!-- Two-column layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

      <!-- Left: Heading & Description -->
      <div class="text-center md:text-left text-white">
        <h2 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
          Build Your Career With Us
        </h2>
        <p class="text-base md:text-lg max-w-lg mx-auto md:mx-0 text-white/90 drop-shadow-sm mb-6">
          Discover meaningful opportunities and grow with Phil-First HR & Services.
        </p>
        <a href="#jobs" class="inline-block bg-white text-emerald-700 font-semibold px-6 py-3 rounded-xl shadow-lg hover:bg-white/90 transition">
          Explore Jobs
        </a>
      </div>

      <!-- Right: Filter Card -->
      <div>
        <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl p-6 md:p-8 text-neutral-800">
          <h3 class="text-lg font-semibold mb-2 text-neutral-700">Find Jobs</h3>
          <div class="text-sm text-neutral-500 mb-4">Use filters to narrow results. Inactive positions are hidden from the list.</div>
          <div class="flex flex-col md:flex-row gap-4 md:gap-3 items-center">
            <!-- Select Inputs -->
            <select id="locationSelect" class="rounded-xl border border-neutral-300 px-4 py-2 w-full md:w-48 text-sm hover:border-emerald-500 transition">
              <option value="any">All provinces</option>
            </select>
            <select id="jobTypeSelect" class="rounded-xl border border-neutral-300 px-4 py-2 w-full md:w-48 text-sm hover:border-emerald-500 transition">
              <option value="any">All types</option>
              <option value="full-time">Full Time</option>
              <option value="part-time">Part Time</option>
              <option value="contract">Contract</option>
            </select>
            <!-- Buttons -->
            <div class="flex gap-3 w-full md:w-auto">
              <button id="applyFilters" class="bg-emerald-700 hover:bg-emerald-800 transition text-white px-6 py-2 rounded-xl font-semibold text-sm md:text-base w-full md:w-auto shadow-md">
                Apply
              </button>
              <button id="clearFilters" class="bg-neutral-200 hover:bg-neutral-300 transition text-neutral-800 px-6 py-2 rounded-xl font-semibold text-sm md:text-base w-full md:w-auto shadow-md">
                Clear
              </button>
            </div>
          </div>
          <div class="mt-4 w-full relative">
            <input id="searchJobs" placeholder="Search jobs (title, client, location)" class="w-full rounded-xl border border-neutral-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
            <button id="clearSearch" aria-label="Clear search" class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-400 hidden">&times;</button>
            <div class="text-xs text-neutral-500 mt-2">Tip: search by job title, client, or location. Use the Clear button to reset.</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ================= JOBS + DETAILS ================= -->
<div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-4 gap-4 md:gap-6">

  <!-- LEFT: JOB LIST -->
  <aside class="md:col-span-1 space-y-2 md:space-y-3">
    <h3 class="text-base md:text-lg font-bold text-neutral-700">Open Positions</h3>
    <div id="results" class="space-y-2 md:space-y-3"></div>
     <div id="pagination" class="flex gap-2 justify-center mt-4"></div>
    <div id="noResults" class="hidden text-center text-neutral-500 text-sm">No job openings match your filters.</div>
  </aside>

  <!-- RIGHT: JOB DETAILS -->
  <main class="md:col-span-3 bg-white rounded-2xl shadow-xl p-6 md:p-8 min-h-[500px]" id="jobDetails">
    <div class="text-center text-neutral-500 mt-12">
      <p class="text-base">Select a job on the left to view details</p>
    </div>
  </main>
</div>

<!-- contact removed: moved to index.php -->

<!-- ================= APPLY MODAL ================= -->
<!-- Login / Register Modals (top-level so they can display over page) -->
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
      <label class="block text-xs font-medium text-neutral-600">Email <span class="text-neutral-400">(Email)</span></label>
      <input name="email" type="email" placeholder="halimbawa@domain.com" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
      <label class="block text-xs font-medium text-neutral-600">Password <span class="text-neutral-400">(Password)</span></label>
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
      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div>
          <label class="block text-xs font-medium text-neutral-600">First name <span class="text-neutral-400">(Unang Pangalan)</span></label>
          <input name="firstName" placeholder="Juan" pattern="[a-zA-Z\s'-]+" title="Letters, spaces, hyphens, and apostrophes only" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
        </div>
        <div>
          <label class="block text-xs font-medium text-neutral-600">Last name <span class="text-neutral-400">(Apelyido)</span></label>
          <input name="lastName" placeholder="Dela Cruz" pattern="[a-zA-Z\s'-]+" title="Letters, spaces, hyphens, and apostrophes only" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Contact # <span class="text-neutral-400">(Telepono)</span></label>
        <input name="contact" placeholder="0917xxxxxxx" pattern="0\d{10}" title="11 digits starting with 0" maxlength="11" required class="w-full rounded-xl border border-neutral-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
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
<div id="applyModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-auto">
    <div class="px-4 py-3 border-b">
      <div class="flex justify-between items-start">
        <div>
          <h3 class="text-lg font-bold">Apply for this Job</h3>
          <p class="text-sm text-neutral-500 mt-1">Fill this form and we'll send your application to the recruiter.</p>
        </div>
        <button onclick="document.getElementById('applyModal').classList.add('hidden')" class="text-2xl text-neutral-500 hover:text-neutral-800">&times;</button>
      </div>
    </div>
    <form id="applyForm" enctype="multipart/form-data" class="px-4 py-4 space-y-3 text-sm">
      <input type="hidden" name="job_id" id="applyJobId" />
      <input type="hidden" name="job_title" id="applyJobTitle" />
      <div>
        <label class="block text-xs font-medium text-neutral-600">Full name <span class="text-neutral-400">(Buong Pangalan)</span></label>
        <input name="full_name" placeholder="Buong Pangalan" pattern="[a-zA-Z\s'-]+" title="Letters, spaces, hyphens, and apostrophes only" required class="w-full rounded-xl border border-neutral-300 px-3 py-2" />
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Email <span class="text-neutral-400">(Email)</span></label>
        <input name="email" type="email" placeholder="halimbawa@domain.com" required class="w-full rounded-xl border border-neutral-300 px-3 py-2" />
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Phone <span class="text-neutral-400">(Telepono)</span></label>
        <input name="phone" type="tel" placeholder="0917xxxxxxx" pattern="0\d{10}" title="11 digits starting with 0" maxlength="11" required class="w-full rounded-xl border border-neutral-300 px-3 py-2" />
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Upload resume <span class="text-neutral-400">(I-upload ang Resume)</span></label>
        <input name="resume" type="file" class="w-full" />
      </div>
      <div>
        <label class="block text-xs font-medium text-neutral-600">Message <span class="text-neutral-400">(Optional)</span></label>
        <textarea name="message" rows="3" class="w-full rounded-xl border border-neutral-300 px-3 py-2"></textarea>
      </div>
      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-neutral-200 text-sm">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 text-white text-sm">Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= SCRIPT ================= -->
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

const api = '../backend/job_api.php';
let jobs = [], filtered = [];
let pageSize = 10;
let currentPage = 1;

// debounce helper
function debounce(fn, wait){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), wait); }; }

function setPage(n){
  const total = Math.max(1, Math.ceil(filtered.length / pageSize));
  if(n < 1) n = 1;
  if(n > total) n = total;
  currentPage = n;
  renderJobs();
}

function renderPagination(){
  const container = document.getElementById('pagination');
  container.innerHTML = '';
  const total = Math.ceil(filtered.length / pageSize) || 1;
  if(total <= 1) return;

  const prev = document.createElement('button');
  prev.className = 'px-3 py-1 rounded bg-neutral-100 hover:bg-neutral-200';
  prev.textContent = 'Prev';
  prev.disabled = currentPage === 1;
  prev.addEventListener('click', () => setPage(currentPage - 1));
  container.appendChild(prev);

  // show page buttons (limit to reasonable count)
  const maxButtons = 7;
  let start = Math.max(1, currentPage - Math.floor(maxButtons/2));
  let end = start + maxButtons - 1;
  if(end > total){ end = total; start = Math.max(1, end - maxButtons + 1); }

  for(let i = start; i <= end; i++){
    const b = document.createElement('button');
    b.className = 'px-3 py-1 rounded ' + (i===currentPage? 'bg-emerald-700 text-white':'bg-neutral-100 hover:bg-neutral-200');
    b.textContent = String(i);
    b.addEventListener('click', () => setPage(i));
    container.appendChild(b);
  }

  const next = document.createElement('button');
  next.className = 'px-3 py-1 rounded bg-neutral-100 hover:bg-neutral-200';
  next.textContent = 'Next';
  next.disabled = currentPage === total;
  next.addEventListener('click', () => setPage(currentPage + 1));
  container.appendChild(next);
}

function normalizeType(s){ return (s||'').toString().replace(/[^a-z0-9]/gi,'').toLowerCase(); }

async function loadProvinces(){
  try{
    const res = await fetch(api + '?action=provinces');
    const j = await res.json();
    if(j.success && Array.isArray(j.data)){
      while(locationSelect.options.length>1) locationSelect.remove(1);
      j.data.forEach(p=>{
        if(!p) return;
        const opt = document.createElement('option');
        opt.value = p; opt.text = p; locationSelect.appendChild(opt);
      });
    }
  }catch(err){ console.error(err); }
}

function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g,c=>({'&':'&amp;','"':'&quot;','\'':'&#39;','<':'&lt;','>':'&gt;'})[c]); }

async function fetchJobs(province){
  let url = api+'?action=list';
  if(province) url+='&province='+encodeURIComponent(province);
  try{
    const res = await fetch(url);
    const j = await res.json();
    jobs = j.success ? j.data : [];
  }catch(err){ jobs=[]; console.error(err); }
  applyFiltersFn();
}

function applyFiltersFn(){
  const type = jobTypeSelect?.value || 'any';
  const selTypeNorm = normalizeType(type);
  const q = (document.getElementById('searchJobs')?.value || '').toLowerCase().trim();
  filtered = jobs.filter(job=>{
    // hide inactive jobs
    if(job && job.status && normalizeType(job.status) === 'inactive') return false;
    // filter by type if selected
    if(selTypeNorm!=='any'){
      const jtNorm = normalizeType(job.job_type);
      if(!jtNorm.includes(selTypeNorm)) return false;
    }
    // search query matching title, client, location
    if(q){
      const hay = ((job.title||job.job_title||'') + ' ' + (job.client||'') + ' ' + (job.location||'')).toLowerCase();
      if(!hay.includes(q)) return false;
    }
    return true;
  });
  currentPage = 1;
  renderJobs();
}
function renderJobs(){
  results.innerHTML='';
  const total = filtered.length;
  if(!total){ noResults.classList.remove('hidden'); document.getElementById('pagination').innerHTML=''; return; }
  noResults.classList.add('hidden');

  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);
  pageItems.forEach(j=>{
    const jobIdent = j.id || j.job_id || j.jobId || j.jobID || '';
    results.innerHTML+=`
      <div class="bg-white border rounded-xl p-3 hover:shadow-md transition cursor-pointer" onclick="showJobDetails('${jobIdent}')">
        <h4 class="font-semibold">${escapeHtml(j.title||j.job_title||j.jobTitle||'')}</h4>
        <p class="text-xs text-neutral-500">${escapeHtml(j.client||'—')} • ${escapeHtml(j.location||'—')}</p>
      </div>
    `;
  });
  renderPagination();
}

async function showJobDetails(id){
  const res=await fetch(api+'?action=get&id='+encodeURIComponent(id));
  const j=(await res.json()).data;
  // store current job for apply modal fallback
  window.currentJob = j || { id: id };
  const list=(txt)=>txt?txt.split(',').map(t=>`<li class="list-disc ml-5 mt-1">${escapeHtml(t)}</li>`).join(''):'';
  jobDetails.innerHTML=`
    <h2 class="text-2xl md:text-3xl font-bold mb-1">${escapeHtml(j.title)}</h2>
    <p class="text-xs md:text-sm text-neutral-500 mb-4">${escapeHtml(j.client||'—')} • ${escapeHtml(j.location||'—')}</p>
    <div class="space-y-4 text-sm">
      <div><h3 class="font-semibold border-b pb-1">Job Description</h3><p>${escapeHtml(j.job_description||'—')}</p></div>
      ${j.skills?`<div><h3 class="font-semibold border-b pb-1">Skills</h3><ul>${list(j.skills)}</ul></div>`:''}
      ${j.qualifications?`<div><h3 class="font-semibold border-b pb-1">Qualifications</h3><ul>${list(j.qualifications)}</ul></div>`:''}
      ${j.benefits?`<div><h3 class="font-semibold border-b pb-1">Benefits</h3><ul>${list(j.benefits)}</ul></div>`:''}
      ${j.salary?`<div><h3 class="font-semibold border-b pb-1">Salary</h3><p>${escapeHtml(j.salary)}</p></div>`:''}
      <button onclick="openApplyModal()" class="bg-emerald-700 text-white px-4 py-2 rounded-xl font-semibold mt-2">Apply Now</button>
    </div>
  `;
}

// If the page is opened with a job_id query param, open that job detail automatically
document.addEventListener('DOMContentLoaded', ()=>{
  try{
    const params = new URLSearchParams(window.location.search);
    const jobId = params.get('job_id') || params.get('id');
    if(jobId){
      // fetch and show the job details
      showJobDetails(jobId);
      // scroll the details area into view
      setTimeout(()=>{ const el = document.getElementById('jobDetails'); if(el) el.scrollIntoView({behavior:'smooth'}); }, 200);
    }
  }catch(e){ /* ignore */ }
});

document.getElementById('applyFilters').addEventListener('click', applyFiltersFn);
locationSelect.onchange=()=>fetchJobs(locationSelect.value!=='any'?locationSelect.value:'');
document.getElementById('clearFilters').addEventListener('click', ()=>{
  jobTypeSelect.value='any'; locationSelect.value='any';
  const s = document.getElementById('searchJobs');
  if(s){ s.value = ''; const clearBtn = document.getElementById(' clearSearch'); if(clearBtn) clearBtn.classList.add('hidden'); }
  fetchJobs();
});
loadProvinces().then(()=>fetchJobs());

// Live polling: refresh jobs list when admin creates/updates jobs (no page reload)
(function(){
  let _jobPollIntervalMs = 15000;
  let _jobPollTimer = null;
  let _jobPollInFlight = false;
  async function pollOnce(){
    if(_jobPollInFlight) return;
    _jobPollInFlight = true;
    try{
      const res = await fetch(api + '?action=list');
      if(!res.ok) return;
      const j = await res.json().catch(()=>null);
      if(!j || !j.success) return;
      const newJobs = Array.isArray(j.data) ? j.data : [];

      // Quick equality check: length or ids or important fields
      const oldMap = new Map(jobs.map(r=>[String(r.id||r.job_id||''), r]));
      let changed = false;
      if(newJobs.length !== jobs.length) changed = true;
      else{
        for(const nj of newJobs){
          const id = String(nj.id||nj.job_id||'');
          const old = oldMap.get(id);
          if(!old){ changed = true; break; }
          if(String((nj.title||'')).trim() !== String((old.title||'')).trim() || String((nj.client||'')).trim() !== String((old.client||'')).trim() || String((nj.status||'')).trim() !== String((old.status||'')).trim() || String((nj.created_at||'')).trim() !== String((old.created_at||'')).trim()){
            changed = true; break;
          }
        }
      }

      if(changed){
        jobs = newJobs;
        applyFiltersFn();
        // if a job detail is currently shown, refresh it if it still exists
        try{
          if(window.currentJob && (window.currentJob.id || window.currentJob.job_id)){
            const curId = String(window.currentJob.id || window.currentJob.job_id || '');
            const found = jobs.find(x => String(x.id||x.job_id||'') === curId);
            if(found) showJobDetails(curId);
            else document.getElementById('jobDetails').innerHTML = '<div class="text-center text-neutral-500 mt-12"><p class="text-base">Select a job on the left to view details</p></div>';
          }
        }catch(e){/* ignore */}
      }
    }catch(e){ console.error('[jobs-poll] error', e); }
    finally{ _jobPollInFlight = false; }
  }
  function start(){ if(_jobPollTimer) return; pollOnce(); _jobPollTimer = setInterval(pollOnce, _jobPollIntervalMs); }
  function stop(){ if(!_jobPollTimer) return; clearInterval(_jobPollTimer); _jobPollTimer = null; }
  document.addEventListener('visibilitychange', ()=>{ if(document.hidden) stop(); else start(); });
  start();
})();

// wire search input with debounce
document.addEventListener('DOMContentLoaded', ()=>{
  const s = document.getElementById('searchJobs');
  const clearBtn = document.getElementById('clearSearch');
  if(s){
    // show/hide clear button immediately on input
    s.addEventListener('input', ()=>{ if(clearBtn) clearBtn.classList.toggle('hidden', !s.value); });
    // apply filters with debounce
    s.addEventListener('input', debounce(()=>{ applyFiltersFn(); }, 300));
  }
  if(clearBtn){
    clearBtn.addEventListener('click', ()=>{ if(s){ s.value=''; applyFiltersFn(); s.focus(); } clearBtn.classList.add('hidden'); });
  }
});

// modal helpers
function openLogin(){ document.getElementById('loginModal').classList.remove('hidden'); }
function closeLogin(){ document.getElementById('loginModal').classList.add('hidden'); }
function openRegister(){ document.getElementById('registerModal').classList.remove('hidden'); }
function closeRegister(){ document.getElementById('registerModal').classList.add('hidden'); }
function openRegisterFromLogin(){ closeLogin(); openRegister(); }

document.addEventListener('DOMContentLoaded', ()=>{
  const b = document.getElementById('openLogin');
  const bm = document.getElementById('openLoginMobile');
  if(b) b.addEventListener('click', openLogin);
  if(bm) bm.addEventListener('click', openLogin);
  // user menu toggles and logout
  const userBtn = document.getElementById('userMenuBtn');
  const userDropdown = document.getElementById('userDropdown');
  if(userBtn && userDropdown) userBtn.addEventListener('click', ()=> userDropdown.classList.toggle('hidden'));
  const userBtnM = document.getElementById('userMenuBtnMobile');
  const userDropdownM = document.getElementById('userMobileDropdown');
  if(userBtnM && userDropdownM) userBtnM.addEventListener('click', ()=> userDropdownM.classList.toggle('hidden'));
  const logoutBtn = document.getElementById('logoutBtn');
  if(logoutBtn) logoutBtn.addEventListener('click', async ()=>{ Swal.fire({ title:'Logging out...', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } }); try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers: {'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({ icon:'success', title:'Logged out', text:'You have been logged out', timer: 800, timerProgressBar: true, showConfirmButton:false }); window.location.href='index.php'; }catch(e){ Swal.fire({ title:'Error', text:'Logout failed', icon:'error', timer: 1500, timerProgressBar: true, showConfirmButton: false }); } });
  const logoutBtnM = document.getElementById('logoutBtnMobile');
  if(logoutBtnM) logoutBtnM.addEventListener('click', async ()=>{ Swal.fire({ title:'Logging out...', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } }); try{ await fetch('../backend/logout.php',{method:'POST',credentials:'same-origin', headers: {'X-Requested-With':'XMLHttpRequest'}}); await Swal.fire({ icon:'success', title:'Logged out', text:'You have been logged out', timer: 800, timerProgressBar: true, showConfirmButton:false }); window.location.href='index.php'; }catch(e){ Swal.fire({ title:'Error', text:'Logout failed', icon:'error', timer: 1500, timerProgressBar: true, showConfirmButton: false }); } });
  const lf = document.getElementById('loginForm');
  if(lf) lf.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const form = new FormData(lf);
    const body = { email: form.get('email'), password: form.get('password') };
    Swal.fire({ title:'Logging in...', text:'Please wait', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
    try{
      const res = await fetch('../backend/login.php', { method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
      const j = await res.json();
      if(j.success){
        closeLogin();
        await Swal.fire({ icon: 'success', title: 'Login successful', text: 'You are now logged in', timer: 1400, timerProgressBar: true, showConfirmButton: false });
        if(j.role === 'admin'){ window.location.href = 'admin/index.php'; } else { window.location.href = 'jobs.php'; }
      } else {
        Swal.fire({ icon: 'error', title: 'Login failed', text: j.message || 'Invalid credentials', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      }
    }catch(err){ console.error(err); Swal.fire({ icon: 'error', title: 'Error', text: 'Login error', timer: 2000, timerProgressBar: true, showConfirmButton: false }); }
  });

  const rf = document.getElementById('registerForm');
  if(rf) rf.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const f = new FormData(rf);
    const firstName = f.get('firstName') || '';
    const lastName = f.get('lastName') || '';
    const contact = f.get('contact') || '';
    const email = f.get('email') || '';
    const password = f.get('password') || '';
    
    // Validate first name
    if(!validateName(firstName)) {
      Swal.fire({ icon: 'error', title: 'Invalid First Name', text: 'First name should contain letters, spaces, hyphens, or apostrophes only', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    // Validate last name
    if(!validateName(lastName)) {
      Swal.fire({ icon: 'error', title: 'Invalid Last Name', text: 'Last name should contain letters, spaces, hyphens, or apostrophes only', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    // Validate email
    if(!validateEmail(email)) {
      Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email address', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    // Validate phone number
    if(!validatePhone(contact)) {
      Swal.fire({ icon: 'error', title: 'Invalid Phone Number', text: 'Phone number must be 11 digits and start with 0 (e.g., 09171234567)', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    const body = { firstName, lastName, contact, email, password };
    Swal.fire({ title:'Creating account...', text:'Please wait', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
    try{
      const res = await fetch('../backend/register.php', { method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
      const j = await res.json();
      if(j.success){
        closeRegister();
        await Swal.fire({ icon: 'success', title: 'Account created', text: 'Your account was created successfully', timer: 1500, timerProgressBar: true, showConfirmButton: false });
        window.location.href = 'jobs.php';
      } else {
        Swal.fire({ icon: 'error', title: 'Registration failed', text: j.message || 'Unable to register', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      }
    }catch(err){ console.error(err); Swal.fire({ icon: 'error', title: 'Error', text: 'Registration error', timer: 2000, timerProgressBar: true, showConfirmButton: false }); }
  });

  const cf = document.getElementById('contactForm'); if(cf) cf.addEventListener('submit', e=>{ e.preventDefault(); alert('Message sent (demo)'); cf.reset(); });
});
</script>
<script>
// Apply modal helpers
function openApplyModal(jobId){
  const f = document.getElementById('applyForm');
  if(f){
    // accept optional jobId parameter; use window.currentJob as fallback
    let resolvedId = null;
    let resolvedTitle = '';
    if (jobId) resolvedId = jobId;
    else if (window.currentJob) resolvedId = window.currentJob.id || window.currentJob.job_id || window.currentJob.jobId || window.currentJob.jobID || null;
    if (window.currentJob) resolvedTitle = window.currentJob.title || window.currentJob.job_title || window.currentJob.jobTitle || '';
    // set hidden inputs
    if (resolvedId) document.getElementById('applyJobId').value = resolvedId;
    else document.getElementById('applyJobId').value = '';
    document.getElementById('applyJobTitle').value = resolvedTitle;
    // if user not logged in, prompt to sign in first
    if(!window.currentUserId){
      Swal.fire({
        icon: 'info',
        title: 'Please sign in',
        text: 'You must be signed in to apply. Would you like to sign in now?',
        showCancelButton: true,
        confirmButtonText: 'Sign in',
        cancelButtonText: 'Cancel'
      }).then(r=>{ if(r.isConfirmed) openLogin(); else {/* do nothing */} });
      return;
    }
    document.getElementById('applyModal').classList.remove('hidden');
  }
}

document.addEventListener('DOMContentLoaded', ()=>{
  const applyForm = document.getElementById('applyForm');
  if(!applyForm) return;
  applyForm.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const fd = new FormData(applyForm);
    const fullName = fd.get('full_name') || '';
    const email = fd.get('email') || '';
    const phone = fd.get('phone') || '';
    
    // Validate full name
    if(!validateName(fullName)) {
      Swal.fire({ icon: 'error', title: 'Invalid Full Name', text: 'Full name should contain letters, spaces, hyphens, or apostrophes only', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    // Validate email
    if(!validateEmail(email)) {
      Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email address', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    // Validate phone number
    if(!validatePhone(phone)) {
      Swal.fire({ icon: 'error', title: 'Invalid Phone Number', text: 'Phone number must be 11 digits and start with 0 (e.g., 09171234567)', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      return;
    }
    
    Swal.fire({ title:'Submitting application...', text:'Please wait', icon:'info', allowOutsideClick: false, allowEscapeKey: false, didOpen: ()=>{ Swal.showLoading(); } });
    try{
      const res = await fetch('../backend/apply.php', { method: 'POST', credentials: 'same-origin', body: fd });
      const j = await res.json();
      if(j.success){
        document.getElementById('applyModal').classList.add('hidden');
        applyForm.reset();
        await Swal.fire({ icon: 'success', title: 'Application submitted', text: j.message || 'Thank you for applying.', timer: 1500, timerProgressBar: true, showConfirmButton: false });
      } else if (j.requires_login) {
        // prompt login
        const r = await Swal.fire({ icon: 'info', title: 'Sign in required', text: 'Please sign in to continue', showCancelButton: true, confirmButtonText: 'Sign in' });
        if(r.isConfirmed) openLogin();
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: j.message || 'Unable to submit application', timer: 2000, timerProgressBar: true, showConfirmButton: false });
      }
    }catch(err){
      console.error(err);
      Swal.fire({ icon: 'error', title: 'Error', text: 'Network or server error', timer: 2000, timerProgressBar: true, showConfirmButton: false });
    }
  });
});
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
 
