<?php include 'header_clean.php'; ?>


<!-- ================= APPLICANTS HEADER & FILTERS ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-6">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-transparent"></div>
  <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow">
          <i class="fa-solid fa-calendar-days"></i>
        </span>
        Calendar Schedule
      </h2>
      <p class="text-sm text-neutral-500 mt-1">View interview schedules on the calendar. Click a date to see scheduled applicants or click "Schedule" on an applicant row to add a new interview.</p>
    </div>
    <div class="flex gap-2">
      <!--<button id="exportPdfBtn" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-emerald-700 transition-all">
        <i class="fa-solid fa-file-pdf"></i>
        View Applicants (PDF)
      </button>-->
    </div>
  </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- Calendar -->
  <div class="col-span-1 bg-white rounded-2xl shadow-sm p-4">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <button id="prevMonth" class="px-3 py-1 rounded bg-neutral-100">‹</button>
        <div class="font-semibold" id="calendarTitle"></div>
        <button id="nextMonth" class="px-3 py-1 rounded bg-neutral-100">›</button>
      </div>
      <button id="todayBtn" class="px-3 py-1 rounded bg-emerald-600 text-white">Today</button>
    </div>
    <div id="calendar" class="grid grid-cols-7 gap-1 text-sm"></div>
  </div>

  <!-- Applicants list -->
  <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-4">
    <h3 class="font-semibold mb-3">Applicants</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-neutral-50 border-b border-neutral-200">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Name</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Position</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Applied</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Actions</th>
          </tr>
        </thead>
        <tbody id="applicantTableBody" class="divide-y divide-neutral-200"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Day view modal -->
<div id="dayModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
  <div id="dayModalInner" class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 id="dayModalTitle" class="text-lg font-semibold">Schedules</h3>
      <div class="flex items-center gap-2">
        <button id="viewDayPdfBtn" class="px-3 py-1 rounded bg-neutral-100">View PDF</button>
        <button id="downloadDayPdfBtn" class="px-3 py-1 rounded bg-emerald-600 text-white hidden">Download PDF</button>
        <button id="closeDayModal" class="text-gray-400 text-2xl">&times;</button>
      </div>
    </div>
    <div id="dayModalInnerContent">
      <div id="dayModalList" class="space-y-3 max-h-72 overflow-auto"></div>
      <div id="dayModalPdfPreview" class="hidden max-h-72 overflow-auto border-l pl-4"></div>
    </div>
    <div class="flex justify-end mt-4"><button id="closeDayModal2" class="px-4 py-2 rounded bg-neutral-100">Close</button></div>
  </div>
</div>

<!-- Schedule modal -->
<div id="scheduleModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Schedule Interview</h3>
      <button id="closeScheduleModal" class="text-gray-400 text-2xl">&times;</button>
    </div>
    <form id="scheduleForm" class="space-y-3">
      <input type="hidden" id="sch_applicant_id">
      <div>
        <label class="text-sm text-gray-600">Applicant</label>
        <div id="sch_applicant_name" class="font-medium"></div>
      </div>
      <div>
        <label class="text-sm text-gray-600">Position</label>
        <input id="sch_position" class="w-full border rounded px-3 py-2" />
      </div>
      <div>
        <label class="text-sm text-gray-600">Date & Time</label>
        <input id="sch_datetime" type="datetime-local" class="w-full border rounded px-3 py-2" required />
      </div>
      <script>document.querySelectorAll('input[type="datetime-local"]').forEach(el => { if(el.id === 'sch_datetime') { const now = new Date(); now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); el.min = now.toISOString().slice(0,16); } });</script>
      <div class="flex justify-end gap-2">
        <button type="button" id="cancelSchedule" class="px-4 py-2 rounded bg-neutral-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- PDF view modal -->
<div id="pdfModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Applicants (For Interview)</h3>
      <div class="flex items-center gap-2">
        <button id="downloadPdfBtn" class="px-3 py-1 rounded bg-emerald-600 text-white">Download PDF</button>
        <button id="closePdfModal" class="text-gray-400 text-2xl">&times;</button>
      </div>
    </div>
    <div id="pdfModalContent" class="max-h-72 overflow-auto">
      <!-- Rendered table will be injected here -->
    </div>
    <div class="flex justify-end mt-4"><button id="closePdfModal2" class="px-4 py-2 rounded bg-neutral-100">Close</button></div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- small inline script to drive calendar and scheduling -->
<script>
const applicantTbody = document.getElementById('applicantTableBody');
let applicants = [];
let schedules = []; // from backend
let schedulesByDate = {};

function fmtDateKey(d){ const y=d.getFullYear(); const m=('0'+(d.getMonth()+1)).slice(-2); const day=('0'+d.getDate()).slice(-2); return `${y}-${m}-${day}`; }

async function loadApplicants(){
  try{
    const res = await fetch('../../backend/get_applications.php');
    const json = await res.json();
    if(!json.success) return console.error('Failed to load applicants', json.message || json);
    const data = json.data || [];
    // Only include applicants with status "for interview" (case-insensitive)
    applicants = (data || []).filter(r => ((r.status||'').toString().toLowerCase().trim() === 'for interview')).map(r => ({ id: r.id, name: r.full_name || r.name || '', position: r.resolved_job_title || '', email: r.email || r.contact_email || '', phone: r.phone || r.contact || r.phone_number || '', applied: r.created_at || '', status: r.status || '' }));
    renderApplicants();
  }catch(err){ console.error('Error loading applicants', err); }
}

function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g,c=>({'&':'&amp;','"':'&quot;',"'":'&#39;','<':'&lt;','>':'&gt;'}[c])); }

function formatScheduleDateTime(dateStr){ if(!dateStr) return ''; try{ const dt = new Date(dateStr); if(isNaN(dt.getTime())) return dateStr; const time = dt.toLocaleString('en-US', { hour:'numeric', minute:'2-digit', hour12:true }); const date = dt.toLocaleString('en-US', { month:'long', day:'numeric', year:'numeric' }); return `${time} - ${date}`; }catch(e){ return dateStr; } }

// helper: add event listener safely (no error if element missing)
function addListenerSafe(id, event, cb){ const el = document.getElementById(id); if(!el){ console.warn('Element not found for listener', id); return null; } el.addEventListener(event, cb); return el; }

function renderApplicants(){
  applicantTbody.innerHTML='';
  applicants.forEach(a=>{
    const tr = document.createElement('tr'); tr.className='hover:bg-gray-50';
    const isScheduled = schedules.some(s => (s.applicant_id || '').toString() === (a.id||'').toString());
    const scheduledBtnHtml = isScheduled ? `<button class="px-3 py-1 rounded bg-emerald-600 text-white" disabled>Scheduled</button>` : `<button class="px-3 py-1 border rounded scheduleBtn" data-id="${a.id}" data-name="${escapeHtml(a.name)}" data-position="${escapeHtml(a.position)}">Schedule</button>`;
    tr.innerHTML = `
      <td class="px-4 py-3 font-medium text-gray-700">${escapeHtml(a.name)}</td>
      <td class="px-4 py-3 text-gray-600">${escapeHtml(a.position)}</td>
      <td class="px-4 py-3 text-gray-600">${escapeHtml(a.applied)}</td>
      <td class="px-4 py-3">${scheduledBtnHtml}</td>
    `;
    applicantTbody.appendChild(tr);
  });
  document.querySelectorAll('.scheduleBtn').forEach(b=>b.addEventListener('click', openScheduleForApplicant));
}

async function loadSchedules(){
  try{
    const res = await fetch('../../backend/schedule_api.php?t=' + Date.now());
    const json = await res.json();
    if(!json.success) return console.error('Failed to load schedules', json.message || json);
    schedules = json.data || [];
    schedulesByDate = {};
    schedules.forEach(s=>{
      const dtKey = s.scheduled_at.slice(0,10);
      schedulesByDate[dtKey] = schedulesByDate[dtKey] || [];
      schedulesByDate[dtKey].push(s);
    });
    renderCalendar(currentMonth, currentYear);
    // ensure applicants reflect scheduled state
    renderApplicants();
  }catch(err){ console.error('Error loading schedules', err); }
}

// simple calendar rendering
const calendarEl = document.getElementById('calendar');
const calendarTitle = document.getElementById('calendarTitle');
let now = new Date();
let currentMonth = now.getMonth();
let currentYear = now.getFullYear();

function renderCalendar(month, year){
  calendarEl.innerHTML='';
  const first = new Date(year, month, 1);
  const last = new Date(year, month+1, 0);
  const startDay = first.getDay();
  calendarTitle.textContent = first.toLocaleString(undefined, { month: 'long' }) + ' ' + year;
  // week day headers
  ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(d=>{
    const hd = document.createElement('div'); hd.className='text-center text-xs font-medium text-neutral-500'; hd.textContent = d; calendarEl.appendChild(hd);
  });
  for(let i=0;i<startDay;i++){ const e=document.createElement('div'); e.className='p-2'; calendarEl.appendChild(e); }
  for(let d=1; d<= last.getDate(); d++){
    const cell = document.createElement('div');
    const dt = new Date(year, month, d);
    const key = fmtDateKey(dt);
    const count = (schedulesByDate[key] || []).length;
    const today = new Date();
    today.setHours(0,0,0,0);
    dt.setHours(0,0,0,0);
    const isPast = dt < today;
    cell.className = 'p-2 rounded cursor-pointer hover:bg-neutral-100';
    if(count>0 && !isPast) cell.innerHTML = `<div class="flex items-center justify-between"><div class="text-sm font-medium">${d}</div><div class="text-xs px-2 py-0.5 bg-emerald-600 text-white rounded-full">${count}</div></div>`;
    else cell.innerHTML = `<div class="text-sm">${d}</div>`;
    cell.addEventListener('click', ()=> openDay(key));
    calendarEl.appendChild(cell);
  }
}

addListenerSafe('prevMonth','click', ()=>{ currentMonth--; if(currentMonth<0){ currentMonth=11; currentYear--; } renderCalendar(currentMonth,currentYear); });
addListenerSafe('nextMonth','click', ()=>{ currentMonth++; if(currentMonth>11){ currentMonth=0; currentYear++; } renderCalendar(currentMonth,currentYear); });
addListenerSafe('todayBtn','click', ()=>{ const t=new Date(); currentMonth=t.getMonth(); currentYear=t.getFullYear(); renderCalendar(currentMonth,currentYear); });

// day modal handling
const dayModal = document.getElementById('dayModal');
const dayModalTitle = document.getElementById('dayModalTitle');
const dayModalList = document.getElementById('dayModalList');
const dayModalPdfPreview = document.getElementById('dayModalPdfPreview');
const dayModalInner = document.getElementById('dayModalInner');
const dayModalInnerContent = document.getElementById('dayModalInnerContent');
const viewDayPdfBtn = document.getElementById('viewDayPdfBtn');
const downloadDayPdfBtn = document.getElementById('downloadDayPdfBtn');
function openDay(key){
  const items = schedulesByDate[key] || [];
  dayModalTitle.textContent = `Schedules for ${key}`;
  // Hide/clear PDF preview by default
  if(dayModalPdfPreview){ dayModalPdfPreview.classList.add('hidden'); dayModalPdfPreview.innerHTML = ''; }
  if(downloadDayPdfBtn) downloadDayPdfBtn.classList.add('hidden');
  if(dayModalInner){ dayModalInner.style.maxWidth = ''; }
  dayModalList.innerHTML = items.length ? items.map(i=> {
    const schedDate = new Date(i.scheduled_at);
    const now = new Date();
    const isPast = schedDate < now;
    const btnDisabled = isPast ? 'disabled' : '';
    const btnClass = isPast ? 'px-3 py-1 border rounded removeScheduleBtn opacity-50 cursor-not-allowed' : 'px-3 py-1 border rounded removeScheduleBtn';
    return `
    <div class="p-3 rounded border">
      <div class="flex items-start justify-between">
        <div>
          <div class="font-medium">${escapeHtml(i.applicant_name||'—')}</div>
          <div class="text-sm text-neutral-600">${escapeHtml(i.position||'')}</div>
          <div class="text-sm text-neutral-500">${formatScheduleDateTime(i.scheduled_at)}</div>
        </div>
        <div class="ml-4">
          <button class="${btnClass}" ${btnDisabled} data-schedule-id="${i.id}" data-applicant-id="${escapeHtml(i.applicant_id||'')}">Remove</button>
        </div>
      </div>
    </div>
  `;
  }).join('') : '<div class="p-4 text-center text-sm text-neutral-500">No applicants on the list</div>';
  // attach handlers for remove buttons inside modal
  setTimeout(()=>{
    dayModalList.querySelectorAll('.removeScheduleBtn').forEach(b=>b.addEventListener('click', async (ev)=>{
      ev.stopPropagation();
      const btn = ev.currentTarget;
      const schedId = btn.dataset.scheduleId;
      if(!schedId) return Swal.fire({ icon:'error', title:'Not found' });
      const r = await Swal.fire({ title:'Remove schedule?', text:'This will remove the interview schedule and re-enable scheduling for this applicant.', icon:'warning', showCancelButton:true, confirmButtonText:'Remove' });
      if(!r.isConfirmed) return;
      let removed = null;
      try{
        // Optimistic remove: remove from DOM/state immediately
        removed = schedules.find(s=> (s.id||'').toString() === (schedId||'').toString());
        schedules = schedules.filter(s=> (s.id||'').toString() !== (schedId||'').toString());
        const k = (removed && removed.scheduled_at) ? removed.scheduled_at.slice(0,10) : key;
        if(schedulesByDate[k]) schedulesByDate[k] = schedulesByDate[k].filter(s=> (s.id||'').toString() !== (schedId||'').toString());
        renderApplicants(); renderCalendar(currentMonth,currentYear);
        // Refresh modal content immediately to show updated list
        openDay(key);
        // Show brief success toast (very fast, 600ms) - keep modal open
        Swal.fire({ icon:'success', title:'Removed', timer:600, showConfirmButton:false });
        // fire delete request in background and reconcile
        const result = await deleteScheduleById(schedId);
        const j = result.json;
        if(j && j.success){
          // deletion confirmed on server, no need for another alert
        } else {
          // Reopen modal so user can see and retry
          dayModal.classList.remove('hidden');
          // revert: re-add removed schedule if available
          if(removed){ schedules.push(removed); schedulesByDate[k] = schedulesByDate[k] || []; schedulesByDate[k].push(removed); openDay(key); renderApplicants(); renderCalendar(currentMonth,currentYear); }
          Swal.fire({ icon:'error', title:'Error', text: (j && (j.message||j.error)) || 'Failed to remove' });
        }
      }catch(err){
        // Reopen modal on error
        dayModal.classList.remove('hidden');
        // revert on error
        if(removed){ schedules.push(removed); const k2 = (removed.scheduled_at||'').slice(0,10); schedulesByDate[k2] = schedulesByDate[k2] || []; schedulesByDate[k2].push(removed); openDay(k2); renderApplicants(); renderCalendar(currentMonth,currentYear); }
        console.error(err);
        Swal.fire({ icon:'error', title:'Server error' });
      }
    }));
  }, 10);
  dayModal.classList.remove('hidden');
}
addListenerSafe('closeDayModal','click', ()=> dayModal.classList.add('hidden'));
addListenerSafe('closeDayModal2','click', ()=> dayModal.classList.add('hidden'));
if(dayModal) dayModal.addEventListener('click', (e)=>{ if(e.target===dayModal) dayModal.classList.add('hidden'); });

// View PDF within day modal: expand modal and show preview
if(viewDayPdfBtn){
  viewDayPdfBtn.addEventListener('click', ()=>{
    // toggle preview
    const isHidden = dayModalPdfPreview.classList.contains('hidden');
    if(isHidden){
      // render preview from current day view
      const key = (dayModalTitle.textContent || '').match(/\d{4}-\d{2}-\d{2}/);
      const dtKey = key ? key[0] : null;
      if(!dtKey) return Swal.fire({ icon:'info', title:'No date' });
      renderDayPdfPreview(dtKey);
      dayModalPdfPreview.classList.remove('hidden');
      if(downloadDayPdfBtn) downloadDayPdfBtn.classList.remove('hidden');
      // expand modal to the right
      if(dayModalInner){ dayModalInner.style.maxWidth = '1100px'; }
      if(dayModalInnerContent){ dayModalInnerContent.classList.add('grid','grid-cols-2','gap-4'); }
      viewDayPdfBtn.textContent = 'Close PDF';
    } else {
      dayModalPdfPreview.classList.add('hidden');
      if(downloadDayPdfBtn) downloadDayPdfBtn.classList.add('hidden');
      if(dayModalInner){ dayModalInner.style.maxWidth = ''; }
      if(dayModalInnerContent){ dayModalInnerContent.classList.remove('grid','grid-cols-2','gap-4'); }
      viewDayPdfBtn.textContent = 'View PDF';
    }
  });
}

function renderDayPdfPreview(dtKey){
  const items = schedulesByDate[dtKey] || [];
  if(!dayModalPdfPreview) return;
  if(!items.length){ dayModalPdfPreview.innerHTML = '<div class="p-4 text-center text-sm text-neutral-500">No applicants to show</div>'; return; }
  const rows = items.map(i=>{
    // attempt to find matching applicant for email/phone
    const app = applicants.find(a => (a.id||'').toString() === (i.applicant_id||'').toString()) || {};
    const name = i.applicant_name || app.name || '';
    const position = i.position || app.position || '';
    const email = app.email || '';
    const phone = app.phone || '';
    return `<tr><td style="padding:8px;border:1px solid #ddd">${escapeHtml(name)}</td><td style="padding:8px;border:1px solid #ddd">${escapeHtml(position)}</td><td style="padding:8px;border:1px solid #ddd">${escapeHtml(email)}</td><td style="padding:8px;border:1px solid #ddd">${escapeHtml(phone)}</td><td style="padding:8px;border:1px solid #ddd">${formatScheduleDateTime(i.scheduled_at)}</td></tr>`;
  }).join('');
  const html = `<div id="dayPdfExportArea"><table style="width:100%;border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;"><thead><tr><th style="text-align:left;padding:8px;border:1px solid #ddd">Name</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Position</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Email</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Phone</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Scheduled At</th></tr></thead><tbody>${rows}</tbody></table></div>`;
  dayModalPdfPreview.innerHTML = html;
}

if(downloadDayPdfBtn){
  downloadDayPdfBtn.addEventListener('click', ()=>{
    // target the generated export table inside the preview
    const exportEl = document.getElementById('dayPdfExportArea');
    if(!exportEl) return Swal.fire({ icon:'info', title:'Nothing to export' });
    const filename = `schedules-${(dayModalTitle.textContent||'').replace(/[^0-9-]/g,'') || new Date().toISOString().slice(0,10)}.pdf`;
    const opt = { 
      margin: 8, 
      filename: filename, 
      image: { type: 'jpeg', quality: 0.95 }, 
      html2canvas: { scale: 1, backgroundColor: '#ffffff', useCORS: true, allowTaint: true }, 
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape', compress: true } 
    };
    // ensure html2pdf is loaded before exporting. Try primary CDN then fallback to alternatives.
    const html2pdfCandidates = [
      'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js',
      'https://unpkg.com/html2pdf.js/dist/html2pdf.bundle.min.js',
      'https://cdn.jsdelivr.net/npm/html2pdf.js/dist/html2pdf.bundle.min.js'
    ];
    function loadScript(src){
      return new Promise((resolve,reject)=>{
        const existing = document.querySelector(`script[data-html2pdf-src="${src}"]`);
        if(existing){ existing.addEventListener('load', ()=> resolve()); existing.addEventListener('error', ()=> reject(new Error('load error'))); return; }
        const s = document.createElement('script'); s.src = src; s.async = true; s.setAttribute('data-html2pdf-src', src);
        s.onload = ()=> resolve();
        s.onerror = (e)=> reject(new Error('Failed loading '+src+': '+(e && e.message)));
        document.head.appendChild(s);
      });
    }
    function ensureHtml2Pdf(){
      return new Promise(async (resolve,reject)=>{
        if(window.html2pdf) return resolve();
        for(const src of html2pdfCandidates){
          try{ await loadScript(src); if(window.html2pdf) return resolve(); }catch(e){ console.warn('html2pdf load failed for', src, e); }
        }
        return reject(new Error('All html2pdf CDN loads failed'));
      });
    }

    ensureHtml2Pdf().then(()=>{
      setTimeout(()=>{
        try{
          console.log('Attempting html2pdf for day export', exportEl);
          html2pdf().set(opt).from(exportEl).toPdf().output('blob').then(function(blob){
            try{
              const url = URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.href = url;
              a.download = filename;
              document.body.appendChild(a);
              a.click();
              a.remove();
              URL.revokeObjectURL(url);
            }catch(e2){ console.error('download link error', e2); Swal.fire({ icon:'error', title:'Download failed', text: (e2 && (e2.message || JSON.stringify(e2))) || 'See console for details' }); }
          }).catch(function(err){ console.error('html2pdf toPdf error (day)', err); Swal.fire({ icon:'error', title:'Export failed', text: (err && (err.message || JSON.stringify(err))) || 'See console for details' }); });
        }catch(e){
          console.error('html2pdf error (day direct):', e);
          Swal.fire({ icon:'error', title:'Export failed', text: (e && (e.message || JSON.stringify(e))) || 'See console for details' });
        }
      }, 250);
    }).catch(err=>{
      console.error('html2pdf load error (day):', err);
      Swal.fire({ icon:'error', title:'Export failed', text: 'The PDF library failed to load. Check network or try again.' });
    });
  });
}

// schedule modal
const scheduleModal = document.getElementById('scheduleModal');
function openScheduleForApplicant(e){
  const btn = e.currentTarget;
  document.getElementById('sch_applicant_id').value = btn.dataset.id || '';
  document.getElementById('sch_applicant_name').textContent = btn.dataset.name || '';
  document.getElementById('sch_position').value = btn.dataset.position || '';
  document.getElementById('sch_datetime').value = '';
  scheduleModal.classList.remove('hidden');
}
addListenerSafe('closeScheduleModal','click', ()=> scheduleModal.classList.add('hidden'));
addListenerSafe('cancelSchedule','click', ()=> scheduleModal.classList.add('hidden'));
if(scheduleModal) scheduleModal.addEventListener('click', (e)=>{ if(e.target===scheduleModal) scheduleModal.classList.add('hidden'); });

const scheduleFormEl = document.getElementById('scheduleForm');
if(scheduleFormEl) scheduleFormEl.addEventListener('submit', async (ev)=>{
  ev.preventDefault();
  const id = document.getElementById('sch_applicant_id').value || null;
  const name = document.getElementById('sch_applicant_name').textContent || '';
  const position = document.getElementById('sch_position').value || '';
  const dt = document.getElementById('sch_datetime').value;
  if(!dt) return Swal.fire({ icon:'info', title:'Please choose date & time' });
  // Optimistic UI: add schedule locally immediately, close modal and update UI
  const tmpId = 'tmp_' + Date.now();
  const schedObj = { id: tmpId, applicant_id: id, applicant_name: name, position: position, scheduled_at: dt };
  // add to local state
  schedules.push(schedObj);
  const key = dt.slice(0,10);
  schedulesByDate[key] = schedulesByDate[key] || [];
  schedulesByDate[key].push(schedObj);
  renderApplicants();
  renderCalendar(currentMonth,currentYear);
  scheduleModal.classList.add('hidden');
  // show quick success toast while request runs
  Swal.fire({ icon:'success', title:'Scheduled', timer:900, showConfirmButton:false });

  // send request in background and reconcile
  try{
    const res = await fetch('../../backend/schedule_api.php', { method: 'POST', headers: { 'Content-Type':'application/json' }, body: JSON.stringify({ applicant_id: id, applicant_name: name, position: position, scheduled_at: dt }) });
    const json = await res.json();
    if(json && json.success){
      // replace tmp id with real id
      const realId = json.id;
      schedules = schedules.map(s=> s.id === tmpId ? Object.assign({}, s, { id: realId }) : s );
      if(schedulesByDate[key]) schedulesByDate[key] = schedulesByDate[key].map(s=> s.id === tmpId ? Object.assign({}, s, { id: realId }) : s );
      // no further UI change needed
    } else {
      // revert optimistic add
      schedules = schedules.filter(s=> s.id !== tmpId);
      if(schedulesByDate[key]) schedulesByDate[key] = schedulesByDate[key].filter(s=> s.id !== tmpId);
      renderApplicants(); renderCalendar(currentMonth,currentYear);
      Swal.fire({ icon:'error', title:'Error', text: (json && (json.message||json.error)) || 'Failed to schedule' });
    }
  }catch(err){
    // revert optimistic add
    schedules = schedules.filter(s=> s.id !== tmpId);
    if(schedulesByDate[key]) schedulesByDate[key] = schedulesByDate[key].filter(s=> s.id !== tmpId);
    renderApplicants(); renderCalendar(currentMonth,currentYear);
    console.error(err);
    Swal.fire({ icon:'error', title:'Server error' });
  }
});

async function confirmRemoveSchedule(e){
  const btn = e.currentTarget;
  const applicantId = btn.dataset.applicantId;
  const sched = schedules.find(s => (s.applicant_id||'').toString() === (applicantId||'').toString());
  if(!sched) return Swal.fire({ icon:'error', title:'Not found' });
  const r = await Swal.fire({ title:'Remove schedule?', text:'This will remove the interview schedule and re-enable scheduling for this applicant.', icon:'warning', showCancelButton:true, confirmButtonText:'Remove' });
  if(!r.isConfirmed) return;
  try{
    const loading = Swal.fire({ title:'Removing...', allowOutsideClick:false, didOpen: ()=> Swal.showLoading() });
    const result = await deleteScheduleById(sched.id);
    Swal.close();
    const j = result.json;
    if(j && j.success){
      // refresh schedules and applicants
      await loadSchedules();
      await Swal.fire({ icon:'success', title:'Removed', timer:1200, showConfirmButton:false });
    } else {
      Swal.fire({ icon:'error', title:'Error', text: (j && (j.message||j.error)) || 'Failed to remove' });
    }
  }catch(err){ Swal.close(); console.error(err); Swal.fire({ icon:'error', title:'Server error' }); }
}

// helper: delete schedule via POST with _method override (more reliable than DELETE)
async function deleteScheduleById(schedId){
  const res = await fetch('../../backend/schedule_api.php', { 
    method: 'POST', 
    headers: { 'Content-Type':'application/json' }, 
    body: JSON.stringify({ _method: 'DELETE', id: schedId }) 
  });
  const json = await res.json();
  return { res, json };
}

// initial load
loadApplicants();
loadSchedules();
renderCalendar(currentMonth,currentYear);

// Auto-refresh schedules every 1 second to detect new interviews
let autoRefreshSchedulesInterval = null;
function startAutoRefreshSchedules(){
  if(autoRefreshSchedulesInterval) clearInterval(autoRefreshSchedulesInterval);
  autoRefreshSchedulesInterval = setInterval(async ()=>{
    try{
      await loadApplicants();
      await loadSchedules();
    }catch(e){
      console.error('Auto-refresh schedules error', e);
    }
  }, 1000);
}
startAutoRefreshSchedules();

// Stop auto-refresh when leaving page
window.addEventListener('beforeunload', ()=>{
  if(autoRefreshSchedulesInterval) clearInterval(autoRefreshSchedulesInterval);
});

// PDF modal handlers
const pdfModal = document.getElementById('pdfModal');
const pdfModalContent = document.getElementById('pdfModalContent');
addListenerSafe('exportPdfBtn','click', ()=>{ renderPdfModalContent(); if(pdfModal) pdfModal.classList.remove('hidden'); });
addListenerSafe('closePdfModal','click', ()=>{ if(pdfModal) pdfModal.classList.add('hidden'); });
addListenerSafe('closePdfModal2','click', ()=>{ if(pdfModal) pdfModal.classList.add('hidden'); });
if(pdfModal) pdfModal.addEventListener('click', (e)=>{ if(e.target === pdfModal) pdfModal.classList.add('hidden'); });

function renderPdfModalContent(){
  if(!pdfModalContent) return;
  if(!applicants || !applicants.length){ pdfModalContent.innerHTML = '<div class="p-4 text-center text-sm text-neutral-500">No applicants to show</div>'; return; }
  const rows = applicants.map(a=>`<tr><td style="padding:8px;border:1px solid #ddd">${escapeHtml(a.name)}</td><td style="padding:8px;border:1px solid #ddd">${escapeHtml(a.position)}</td><td style="padding:8px;border:1px solid #ddd">${escapeHtml(a.email)}</td><td style="padding:8px;border:1px solid #ddd">${escapeHtml(a.phone)}</td></tr>`).join('');
  const html = `<div id="pdfExportArea"><table style="width:100%;border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;"><thead><tr><th style="text-align:left;padding:8px;border:1px solid #ddd">Name</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Position</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Email</th><th style="text-align:left;padding:8px;border:1px solid #ddd">Phone</th></tr></thead><tbody>${rows}</tbody></table></div>`;
  pdfModalContent.innerHTML = html;
  }

  addListenerSafe('downloadPdfBtn','click', ()=>{
  const exportEl = document.getElementById('pdfExportArea');
  if(!exportEl) return Swal.fire({ icon:'info', title:'Nothing to export' });
  const filename = `applicants-for-interview-${new Date().toISOString().slice(0,10)}.pdf`;
  const opt = { margin:10, filename: filename, image:{ type:'jpeg', quality:0.98 }, html2canvas: { scale:2 }, jsPDF:{ unit:'mm', format:'a4', orientation:'portrait' } };
    // ensure html2pdf is loaded before exporting. Try primary CDN then fallback CDN.
    const html2pdfCandidates = [
      'https://cdn.jsdelivr.net/npm/html2pdf.js@0.9.2/dist/html2pdf.bundle.min.js',
      'https://unpkg.com/html2pdf.js@0.9.2/dist/html2pdf.bundle.min.js',
      // local fallback: place html2pdf.bundle.min.js at frontend/assets/vendor/
      '../assets/vendor/html2pdf.bundle.min.js'
    ];
    function loadScript(src){
      return new Promise((resolve,reject)=>{
        // avoid duplicate loaders for same src
        const existing = document.querySelector(`script[data-html2pdf-src="${src}"]`);
        if(existing){
          existing.addEventListener('load', ()=> resolve());
          existing.addEventListener('error', ()=> reject(new Error('load error')));
          return;
        }
        const s = document.createElement('script'); s.src = src; s.async = true; s.setAttribute('data-html2pdf-src', src);
        s.onload = ()=> resolve();
        s.onerror = (e)=> reject(new Error('Failed loading '+src+': '+(e && e.message)));
        document.head.appendChild(s);
      });
    }

    function ensureHtml2Pdf(){
      return new Promise(async (resolve,reject)=>{
        if(window.html2pdf) return resolve();
        for(const src of html2pdfCandidates){
          try{
            await loadScript(src);
            if(window.html2pdf) return resolve();
          }catch(e){
            console.warn('html2pdf load failed for', src, e);
            // try next
          }
        }
        return reject(new Error('All html2pdf CDN loads failed'));
      });
    }

    ensureHtml2Pdf().then(()=>{
    setTimeout(()=>{
      try{
        console.log('Attempting html2pdf for list export', exportEl);
        html2pdf().set(opt).from(exportEl).toPdf().output('blob').then(function(blob){
          try{
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
          }catch(e2){ console.error('download link error (list)', e2); Swal.fire({ icon:'error', title:'Download failed', text: (e2 && (e2.message || JSON.stringify(e2))) || 'See console for details' }); }
        }).catch(function(err){ console.error('html2pdf toPdf error (list)', err); Swal.fire({ icon:'error', title:'Export failed', text: (err && (err.message || JSON.stringify(err))) || 'See console for details' }); });
      }catch(e){
        console.error('html2pdf error (list direct):', e);
        Swal.fire({ icon:'error', title:'Export failed', text: (e && (e.message || JSON.stringify(e))) || 'See console for details' });
      }
    }, 250);
    }).catch(err=>{
      console.error('html2pdf load error (list):', err);
      Swal.fire({ icon:'error', title:'Export failed', html: 'The PDF library failed to load from CDNs.<br/>You can download <a href="https://github.com/eKoopmans/html2pdf.js/releases" target="_blank">html2pdf.bundle.min.js</a> and save it to <code>frontend/assets/vendor/html2pdf.bundle.min.js</code>, then retry.' });
    });
});
</script>

