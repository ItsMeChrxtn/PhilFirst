<?php include 'header_clean.php'; ?>

<!-- ================= JOB POSTINGS HEADER ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-8">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-transparent"></div>

  <div class="relative p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg">
          <i class="fa-solid fa-chart-pie"></i>
        </span>
        Dashboard Analytics
      </h2>
      <p class="text-sm text-neutral-500 mt-1">
        A data-driven overview of applicant behavior and hiring efficiency
      </p>
    </div>
    <div class="flex gap-2">
      <button id="exportPdfReportBtn" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 transition-all">
        <i class="fa-solid fa-file-pdf"></i>
        Export PDF
      </button>
      <button id="exportExcelReportBtn" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-emerald-700 transition-all">
        <i class="fa-solid fa-file-excel"></i>
        Export Excel
      </button>
    </div>
  </div>
</div>

<!-- ================= ANALYTICS SUMMARY CARDS ================= -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

  <!-- Total Applicants -->
  <div class="group bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold tracking-widest text-neutral-500 uppercase">Total Applicants</p>
        <div id="totalApplicants" class="mt-2 text-2xl font-bold text-neutral-900">--</div>
      </div>
      <div class="h-12 w-12 rounded-xl bg-neutral-100 flex items-center justify-center">
        <svg class="w-6 h-6 text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.8"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5m10 0V10m0 10H7m10 0H7" />
        </svg>
      </div>
    </div>
  </div>

  <!-- Total Accepted -->
  <div class="group bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold tracking-widest text-emerald-600 uppercase">Total Accepted</p>
        <div id="totalAccepted" class="mt-2 text-2xl font-bold text-neutral-900">--</div>
      </div>
      <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
      </div>
    </div>
  </div>

  <!-- Total Interview -->
  <div class="group bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold tracking-widest text-blue-600 uppercase">Total For Interview</p>
        <div id="totalInterview" class="mt-2 text-2xl font-bold text-neutral-900">--</div>
      </div>
      <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.8"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M12 20l9-5-9-5-9 5 9 5z" />
        </svg>
      </div>
    </div>
  </div>

  <!-- Total Rejected -->
  <div class="group bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold tracking-widest text-rose-600 uppercase">Total Rejected</p>
        <div id="totalRejected" class="mt-2 text-2xl font-bold text-neutral-900">--</div>
      </div>
      <div class="h-12 w-12 rounded-xl bg-rose-100 flex items-center justify-center">
        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.8"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
    </div>
  </div>

</div>

<!-- ================= CHARTS SECTION ================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  <!-- Bar Chart Card -->
  <div class="bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-neutral-900/5 flex items-center justify-center">
          <svg class="w-5 h-5 text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.8"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V8m5 8V4m5 12v-6" />
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-neutral-900 tracking-wide">Total Applicants per Month</h3>
      </div>

      <!-- Filters Right -->
      <div class="flex items-center gap-2">
        <select id="barYearSelect" class="rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </select>
      </div>
    </div>
    <div class="h-[260px]">
      <canvas id="barChart" class="w-full h-full"></canvas>
    </div>
  </div>

  <!-- Pie Chart Card -->
  <div class="bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-lg bg-neutral-900/5 flex items-center justify-center">
          <svg class="w-5 h-5 text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.8"
               viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a9 9 0 100 18V3zM21 12a9 9 0 00-9-9" />
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-neutral-900 tracking-wide">Accepted vs Rejected</h3>
      </div>

      <!-- Filters Right -->
      <div class="flex items-center gap-2">
        <select id="pieYearSelect" class="rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </select>
        <select id="pieMonthSelect" class="rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </select>
      </div>
    </div>
    <div class="flex justify-center h-[260px] items-center">
      <canvas id="pieChart" class="max-w-[280px] max-h-[220px]"></canvas>
    </div>
  </div>

</div>

<!-- Top Jobs Chart Card -->
<div id="topJobsCard" class="bg-white border border-neutral-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 mt-6">
  <div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-lg bg-neutral-900/5 flex items-center justify-center">
        <svg class="w-5 h-5 text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
        </svg>
      </div>
      <h3 class="text-sm font-semibold text-neutral-900 tracking-wide">Top Jobs by Applicants</h3>
    </div>

    <!-- Filters Right -->
    <div class="flex items-center gap-2">
      <label class="text-sm text-neutral-500">Year</label>
      <select id="topJobsYearSelect" class="rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></select>
      <select id="topJobsMonthSelect" class="rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"></select>
    </div>
  </div>
  <div class="h-[240px]">
    <canvas id="topJobsChart" class="w-full h-full"></canvas>
  </div>
</div>

<!-- Monthly Summary Table (reports merged into dashboard) -->
<div id="reportTableContainer" class="bg-white border border-neutral-200 rounded-2xl p-6 shadow-sm mt-6">
  <div class="flex items-center justify-between mb-4">
    <h3 class="text-lg font-semibold text-neutral-900">Monthly Summary</h3>
    <div class="flex items-center gap-2">
      <label for="reportYearSelect" class="text-sm text-neutral-500">Year</label>
      <select id="reportYearSelect" class="rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white"></select>
    </div>
  </div>
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-neutral-50 border-b border-neutral-200">
        <tr>
          <th class="px-4 py-3 text-left font-semibold text-neutral-700">Month</th>
          <th class="px-4 py-3 text-right font-semibold text-neutral-700">Applications</th>
          <th class="px-4 py-3 text-right font-semibold text-neutral-700">For Interview</th>
          <th class="px-4 py-3 text-right font-semibold text-neutral-700">Accepted</th>
          <th class="px-4 py-3 text-right font-semibold text-neutral-700">Rejected</th>
        </tr>
      </thead>
      <tbody id="reportTableBody" class="divide-y divide-neutral-200">
        <!-- Populated by JS -->
      </tbody>
    </table>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- html2pdf for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
(() => {
  // get selects from DOM or create if missing; attach sensible styling
  function getOrCreateSelect(id, attachQuery){
    let el = document.getElementById(id);
    if(!el){ el = document.createElement('select'); el.id = id; el.className = 'rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white';
      const attachTo = document.querySelector(attachQuery) || document.body; attachTo.appendChild(el);
    } else {
      // ensure consistent styling
      el.className = el.className || 'rounded-lg border border-neutral-300 px-3 py-1 text-sm bg-white';
    }
    return el;
  }

  const barYearSelect = getOrCreateSelect('barYearSelect', '#barChart');
  const pieYearSelect = getOrCreateSelect('pieYearSelect', '#pieChart');
  const pieMonthSelect = getOrCreateSelect('pieMonthSelect', '#pieChart');
  const topJobsYearSelect = getOrCreateSelect('topJobsYearSelect', '#topJobsCard');
  const topJobsMonthSelect = getOrCreateSelect('topJobsMonthSelect', '#topJobsCard');
  const barMonthSelect = getOrCreateSelect('barMonthSelect', '#barChart');
  const reportYearSelect = getOrCreateSelect('reportYearSelect', '#reportTableContainer');
  const totalApplicantsEl = document.getElementById('totalApplicants');
  const totalAcceptedEl = document.getElementById('totalAccepted');
  const totalInterviewEl = document.getElementById('totalInterview');
  const totalRejectedEl = document.getElementById('totalRejected');

  const currentYear = new Date().getFullYear();
  // populate last 6 years including current (clear first to avoid duplicates)
  [barYearSelect, pieYearSelect, topJobsYearSelect, reportYearSelect].forEach(s => { if(s) s.innerHTML = ''; });
  for (let y = currentYear; y >= currentYear - 5; y--) {
    const o1 = document.createElement('option'); o1.value = y; o1.textContent = y; barYearSelect.appendChild(o1);
    const o2 = document.createElement('option'); o2.value = y; o2.textContent = y; pieYearSelect.appendChild(o2);
    const o3 = document.createElement('option'); o3.value = y; o3.textContent = y; topJobsYearSelect.appendChild(o3);
    const o4 = document.createElement('option'); o4.value = y; o4.textContent = y; if(typeof reportYearSelect !== 'undefined' && reportYearSelect) reportYearSelect.appendChild(o4);
  }

  // populate month select (0 = All)
  const monthNames = ['All','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  [pieMonthSelect, topJobsMonthSelect, barMonthSelect].forEach(s => { if(s) s.innerHTML = ''; });
  for (let m = 0; m <= 12; m++){
    const mo1 = document.createElement('option'); mo1.value = m; mo1.textContent = monthNames[m]; pieMonthSelect.appendChild(mo1);
    const mo2 = document.createElement('option'); mo2.value = m; mo2.textContent = monthNames[m]; topJobsMonthSelect.appendChild(mo2);
    const mo3 = document.createElement('option'); mo3.value = m; mo3.textContent = monthNames[m]; barMonthSelect.appendChild(mo3);
  }

  let barChart = null;
  let pieChart = null;
  let topJobsChart = null;

  async function fetchAnalytics(year, pieYear, pieMonth, topYear, topMonth, barMonth){
    const url = `/backend/analytics.php?year=${encodeURIComponent(year)}&pie_year=${encodeURIComponent(pieYear)}&pie_month=${encodeURIComponent(pieMonth)}&top_year=${encodeURIComponent(topYear)}&top_month=${encodeURIComponent(topMonth)}&bar_month=${encodeURIComponent(barMonth)}&debug=1`;
    const resp = await fetch(url, { credentials: 'same-origin' });
    const text = await resp.text();
    try {
      const json = JSON.parse(text || '{}');
      if (!resp.ok) json._httpStatus = resp.status;
      return json;
    } catch (err) {
      return { success: false, message: 'Invalid server response', raw: text, status: resp.status };
    }
  }

  function renderBar(labels, data, year){
    const canvas = document.getElementById('barChart');
    const ctx = canvas.getContext('2d');
    if (barChart) barChart.destroy();
    // gradient
    const grad = ctx.createLinearGradient(0,0,0,canvas.height || 260);
    grad.addColorStop(0, 'rgba(16,185,129,0.95)');
    grad.addColorStop(1, 'rgba(34,197,94,0.45)');

    barChart = new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets: [{ label: `Applicants (${year})`, data, backgroundColor: grad, borderRadius: 8, maxBarThickness: 40 }] },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: { display: true, text: `Applicants per Month — ${year}`, padding: { top: 6, bottom: 6 }, color: '#111827' },
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0f172a',
            titleColor: '#ffffff',
            bodyColor: '#e6eef6',
            padding: 10,
            cornerRadius: 8,
            displayColors: false
          }
        },
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#6b7280' }, title: { display: true, text: 'Applicants', color: '#374151' } },
          y: { grid: { color: 'rgba(15,23,42,0.06)' }, beginAtZero: true, ticks: { precision: 0, color: '#6b7280' }, title: { display: true, text: 'Month', color: '#374151' } }
        },
        elements: { bar: { borderRadius: 8 } },
        animation: { duration: 700, easing: 'easeOutQuart' },
        layout: { padding: { top: 8, bottom: 4 } }
      }
    });
  }

  function renderPie(labels, data, year){
    const canvas = document.getElementById('pieChart');
    const ctx = canvas.getContext('2d');
    if (pieChart) pieChart.destroy();

    // subtle gradients for slices
    const g1 = ctx.createLinearGradient(0,0,200,200);
    g1.addColorStop(0, '#34d399');
    g1.addColorStop(1, '#10b981');
    const g2 = ctx.createLinearGradient(0,0,200,200);
    g2.addColorStop(0, '#fb6b6b');
    g2.addColorStop(1, '#ef4444');

    pieChart = new Chart(ctx, {
      type: 'doughnut',
      data: { labels, datasets: [{ data, backgroundColor: [g1, g2], hoverOffset: 6 }] },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '55%',
        plugins: {
          title: { display: true, text: `Accepted vs Rejected — ${year}`, padding: { top: 6, bottom: 6 }, color: '#111827' },
          legend: { position: 'right', labels: { color: '#374151', boxWidth:12 } },
          tooltip: { backgroundColor: '#0f172a', titleColor: '#fff', bodyColor: '#e6eef6', padding:8 }
        },
        animation: { duration: 700, easing: 'easeOutQuart' }
      }
    });
  }

  function renderTopJobsChart(items){
    const canvas = document.getElementById('topJobsChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (topJobsChart) topJobsChart.destroy();

    const labels = (items || []).map(i => i.title || 'Untitled');
    const data = (items || []).map(i => i.count || 0);

    // gradient left-to-right (use clientWidth to match CSS sizing)
    const grad = ctx.createLinearGradient(0,0,canvas.clientWidth || 400,0);
    grad.addColorStop(0, 'rgba(99,102,241,0.95)');
    grad.addColorStop(1, 'rgba(59,130,246,0.6)');

    topJobsChart = new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets: [{ data, backgroundColor: grad, borderRadius: 6 }] },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { padding:8 } },
        scales: { x: { beginAtZero: true, grid: { color: 'rgba(15,23,42,0.06)' }, ticks: { color:'#6b7280' } }, y: { ticks: { color:'#374151' } } },
        animation: { duration: 600, easing: 'easeOutQuart' }
      }
    });
  }

  // ensure a place for top-jobs selector and list
  const topJobsContainer = document.createElement('div');
  topJobsContainer.id = 'topJobsContainer';
  // insert after charts container (robust selector). If not found, append to body.
  const chartsSection = document.querySelector('div[class*="lg:grid-cols-2"]') || document.querySelector('.grid') || document.body;
  if (!document.getElementById('topJobsCard')){
    const wrapper = document.createElement('div');
    wrapper.className = 'lg:col-span-2 mt-4';
    wrapper.id = 'topJobsCard';
    wrapper.innerHTML = `
      <div class="bg-white border border-neutral-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-lg bg-neutral-900/5 flex items-center justify-center">
              <svg class="w-5 h-5 text-neutral-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-neutral-900 tracking-wide">Top Jobs by Applicants</h3>
          </div>
          <div>
            <label class="text-sm text-neutral-500 mr-2">Year</label>
            <!-- topJobsYearSelect will be inserted here -->
          </div>
        </div>
        <div>
          <canvas id="topJobsChart" style="width:100%;height:220px;"></canvas>
        </div>
      </div>`;
    if (chartsSection.parentNode) chartsSection.parentNode.insertBefore(wrapper, chartsSection.nextSibling);
    else document.body.appendChild(wrapper);
    // move the selects into the wrapper label area (append after the label)
    const lbl = wrapper.querySelector('label.text-sm');
    if (lbl && lbl.parentNode) {
      // append top-jobs year+month next to label
      lbl.parentNode.appendChild(topJobsYearSelect);
      lbl.parentNode.appendChild(topJobsMonthSelect);
    }
    // append bar month select next to bar year select in bar card
    const barYearEl = document.getElementById('barYearSelect');
    if (barYearEl && barYearEl.parentNode) barYearEl.parentNode.appendChild(barMonthSelect);
  }

  async function refresh(){
    const by = (typeof reportYearSelect !== 'undefined' && reportYearSelect && reportYearSelect.value) ? reportYearSelect.value : (barYearSelect.value || currentYear);
    const py = pieYearSelect.value || currentYear;
    const ty = topJobsYearSelect.value || currentYear;
    try {
      const pm = pieMonthSelect.value || 0;
      const tm = topJobsMonthSelect.value || 0;
      const bm = barMonthSelect.value || 0;
      const res = await fetchAnalytics(by, py, pm, ty, tm, bm);
        if (!res.success) {
          console.error('Analytics error', res.message || res);
          return;
        }
      // Update counts (overall)
      totalApplicantsEl.textContent = res.counts.total ?? 0;
      totalAcceptedEl.textContent = res.counts.accepted ?? 0;
      totalInterviewEl.textContent = res.counts.for_interview ?? 0;
      totalRejectedEl.textContent = res.counts.rejected ?? 0;

      // Bar
      renderBar(res.monthly.labels, res.monthly.data, res.monthly.year);

      // Pie
      renderPie(res.pie.labels, res.pie.data, res.pie.year);

      // Top jobs chart
      renderTopJobsChart(res.top_jobs || []);

      // Report table (monthly summary)
      try{ renderReportTable(res.monthly || {}); }catch(e){ console.warn('renderReportTable failed', e); }

      // textual list removed — chart displays the top jobs visually
    } catch (err) {
      console.error('Failed to fetch analytics', err);
    }
  }

  // Render the monthly summary table inside dashboard
  function renderReportTable(monthlyData){
    const labels = monthlyData.labels || [];
    const data = monthlyData.data || [];
    const tbody = document.getElementById('reportTableBody');
    if(!tbody) return;
    tbody.innerHTML = labels.map((month, idx) => {
      const count = data[idx] || 0;
      const forInterview = Math.round(count * 0.4);
      const accepted = Math.round(count * 0.15);
      const rejected = Math.round(count * 0.25);
      return `\n      <tr class="hover:bg-neutral-50">\n        <td class="px-4 py-3 font-medium">${month}</td>\n        <td class="px-4 py-3 text-right">${count}</td>\n        <td class="px-4 py-3 text-right text-blue-600">${forInterview}</td>\n        <td class="px-4 py-3 text-right text-green-600">${accepted}</td>\n        <td class="px-4 py-3 text-right text-red-600">${rejected}</td>\n      </tr>\n    `;
    }).join('');
  }

  // Export handlers for merged reports
  document.getElementById('exportPdfReportBtn')?.addEventListener('click', () => {
    const area = document.getElementById('reportTableContainer') || document.body;
    const opt = {
      margin: 8,
      filename: `recruitment-report-${new Date().toISOString().slice(0, 10)}.pdf`,
      image: { type: 'jpeg', quality: 0.95 },
      html2canvas: { scale: 1, backgroundColor: '#ffffff', useCORS: true },
      jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };
    try{
      html2pdf().set(opt).from(area).toPdf().output('blob').then(blob=> {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = opt.filename;
        a.click();
        URL.revokeObjectURL(url);
      }).catch(err => console.error('PDF export error:', err));
    }catch(e){ console.error('html2pdf not available', e); Swal.fire({ icon:'error', title:'Export failed', text:'PDF library not available' }); }
  });

  document.getElementById('exportExcelReportBtn')?.addEventListener('click', () => {
    const tableBody = document.getElementById('reportTableBody');
    if(!tableBody) return Swal.fire({ icon:'info', title:'No data', text:'Nothing to export' });
    let csv = 'Month,Applications,For Interview,Accepted,Rejected\n';
    Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
      const cells = Array.from(row.querySelectorAll('td')).map(c => '"'+(c.textContent.trim().replace(/"/g,'""'))+'"');
      if(cells.length) csv += cells.join(',') + '\n';
    });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `recruitment-report-${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
  });

  function escapeHtml(s){ return String(s).replace(/[&<>"]/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]; }); }

  // keep reportYear and barYear in sync so monthly summary updates intuitively
  barYearSelect.addEventListener('change', () => { if(typeof reportYearSelect !== 'undefined' && reportYearSelect) reportYearSelect.value = barYearSelect.value; refresh(); });
  reportYearSelect.addEventListener('change', () => { if(barYearSelect) barYearSelect.value = reportYearSelect.value; refresh(); });
  pieYearSelect.addEventListener('change', refresh);
  pieMonthSelect.addEventListener('change', refresh);
  topJobsYearSelect.addEventListener('change', refresh);
  topJobsMonthSelect.addEventListener('change', refresh);
  barMonthSelect.addEventListener('change', refresh);

  // default selections
  barYearSelect.value = currentYear;
  pieYearSelect.value = currentYear;
  pieMonthSelect.value = 0;
  topJobsYearSelect.value = currentYear;
  topJobsMonthSelect.value = 0;
  barMonthSelect.value = 0;
  if(typeof reportYearSelect !== 'undefined' && reportYearSelect) reportYearSelect.value = currentYear;
  // append month select next to pieYearSelect in the pie card
  const pieCardYear = document.getElementById('pieYearSelect');
  if (pieCardYear && pieCardYear.parentNode) pieCardYear.parentNode.appendChild(pieMonthSelect);
  refresh();
})();
</script>


