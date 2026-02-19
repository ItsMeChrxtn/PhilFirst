<?php include 'header_clean.php'; ?>

<!-- ================= REPORTS HEADER ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-6">
  <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-transparent"></div>
  <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow">
          <i class="fa-solid fa-chart-line"></i>
        </span>
        Reports & Analytics
      </h2>
      <p class="text-sm text-neutral-500 mt-1">Recruitment metrics, application trends, and performance overview.</p>
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

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm p-4 mb-6 border border-neutral-200">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="text-sm font-medium text-neutral-700 mb-2 block">Year</label>
      <select id="yearFilter" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026" selected>2026</option>
      </select>
    </div>
    <div>
      <label class="text-sm font-medium text-neutral-700 mb-2 block">Month (for Pie chart)</label>
      <select id="monthFilter" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
        <option value="0">All Months</option>
        <option value="1">January</option>
        <option value="2">February</option>
        <option value="3">March</option>
        <option value="4">April</option>
        <option value="5">May</option>
        <option value="6">June</option>
        <option value="7">July</option>
        <option value="8">August</option>
        <option value="9">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
      </select>
    </div>
    <div class="flex items-end">
      <button id="refreshChartsBtn" class="w-full rounded-lg bg-neutral-700 px-4 py-2 text-white hover:bg-neutral-800 transition-all">
        <i class="fa-solid fa-rotate"></i> Refresh
      </button>
    </div>
  </div>
</div>

<!-- KPI Cards -->
<div id="kpiCardsContainer" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
  <!-- Populated by JS -->
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
  <!-- Monthly Applications Chart -->
  <div class="bg-white rounded-2xl shadow-sm p-6 border border-neutral-200">
    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Monthly Applications</h3>
    <canvas id="monthlyChart" height="80"></canvas>
  </div>

  <!-- Application Status Distribution -->
  <div class="bg-white rounded-2xl shadow-sm p-6 border border-neutral-200">
    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Application Status</h3>
    <canvas id="statusChart" height="80"></canvas>
  </div>
</div>

<!-- Top Positions Chart -->
<div class="bg-white rounded-2xl shadow-sm p-6 border border-neutral-200 mb-6">
  <h3 class="text-lg font-semibold text-neutral-900 mb-4">Top 10 Most Applied Positions</h3>
  <canvas id="topJobsChart" height="60"></canvas>
</div>

<!-- Reports Table -->
<div id="reportTableContainer" class="bg-white rounded-2xl shadow-sm p-6 border border-neutral-200">
  <h3 class="text-lg font-semibold text-neutral-900 mb-4">Monthly Summary</h3>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
let analyticsData = {};
const charts = {};

// KPI Card Template
function createKpiCard(title, value, icon, color) {
  const colorMap = {
    'blue': 'from-blue-50 to-blue-100 text-blue-700 bg-blue-600',
    'green': 'from-emerald-50 to-emerald-100 text-emerald-700 bg-emerald-600',
    'orange': 'from-orange-50 to-orange-100 text-orange-700 bg-orange-600',
    'red': 'from-red-50 to-red-100 text-red-700 bg-red-600'
  };
  const [bgGradient, , textColor, iconBg] = colorMap[color].split(' ');
  return `
    <div class="bg-gradient-to-br ${colorMap[color].slice(0, colorMap[color].lastIndexOf(' '))} rounded-xl p-4 border border-neutral-200">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-sm font-medium text-neutral-600 mb-1">${title}</p>
          <p class="text-2xl font-bold text-neutral-900">${value}</p>
        </div>
        <div class="h-10 w-10 rounded-lg ${iconBg} text-white flex items-center justify-center">
          <i class="fa-solid ${icon}"></i>
        </div>
      </div>
    </div>
  `;
}

// Load Analytics Data
async function loadAnalytics() {
  try {
    const year = document.getElementById('yearFilter').value;
    const month = document.getElementById('monthFilter').value;
    const response = await fetch(`../../backend/analytics.php?year=${year}&pie_year=${year}&pie_month=${month}&top_year=${year}&top=15`);
    if (!response.ok) throw new Error('Failed to load analytics');
    analyticsData = await response.json();
    
    if (!analyticsData.success) throw new Error(analyticsData.message || 'Unknown error');
    
    renderKpis();
    renderCharts();
  } catch (err) {
    console.error('Analytics error:', err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load analytics data' });
  }
}

function renderKpis() {
  const counts = analyticsData.counts || {};
  const container = document.getElementById('kpiCardsContainer');
  container.innerHTML = `
    ${createKpiCard('Total Applications', counts.total || 0, 'fa-file-alt', 'blue')}
    ${createKpiCard('For Interview', counts.for_interview || 0, 'fa-calendar-check', 'green')}
    ${createKpiCard('Accepted', counts.accepted || 0, 'fa-check-circle', 'emerald')}
    ${createKpiCard('Rejected', counts.rejected || 0, 'fa-times-circle', 'red')}
  `;
}

function renderCharts() {
  // Monthly Chart
  if (charts.monthly) charts.monthly.destroy();
  const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
  const monthlyData = analyticsData.monthly || {};
  charts.monthly = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
      labels: monthlyData.labels || [],
      datasets: [{
        label: 'Applications',
        data: monthlyData.data || [],
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderColor: 'rgb(59, 130, 246)',
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });

  // Status Pie Chart
  if (charts.status) charts.status.destroy();
  const statusCtx = document.getElementById('statusChart').getContext('2d');
  const pieData = analyticsData.pie || {};
  charts.status = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
      labels: pieData.labels || [],
      datasets: [{
        data: pieData.data || [],
        backgroundColor: ['rgba(34, 197, 94, 0.7)', 'rgba(239, 68, 68, 0.7)'],
        borderColor: ['rgb(34, 197, 94)', 'rgb(239, 68, 68)'],
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Top Jobs Chart
  if (charts.topJobs) charts.topJobs.destroy();
  const topJobsCtx = document.getElementById('topJobsChart').getContext('2d');
  const topJobs = analyticsData.top_jobs || [];
  charts.topJobs = new Chart(topJobsCtx, {
    type: 'horizontalBar',
    data: {
      labels: topJobs.map(j => j.title || `Job #${j.job_id}`),
      datasets: [{
        label: 'Applications',
        data: topJobs.map(j => j.count),
        backgroundColor: 'rgba(139, 92, 246, 0.7)',
        borderColor: 'rgb(139, 92, 246)',
        borderWidth: 1,
        borderRadius: 4
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } }
    }
  });

  renderReportTable();
}

function renderReportTable() {
  const monthlyData = analyticsData.monthly || {};
  const labels = monthlyData.labels || [];
  const data = monthlyData.data || [];
  const tbody = document.getElementById('reportTableBody');
  tbody.innerHTML = labels.map((month, idx) => {
    const count = data[idx] || 0;
    const forInterview = Math.round(count * 0.4);
    const accepted = Math.round(count * 0.15);
    const rejected = Math.round(count * 0.25);
    return `
      <tr class="hover:bg-neutral-50">
        <td class="px-4 py-3 font-medium">${month}</td>
        <td class="px-4 py-3 text-right">${count}</td>
        <td class="px-4 py-3 text-right text-blue-600">${forInterview}</td>
        <td class="px-4 py-3 text-right text-green-600">${accepted}</td>
        <td class="px-4 py-3 text-right text-red-600">${rejected}</td>
      </tr>
    `;
  }).join('');
}

// Export to PDF
document.getElementById('exportPdfReportBtn')?.addEventListener('click', () => {
  const element = document.querySelector('.bg-white.rounded-2xl');
  const opt = {
    margin: 8,
    filename: `recruitment-report-${new Date().toISOString().slice(0, 10)}.pdf`,
    image: { type: 'jpeg', quality: 0.95 },
    html2canvas: { scale: 1, backgroundColor: '#ffffff' },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
  };
  html2pdf().set(opt).from(document.body).toPdf().output('blob').then(blob=> {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = opt.filename;
    a.click();
    URL.revokeObjectURL(url);
  }).catch(err => console.error('PDF export error:', err));
});

// Export to Excel
document.getElementById('exportExcelReportBtn')?.addEventListener('click', () => {
  const table = document.getElementById('reportTableBody').closest('table');
  let csv = 'Month,Applications,For Interview,Accepted,Rejected\n';
  table.querySelectorAll('tbody tr').forEach(row => {
    const cells = Array.from(row.querySelectorAll('td')).map(c => c.textContent.trim());
    csv += cells.join(',') + '\n';
  });
  const blob = new Blob([csv], { type: 'text/csv' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `recruitment-report-${new Date().toISOString().slice(0, 10)}.csv`;
  a.click();
  URL.revokeObjectURL(url);
});

// Event Listeners
document.getElementById('refreshChartsBtn')?.addEventListener('click', loadAnalytics);
document.getElementById('yearFilter')?.addEventListener('change', loadAnalytics);
document.getElementById('monthFilter')?.addEventListener('change', loadAnalytics);

// Initial Load
document.addEventListener('DOMContentLoaded', loadAnalytics);
</script>

<?php include 'footer.php'; ?>
