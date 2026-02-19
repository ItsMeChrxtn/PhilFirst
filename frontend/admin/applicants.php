<?php include 'header_clean.php'; ?>

<!-- ================= APPLICANTS HEADER & FILTERS ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-6">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-transparent"></div>
  <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow">
          <i class="fa-solid fa-users"></i>
        </span>
        Applicants
      </h2>
      <p class="text-sm text-neutral-500 mt-1">Manage and review applicant submissions</p>
    </div>
    <!--<button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow hover:bg-emerald-700 transition-all">
      <i class="fa-solid fa-plus"></i>
      Add Applicant
    </button>
-->
  </div>
</div>

<div class="rounded-2xl border border-neutral-200 bg-white shadow-sm mb-6">
  <div class="p-4 md:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

    <!-- Status Pills -->
    <div id="statusPillsApplicants" class="relative flex flex-wrap gap-2">
      <div id="statusIndicatorApplicants" class="absolute rounded-full bg-emerald-600 transition-all duration-300" style="height:36px; width:0; left:0; top:0; z-index:0;"></div>
      <button class="appStatusBtn selected relative z-10 px-4 py-2 rounded-full text-sm font-medium text-white transition" data-status="all">All</button>
      <button class="appStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 transition" data-status="Pending">Pending</button>
      <button class="appStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 transition" data-status="For interview">For interview</button>
      <button class="appStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 transition" data-status="Rejected">Rejected</button>
      <button class="appStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 transition" data-status="Accepted">Accepted</button>
    </div>

    <!-- Search -->
    <div class="flex w-full lg:w-auto gap-2">
      <div class="relative w-full lg:w-80">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm"></i>
        <input id="applicantSearch" placeholder="Search name, position, date" class="w-full rounded-xl border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:outline-none transition" />
      </div>
      <button id="appSearchApply" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">Apply</button>
      <button id="appSearchClear" class="rounded-xl bg-neutral-100 px-4 py-2.5 text-sm hover:bg-neutral-200 transition">Clear</button>
    </div>
  </div>
</div>

    <!-- VIEW APPLICANT MODAL -->
    <div id="viewApplicantModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 p-6 md:p-8">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xl font-semibold text-gray-800">Applicant Details</h3>
          <button id="closeViewApplicant" class="text-gray-400 hover:text-gray-600 text-2xl p-2 rounded-md">&times;</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">Full Name</p>
            <p id="viewAppName" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Email</p>
            <p id="viewAppEmail" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Phone</p>
            <p id="viewAppPhone" class="font-medium text-gray-800"></p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Applied</p>
            <p id="viewAppApplied" class="font-medium text-gray-800"></p>
          </div>
          <div class="md:col-span-2">
            <p class="text-sm text-gray-500">Position (Job)</p>
            <p id="viewAppPosition" class="font-medium text-gray-800"></p>
          </div>
          <div class="md:col-span-2">
            <p class="text-sm text-gray-500">Job Description</p>
            <p id="viewAppJobDesc" class="text-sm text-gray-700"></p>
          </div>
          <div class="md:col-span-2">
            <p class="text-sm text-gray-500">Message</p>
            <p id="viewAppMessage" class="text-sm text-gray-700"></p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Resume</p>
            <p id="viewAppResume" class="text-sm"><button id="viewAppResumeBtn" class="text-blue-600 underline"></button></p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Status</p>
            <p id="viewAppStatus" class="font-medium"></p>
          </div>
        </div>
        <div class="flex justify-end mt-6">
          <button id="closeViewApplicant2" class="px-4 py-2 border rounded hover:bg-gray-100">Close</button>
        </div>
      </div>
    </div>

<!-- ================= APPLICANTS TABLE ================= -->
<div class="rounded-2xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
  <table class="min-w-full">
    <thead class="bg-neutral-50 border-b border-neutral-200">
      <tr>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Name</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Position</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Phone</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Applied</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Status</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Actions</th>
      </tr>
    </thead>
    <tbody id="applicantTableBody" class="divide-y divide-neutral-200"></tbody>
  </table>
</div>

<?php include 'footer.php'; ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Alerts helper -->
<script src="../assets/js/alerts.js"></script>

<script>
  const tbody = document.getElementById('applicantTableBody');
  function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g,c=>({'&':'&amp;','"':'&quot;',"'":'&#39;','<':'&lt;','>':'&gt;'}[c])); }

  // Fetch applicants from backend
  async function loadApplicants(){
    try{
      const res = await fetch('../../backend/get_applications.php');
      const json = await res.json();
      if(!json.success) return console.error('Failed to load applicants', json.message || json);
      const data = json.data || [];
      // store raw rows for detail view
      window.applicantsMap = {};
      // Map to expected shape and render
      const list = data.map(r => { window.applicantsMap[r.id] = r; return { id: r.id, name: r.full_name || r.name || r.user_name || '', position: r.resolved_job_title || '', phone: r.phone || r.contact || r.phone_number || '', applied: r.created_at || r.created || '', status: r.status || 'Pending' }; });
      renderApplicants(list);
      applyApplicantFilters();
    }catch(err){ console.error('Error fetching applicants', err); }
  }

  function renderApplicants(list){
    tbody.innerHTML = '';
    list.forEach(a=>{
      const tr = document.createElement('tr'); tr.className='hover:bg-gray-50';
      tr.dataset.status = a.status || '';
      const searchText = ((a.name||'') + ' ' + (a.position||'') + ' ' + (a.phone||'') + ' ' + (a.applied||'')).toLowerCase();
      tr.dataset.search = searchText;
      tr.innerHTML = `
          <td class="px-6 py-4 font-medium text-gray-700">${escapeHtml(a.name)}</td>
          <td class="px-6 py-4 text-gray-600">${escapeHtml(a.position)}</td>
          <td class="px-6 py-4 text-gray-600">${escapeHtml(a.phone)}</td>
          <td class="px-6 py-4 text-gray-600">${escapeHtml(a.applied)}</td>
          <td class="px-6 py-4"><span data-status-span class="px-3 py-1 text-xs rounded-full ${badgeClassFor(a.status)} font-semibold">${escapeHtml(a.status)}</span></td>
            <td class="px-6 py-4 flex items-center gap-3 justify-start">
  <!-- View -->
  <button class="viewApplicantBtn w-10 h-10 flex items-center justify-center rounded-full bg-blue-50 hover:bg-blue-100 shadow-lg transition-transform transform hover:scale-110" data-id="${a.id}" title="View Applicant">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
  </button>

  <!-- Resume (view inline) -->
  <button class="resumeBtn w-10 h-10 flex items-center justify-center rounded-full bg-teal-50 hover:bg-teal-100 shadow-lg transition-transform transform hover:scale-110" data-id="${a.id}" title="View Resume">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-700" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a2 2 0 00-2 2v8H8l4 4 4-4h-2V4a2 2 0 00-2-2z"/></svg>
  </button>

  <!-- Accept -->
  <button class="appActionBtn w-10 h-10 flex items-center justify-center rounded-full bg-green-800 hover:bg-green-900 shadow-lg transition-transform transform hover:scale-110" data-id="${a.id}" data-action="Accepted" title="Accept">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
    </svg>
  </button>

  <!-- For Interview -->
  <button class="appActionBtn w-10 h-10 flex items-center justify-center rounded-full bg-emerald-100 hover:bg-emerald-200 shadow-lg transition-transform transform hover:scale-110" data-id="${a.id}" data-action="For interview" title="For Interview">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m0 0a9 9 0 11-6 0 9 9 0 016 0z" />
    </svg>
  </button>

  <!-- Reject -->
  <button class="appActionBtn w-10 h-10 flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 shadow-lg transition-transform transform hover:scale-110" data-id="${a.id}" data-action="Rejected" title="Reject">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
</td>

`;
      tbody.appendChild(tr);
      // set initial disabled state for action buttons based on status
      const acceptBtnInit = tr.querySelector('[data-action="Accepted"]');
      const forBtnInit = tr.querySelector('[data-action="For interview"]');
      const rejBtnInit = tr.querySelector('[data-action="Rejected"]');
      const cur = (a.status||'').toLowerCase();
      if(acceptBtnInit){ acceptBtnInit.disabled = (cur === 'accepted'); acceptBtnInit.classList.toggle('opacity-50', acceptBtnInit.disabled); acceptBtnInit.classList.toggle('cursor-not-allowed', acceptBtnInit.disabled); }
      if(forBtnInit){ forBtnInit.disabled = (cur === 'for interview' || cur === 'for_interview' || cur === 'for-interview'); forBtnInit.classList.toggle('opacity-50', forBtnInit.disabled); forBtnInit.classList.toggle('cursor-not-allowed', forBtnInit.disabled); }
      if(rejBtnInit){ rejBtnInit.disabled = (cur === 'rejected'); rejBtnInit.classList.toggle('opacity-50', rejBtnInit.disabled); rejBtnInit.classList.toggle('cursor-not-allowed', rejBtnInit.disabled); }
    });
      // wire up handlers after rendering
      document.querySelectorAll('.viewApplicantBtn').forEach(b=>b.addEventListener('click', async ()=>{
        const id = b.dataset.id;
        const rowData = (window.applicantsMap && window.applicantsMap[id]) ? window.applicantsMap[id] : null;
        if(!rowData) return Swal.fire({ icon:'error', title:'Not found' });
        // populate modal fields
        document.getElementById('viewAppName').textContent = rowData.full_name || rowData.name || '';
        document.getElementById('viewAppEmail').textContent = rowData.email || '';
        document.getElementById('viewAppPhone').textContent = rowData.phone || '';
        document.getElementById('viewAppApplied').textContent = rowData.created_at || rowData.created || '';
        document.getElementById('viewAppPosition').textContent = rowData.resolved_job_title || '';
        document.getElementById('viewAppJobDesc').textContent = rowData.resolved_job_description || '';
        document.getElementById('viewAppMessage').textContent = rowData.message || '';
        const resumeLink = document.getElementById('viewAppResumeLink');
        const resumeBtn = document.getElementById('viewAppResumeBtn');
        if(resumeBtn){ if(rowData.resume_path){ resumeBtn.textContent = 'View Resume'; resumeBtn.onclick = ()=> openResume(rowData.resume_path); } else { resumeBtn.textContent = ''; resumeBtn.onclick = null; } }
        const statusEl = document.getElementById('viewAppStatus'); statusEl.textContent = rowData.status || '';
        // show modal
        const modal = document.getElementById('viewApplicantModal'); if(modal) modal.classList.remove('hidden');
        // disable action buttons appropriately inside the row element
      }));

      // resume buttons in each row
      document.querySelectorAll('.resumeBtn').forEach(b=>b.addEventListener('click', ()=>{
        const id = b.dataset.id;
        const rowData = (window.applicantsMap && window.applicantsMap[id]) ? window.applicantsMap[id] : null;
        if(!rowData || !rowData.resume_path) return Swal.fire({ icon:'info', title:'No resume', text:'No resume available for this applicant.' });
        openResume(rowData.resume_path);
      }));

      document.querySelectorAll('.appActionBtn').forEach(b=>b.addEventListener('click', async (e)=>{
        const id = b.dataset.id;
        const action = b.dataset.action;
        if(!id || !action) return;
        let icon = 'question';
        let confirmColor = '#16a34a';
        let title = `Confirm ${action}?`;
        if(action === 'Accepted'){ icon='success'; confirmColor='#065f46'; }
        if(action === 'For interview'){ icon='info'; confirmColor='#15803d'; }
        if(action === 'Rejected'){ icon='warning'; confirmColor='#dc2626'; }

        const choice = await Swal.fire({ title: title, text: `Are you sure you want to mark this applicant as "${action}"?`, icon: icon, showCancelButton: true, confirmButtonColor: confirmColor, confirmButtonText: 'Yes', cancelButtonText: 'Cancel' });
        if(!choice.isConfirmed) return;

        try{
          const loading = Swal.fire({ title: 'Updating...', allowOutsideClick:false, didOpen: ()=>Swal.showLoading() });
          const res = await fetch('../../backend/update_application.php', { method: 'POST', headers: { 'Content-Type':'application/json' }, body: JSON.stringify({ id: id, status: action }) });
          const json = await res.json();
          Swal.close();
          if(json.success){
            await Swal.fire({ icon: 'success', title: 'Updated', text: 'Application status updated.' });
            // update row UI
            const row = b.closest('tr');
            if(row){
              row.dataset.status = action;
              const span = row.querySelector('[data-status-span]');
              if(span){
                  span.textContent = action;
                  span.className = 'px-3 py-1 text-xs rounded-full font-semibold ' + badgeClassFor(action);
                }
              // update action buttons state in this row
              const acceptBtn = row.querySelector('[data-action="Accepted"]');
              const forBtn = row.querySelector('[data-action="For interview"]');
              const rejBtn = row.querySelector('[data-action="Rejected"]');
              if(acceptBtn) { acceptBtn.disabled = (action.toLowerCase() === 'accepted'); acceptBtn.classList.toggle('opacity-50', acceptBtn.disabled); acceptBtn.classList.toggle('cursor-not-allowed', acceptBtn.disabled); }
              if(forBtn) { forBtn.disabled = (action.toLowerCase() === 'for interview'); forBtn.classList.toggle('opacity-50', forBtn.disabled); forBtn.classList.toggle('cursor-not-allowed', forBtn.disabled); }
              if(rejBtn) { rejBtn.disabled = (action.toLowerCase() === 'rejected'); rejBtn.classList.toggle('opacity-50', rejBtn.disabled); rejBtn.classList.toggle('cursor-not-allowed', rejBtn.disabled); }
            }
            // reload page after Swal closes to reflect changes globally
            window.location.reload();
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: json.message || 'Failed to update' });
          }
        }catch(err){
          Swal.close(); console.error(err); Swal.fire({ icon: 'error', title: 'Server error', text: 'Unable to update application.' });
        }
      }));
  }

  function badgeClassFor(status){
    if(!status) return 'bg-gray-100 text-gray-700';
    const s = status.toLowerCase();
    if(s === 'pending') return 'bg-yellow-100 text-yellow-800';
    if(s === 'accepted' || s === 'accept') return 'bg-green-800 text-white';
    if(s === 'for interview' || s === 'for_interview' || s === 'for-interview') return 'bg-emerald-100 text-emerald-700';
    if(s === 'rejected') return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-gray-700';
  }

  // Filtering
  function getSelectedStatusApplicants(){ const b = document.querySelector('.appStatusBtn.selected'); return b ? b.dataset.status : 'all'; }
  const statusBtns = document.querySelectorAll('.appStatusBtn');
  const statusPills = document.getElementById('statusPillsApplicants');
  const statusIndicator = document.getElementById('statusIndicatorApplicants');
  function moveIndicatorToApplicants(btn){ if(!btn || !statusPills || !statusIndicator) return; const rBtn = btn.getBoundingClientRect(); const rParent = statusPills.getBoundingClientRect(); const left = rBtn.left - rParent.left + statusPills.scrollLeft; statusIndicator.style.width = rBtn.width + 'px'; statusIndicator.style.height = rBtn.height + 'px'; statusIndicator.style.transform = `translateX(${left}px)`; }
  statusBtns.forEach((b,idx)=>{ if(idx===0) b.classList.add('selected'); b.addEventListener('click', ()=>{ statusBtns.forEach(x=>{ x.classList.remove('selected'); x.classList.remove('text-white'); x.classList.add('text-neutral-700'); }); b.classList.add('selected'); b.classList.remove('text-neutral-700'); b.classList.add('text-white'); moveIndicatorToApplicants(b); applyApplicantFilters(); }); });
  const initialApp = document.querySelector('.appStatusBtn.selected'); if(initialApp) setTimeout(()=>moveIndicatorToApplicants(initialApp),50); window.addEventListener('resize', ()=>{ const sel = document.querySelector('.appStatusBtn.selected'); if(sel) moveIndicatorToApplicants(sel); });

  const appSearchInput = document.getElementById('applicantSearch');
  document.getElementById('appSearchApply').addEventListener('click', ()=> applyApplicantFilters());
  document.getElementById('appSearchClear').addEventListener('click', ()=>{ appSearchInput.value=''; applyApplicantFilters(); });

  function applyApplicantFilters(){
    const q = (appSearchInput.value||'').toLowerCase().trim();
    const status = getSelectedStatusApplicants();
    Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
      const rowStatus = tr.dataset.status || '';
      const hay = tr.dataset.search || '';
      const statusOk = status==='all' ? true : (rowStatus === status);
      const textOk = !q ? true : hay.includes(q);
      tr.style.display = (statusOk && textOk) ? '' : 'none';
    });
  }

  // initial load
  loadApplicants();

  // Modal close handlers
  const viewModal = document.getElementById('viewApplicantModal');
  document.getElementById('closeViewApplicant').addEventListener('click', ()=>{ if(viewModal) viewModal.classList.add('hidden'); });
  document.getElementById('closeViewApplicant2').addEventListener('click', ()=>{ if(viewModal) viewModal.classList.add('hidden'); });
  // close when clicking backdrop
  if(viewModal) viewModal.addEventListener('click', (e)=>{ if(e.target === viewModal) viewModal.classList.add('hidden'); });

  function openResume(path){
    // Normalize path: make it absolute from site root if not an absolute URL
    try{
      if(!path) throw new Error('No path');
      let url = path;
      if(!/^https?:\/\//i.test(url) && url.indexOf('/') !== 0){ url = '/' + url; }

      // Check resource availability with HEAD before embedding
      fetch(url, { method: 'HEAD', credentials: 'same-origin' }).then(res => {
        if(res.ok){
          const ct = res.headers.get('content-type') || '';
          // If browser can render (pdf or image), embed in iframe; otherwise open in new tab
          if(ct.includes('pdf') || ct.startsWith('image/') || ct.includes('text/') || ct.includes('application/')){
            const iframe = `<div style="width:100%;height:70vh;max-height:70vh"><iframe src="${url}" style="width:100%;height:100%;border:0;border-radius:8px"></iframe></div>`;
            Swal.fire({ title: 'View Resume', html: iframe, width: '80%', showCloseButton: true, showConfirmButton: false });
          } else {
            // fallback to new tab
            window.open(url, '_blank');
          }
        } else {
          // not found or forbidden
          Swal.fire({ icon: 'error', title: 'File not found', text: 'Resume file could not be loaded (404/403).' });
        }
      }).catch(err => {
        console.warn('resume HEAD check failed', err);
        // try opening directly as last resort
        try{ window.open(path, '_blank'); }catch(e){ Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to open resume.' }); }
      });
    }catch(e){ Swal.fire({ icon: 'error', title: 'Error', text: 'No resume path provided.' }); }
  }
</script>
