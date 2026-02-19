<?php include 'header_clean.php'; ?>

<!-- ================= JOB POSTINGS HEADER ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-8">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-transparent"></div>

  <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow">
          <i class="fa-solid fa-briefcase"></i>
        </span>
        Job Postings
      </h2>
      <p class="text-sm text-neutral-500 mt-1">
        Manage, create, and maintain all job listings
      </p>
    </div>

    <button
      data-modal-target="createJobModal"
      class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white shadow hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
      <i class="fa-solid fa-plus"></i>
      Create Job
    </button>
  </div>
</div>

<div class="rounded-2xl border border-neutral-200 bg-white shadow-sm mb-8">
  <div class="p-4 md:p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

    <!-- Status Pills -->
    <div id="statusPills" class="relative flex flex-wrap gap-2">
      <div id="statusIndicator" class="absolute rounded-full bg-emerald-600 transition-all duration-300" style="height:36px; width:0; left:0; top:0; z-index:0;"></div>
      <button class="adminStatusBtn selected relative z-10 px-4 py-2 rounded-full text-sm font-medium text-white transition" data-status="all">All</button>
      <button class="adminStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 hover:text-white transition" data-status="Active">Active</button>
      <button class="adminStatusBtn relative z-10 px-4 py-2 rounded-full text-sm font-medium text-neutral-700 hover:text-white transition" data-status="Inactive">Inactive</button>
    </div>

    <!-- Search -->
    <div class="flex w-full lg:w-auto gap-2">
      <div class="relative w-full lg:w-80">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm"></i>
        <input
          id="adminSearch"
          placeholder="Search title, client, location"
          class="w-full rounded-xl border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-200 focus:outline-none transition" />
      </div>

      <button id="adminSearchApply" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition">
        Apply
      </button>
      <button id="adminSearchClear" class="rounded-xl bg-neutral-100 px-4 py-2.5 text-sm hover:bg-neutral-200 transition">
        Clear
      </button>
    </div>
  </div>
</div>


<!-- ================= TABLE ================= -->
<div class="rounded-2xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
  <table class="min-w-full">
    <thead class="bg-neutral-50 border-b border-neutral-200">
      <tr>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Title</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Client</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Location</th>
        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-neutral-500">Status</th>
        <th class="px-6 py-4"></th>
      </tr>
    </thead>
    <tbody id="jobTableBody" class="divide-y divide-neutral-200"></tbody>
  </table>
</div>

<!-- ================= CREATE JOB MODAL ================= -->
<div id="createJobModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-4 p-6 md:p-8 transform scale-95 transition-all duration-200">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-2xl">
          <i class="fa-solid fa-plus"></i>
        </div>
        <div>
          <h3 class="text-2xl font-semibold text-gray-800">Create Job</h3>
          <p class="text-sm text-gray-500">Add a new job posting. Fill required fields and click Save.</p>
        </div>
      </div>
      <button data-close-modal class="text-gray-400 hover:text-gray-600 text-2xl p-2 rounded-md">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Form -->
    <form id="createJobForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <!-- Full-width fields -->
      <div class="md:col-span-2">
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-briefcase text-green-600"></i> Job Title
        </label>
        <input id="titleInput" name="title" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div class="md:col-span-2">
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-align-left text-green-600"></i> Job Description
        </label>
        <textarea id="jobDescriptionInput" name="job_description" rows="4" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none" placeholder="Describe the role, responsibilities, and expectations"></textarea>
      </div>

      <!-- Two-column fields -->
      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-clock text-green-600"></i> Job Type
        </label>
        <select id="jobTypeSelect" name="job_type" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
          <option>Full-time</option>
          <option>Part-time</option>
          <option>Contract</option>
        </select>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-building text-green-600"></i> Client
        </label>
        <input id="clientInput" name="client" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-money-bill-wave text-green-600"></i> Salary
        </label>
        <input id="salaryInput" name="salary" placeholder="₱25,000 / month" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-location-dot text-green-600"></i> Location
        </label>
        <input id="locationInput" name="location" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-star text-green-600"></i> Skills
        </label>
        <input id="skillsInput" placeholder="Type and press Enter" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
        <div id="skillsTags" class="mt-2 flex flex-wrap gap-2"></div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-graduation-cap text-green-600"></i> Qualifications
        </label>
        <input id="qualificationInput" placeholder="Type and press Enter" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
        <div id="qualificationTags" class="mt-2 flex flex-wrap gap-2"></div>
      </div>

      <!-- Two-column: Benefits & Status -->
      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-gift text-green-600"></i> Benefits
        </label>
        <input id="benefitsInput" placeholder="Type and press Enter" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
        <div id="benefitsTags" class="mt-2 flex flex-wrap gap-2"></div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-toggle-on text-green-600"></i> Status
        </label>
        <select id="statusSelect" name="status" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
          <option>Active</option>
          <option>Inactive</option>
        </select>
      </div>

    </form>

    <!-- Footer Buttons -->
    <div class="flex justify-end gap-3 mt-6">
      <button data-close-modal class="px-4 py-2 border rounded hover:bg-gray-100 flex items-center gap-2">
        <i class="fa-solid fa-times text-sm"></i> Cancel
      </button>
      <button id="saveCreateBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2">
        <i class="fa-solid fa-floppy-disk"></i> Save Job
      </button>
    </div>

  </div>
</div>


<!-- ================= EDIT JOB MODAL ================= -->
<div id="editJobModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-4 p-6 md:p-8 transform scale-95 transition-all duration-200">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-2xl">
          <i class="fa-solid fa-pen"></i>
        </div>
        <div>
          <h3 class="text-2xl font-semibold text-gray-800">Edit Job</h3>
          <p class="text-sm text-gray-500">Update the job details and click Save Changes.</p>
        </div>
      </div>
      <button data-close-modal class="text-gray-400 hover:text-gray-600 text-2xl p-2 rounded-md">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Form -->
    <form id="editJobForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input type="hidden" id="editJobId" name="id">

      <!-- Full-width fields -->
      <div class="md:col-span-2">
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-briefcase text-green-600"></i> Job Title
        </label>
        <input id="editTitle" name="title" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div class="md:col-span-2">
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-align-left text-green-600"></i> Job Description
        </label>
        <textarea id="editJobDescription" name="job_description" rows="4" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none" placeholder="Describe the role, responsibilities, and expectations"></textarea>
      </div>

      <!-- Two-column fields -->
      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-clock text-green-600"></i> Job Type
        </label>
        <select id="editJobType" name="job_type" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
          <option>Full-time</option>
          <option>Part-time</option>
          <option>Contract</option>
        </select>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-building text-green-600"></i> Client
        </label>
        <input id="editClient" name="client" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-money-bill-wave text-green-600"></i> Salary
        </label>
        <input id="editSalary" name="salary" placeholder="₱25,000 / month" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-location-dot text-green-600"></i> Location
        </label>
        <input id="editLocation" name="location" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-star text-green-600"></i> Skills
        </label>
        <input id="editSkillsInput" placeholder="Type and press Enter" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
        <div id="editSkillsTags" class="mt-2 flex flex-wrap gap-2"></div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-graduation-cap text-green-600"></i> Qualifications
        </label>
        <input id="editQualificationInput" placeholder="Type and press Enter" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
        <div id="editQualificationTags" class="mt-2 flex flex-wrap gap-2"></div>
      </div>

      <!-- Two-column at the bottom: Benefits & Status -->
      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-gift text-green-600"></i> Benefits
        </label>
        <input id="editBenefitsInput" placeholder="Type and press Enter" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
        <div id="editBenefitsTags" class="mt-2 flex flex-wrap gap-2"></div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
          <i class="fa-solid fa-toggle-on text-green-600"></i> Status
        </label>
        <select id="editStatus" name="status" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
          <option>Active</option>
          <option>Inactive</option>
        </select>
      </div>

    </form>

    <!-- Footer Buttons -->
    <div class="flex justify-end gap-3 mt-6">
      <button data-close-modal class="px-4 py-2 border rounded hover:bg-gray-100 flex items-center gap-2">
        <i class="fa-solid fa-times text-sm"></i> Cancel
      </button>
      <button id="saveEditBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2">
        <i class="fa-solid fa-floppy-disk"></i> Save Changes
      </button>
    </div>
  </div>
</div>


<!-- ================= VIEW JOB MODAL ================= -->
<div id="viewJobModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl mx-4 p-6 md:p-8 transform scale-95 transition-all duration-200">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 text-2xl">
          <i class="fa-solid fa-eye"></i>
        </div>
        <div>
          <h3 class="text-2xl font-semibold text-gray-800">View Job</h3>
          <p class="text-sm text-gray-500">All job details are displayed below.</p>
        </div>
      </div>
      <button data-close-modal class="text-gray-400 hover:text-gray-600 text-2xl p-2 rounded-md">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Job Title & Description -->
    <div class="mb-6">
      <div class="mb-3">
        <h4 class="flex items-center gap-2 text-lg font-semibold text-gray-800">
          <i class="fa-solid fa-briefcase text-green-600"></i> Job Title
        </h4>
        <p id="viewJobTitle" class="text-gray-700 text-base"></p>
      </div>
      <div>
        <h4 class="flex items-center gap-2 text-lg font-semibold text-gray-800">
          <i class="fa-solid fa-align-left text-green-600"></i> Job Description
        </h4>
        <p id="viewJobDescription" class="text-gray-700 text-base"></p>
      </div>
    </div>

    <!-- Job Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

      <!-- Job Type -->
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-clock text-green-600 w-5"></i>
        <div>
          <p class="text-gray-500 text-sm">Job Type</p>
          <p id="viewJobType" class="text-gray-800 font-medium"></p>
        </div>
      </div>

      <!-- Client -->
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-building text-green-600 w-5"></i>
        <div>
          <p class="text-gray-500 text-sm">Client</p>
          <p id="viewClient" class="text-gray-800 font-medium"></p>
        </div>
      </div>

      <!-- Salary -->
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-money-bill-wave text-green-600 w-5"></i>
        <div>
          <p class="text-gray-500 text-sm">Salary</p>
          <p id="viewSalary" class="text-gray-800 font-medium"></p>
        </div>
      </div>

      <!-- Location -->
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-location-dot text-green-600 w-5"></i>
        <div>
          <p class="text-gray-500 text-sm">Location</p>
          <p id="viewLocation" class="text-gray-800 font-medium"></p>
        </div>
      </div>

      <!-- Status -->
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-toggle-on text-green-600 w-5"></i>
        <div>
          <p class="text-gray-500 text-sm">Status</p>
          <p id="viewStatus" class="text-gray-800 font-medium"></p>
        </div>
      </div>

    </div>

    <!-- Skills, Qualifications, Benefits -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Skills -->
      <div>
        <h4 class="flex items-center gap-2 text-gray-800 font-semibold mb-2">
          <i class="fa-solid fa-star text-green-600"></i> Skills
        </h4>
        <div id="viewSkills" class="flex flex-wrap gap-2">
          <!-- Example: <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">JavaScript</span> -->
        </div>
      </div>

      <!-- Qualifications -->
      <div>
        <h4 class="flex items-center gap-2 text-gray-800 font-semibold mb-2">
          <i class="fa-solid fa-graduation-cap text-green-600"></i> Qualifications
        </h4>
        <div id="viewQualifications" class="flex flex-wrap gap-2">
          <!-- Example: <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Bachelor's Degree</span> -->
        </div>
      </div>

      <!-- Benefits -->
      <div>
        <h4 class="flex items-center gap-2 text-gray-800 font-semibold mb-2">
          <i class="fa-solid fa-gift text-green-600"></i> Benefits
        </h4>
        <div id="viewBenefits" class="flex flex-wrap gap-2">
          <!-- Example: <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Health Insurance</span> -->
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="flex justify-end mt-6">
      <button data-close-modal class="px-4 py-2 border rounded hover:bg-gray-100">Close</button>
    </div>
  </div>
</div>


<!-- pagination controls -->
<div class="mt-4" id="paginationControls"></div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Alerts helper -->
<script src="../assets/js/alerts.js"></script>

<!-- ================= SCRIPT ================= -->
<script>
  const apiUrl = '/backend/job_api.php';
  let jobsData = [];
  let currentPage = 1;
  const pageSize = 15;

  // Generic modal handlers
  document.querySelectorAll('[data-modal-target]').forEach(btn => {
    btn.addEventListener('click', () => {
      const modal = document.getElementById(btn.dataset.modalTarget);
      modal.classList.remove('hidden');
      // autofocus first input/select/textarea inside modal
      setTimeout(() => {
        const el = modal.querySelector('input:not([type=hidden]), select, textarea');
        if (el) el.focus();
      }, 50);
    });
  });
  document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.fixed').classList.add('hidden'));
  });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.fixed').forEach(m=>m.classList.add('hidden')); });
  document.querySelectorAll('.fixed').forEach(modal => modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); }));

  // Tag input helper
  function tagInput(inputEl, containerEl) {
    inputEl.addEventListener('keydown', e => {
      if (e.key === 'Enter' && inputEl.value.trim()) {
        e.preventDefault();
        const tag = document.createElement('span');
        tag.className = 'bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm flex items-center gap-2';
        tag.textContent = inputEl.value.trim();
        const btn = document.createElement('button'); btn.className = 'font-bold ml-2'; btn.innerHTML = '&times;'; btn.onclick = () => tag.remove();
        tag.appendChild(btn);
        containerEl.appendChild(tag);
        inputEl.value = '';
      }
    });
  }

  // Initialize tags for create
  tagInput(document.getElementById('qualificationInput'), document.getElementById('qualificationTags'));
  tagInput(document.getElementById('benefitsInput'), document.getElementById('benefitsTags'));
  tagInput(document.getElementById('skillsInput'), document.getElementById('skillsTags'));

  // Initialize tags for edit (delegated after elements exist)
  function initEditTagInputs() {
    tagInput(document.getElementById('editQualificationInput'), document.getElementById('editQualificationTags'));
    tagInput(document.getElementById('editBenefitsInput'), document.getElementById('editBenefitsTags'));
    tagInput(document.getElementById('editSkillsInput'), document.getElementById('editSkillsTags'));
  }

  // Collect tags from container as comma-separated
  function collectTags(container) {
    return Array.from(container.querySelectorAll('span')).map(s => s.childNodes[0].textContent.trim()).join(', ');
  }

  // Render list
  // Load all jobs (then paginate client-side)
  async function loadJobs() {
    const res = await fetch(apiUrl + '?action=list');
    const json = await res.json();
    if (!json.success) return notifyError('Failed to load jobs');
    jobsData = json.data || [];
    currentPage = 1;
    renderJobsPage(currentPage);
    renderPagination();
  }

  function renderJobsPage(page) {
    const tbody = document.getElementById('jobTableBody');
    tbody.innerHTML = '';
    // apply admin filters (search + status)
    const filtered = getFilteredJobs();
    const start = (page - 1) * pageSize;
    const slice = filtered.slice(start, start + pageSize);
    slice.forEach(job => {
      const tr = document.createElement('tr'); tr.className = 'hover:bg-gray-50';
      tr.innerHTML = `
        <td class="px-6 py-4 font-medium text-gray-700">${escapeHtml(job.title)}</td>
        <td class="px-6 py-4 text-gray-600">${escapeHtml(job.client || '')}</td>
        <td class="px-6 py-4 text-gray-600">${escapeHtml(job.location || '')}</td>
        <td class="px-6 py-4"><span class="px-3 py-1 text-xs rounded-full ${job.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'} font-semibold">${escapeHtml(job.status || '')}</span></td>
        <td class="px-6 py-4 flex justify-end gap-3">
  <!-- View -->
  <button title="View" data-id="${job.id}" class="viewBtn w-10 h-10 flex items-center justify-center rounded-full bg-blue-50 hover:bg-blue-100 shadow-lg transition-transform transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
  </button>

  <!-- Edit -->
  <button title="Edit" data-id="${job.id}" class="editBtn w-10 h-10 flex items-center justify-center rounded-full bg-emerald-100 hover:bg-emerald-200 shadow-lg transition-transform transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.1 2.1 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
    </svg>
  </button>

  <!-- Delete -->
  <button title="Delete" data-id="${job.id}" class="deleteBtn w-10 h-10 flex items-center justify-center rounded-full bg-red-600 hover:bg-red-700 shadow-lg transition-transform transform hover:scale-110">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
        </td>`;
      tbody.appendChild(tr);
    });
    // Attach handlers
    document.querySelectorAll('.viewBtn').forEach(b => b.addEventListener('click', () => viewJob(b.dataset.id)));
    document.querySelectorAll('.editBtn').forEach(b => b.addEventListener('click', () => openEditModal(b.dataset.id)));
    document.querySelectorAll('.deleteBtn').forEach(b => b.addEventListener('click', () => deleteJob(b.dataset.id)));
  }

  function renderPagination() {
    const controls = document.getElementById('paginationControls');
    controls.innerHTML = '';
    const filtered = getFilteredJobs();
    const total = Math.ceil(filtered.length / pageSize) || 1;
    const prev = document.createElement('button'); prev.className = 'px-3 py-1 border rounded mr-2'; prev.textContent = 'Prev'; prev.disabled = currentPage === 1; prev.onclick = () => { if (currentPage>1) { currentPage--; renderJobsPage(currentPage); renderPagination(); } };
    const next = document.createElement('button'); next.className = 'px-3 py-1 border rounded ml-2'; next.textContent = 'Next'; next.disabled = currentPage === total; next.onclick = () => { if (currentPage<total) { currentPage++; renderJobsPage(currentPage); renderPagination(); } };
    const info = document.createElement('span'); info.className = 'text-sm text-gray-600'; info.textContent = ` Page ${currentPage} of ${total} `;
    controls.appendChild(prev); controls.appendChild(info); controls.appendChild(next);
  }

  // Return jobsData filtered by adminSearch and selected status button
  function getFilteredJobs(){
    const q = (document.getElementById('adminSearch')?.value || '').toLowerCase().trim();
    const selBtn = document.querySelector('.adminStatusBtn.selected');
    const status = selBtn ? selBtn.dataset.status : 'all';
    return jobsData.filter(job => {
      if(status !== 'all' && (job.status || '') !== status) return false;
      if(!q) return true;
      const hay = ((job.title||'') + ' ' + (job.client||'') + ' ' + (job.location||'')).toLowerCase();
      return hay.includes(q);
    });
  }

  function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g, c=>({'&':'&amp;','"':'&quot;',"'":"&#39;",'<':'&lt;','>':'&gt;'})[c]); }

  // Create job
  document.getElementById('saveCreateBtn').addEventListener('click', async () => {
    const form = document.getElementById('createJobForm');
    const data = new FormData();
    data.append('title', document.getElementById('titleInput').value);
    data.append('client', document.getElementById('clientInput').value);
    data.append('job_type', document.getElementById('jobTypeSelect').value);
    data.append('salary', document.getElementById('salaryInput').value);
    data.append('location', document.getElementById('locationInput').value);
    data.append('skills', collectTags(document.getElementById('skillsTags')));
    data.append('qualifications', collectTags(document.getElementById('qualificationTags')));
    data.append('benefits', collectTags(document.getElementById('benefitsTags')));
    data.append('job_description', document.getElementById('jobDescriptionInput').value || '');
    data.append('status', document.getElementById('statusSelect').value || 'Active');
    const res = await fetch(apiUrl + '?action=create', { method: 'POST', body: data });
    const json = await res.json();
    if (json.success) {
      notifySuccess('Created successfully');
      document.getElementById('createJobModal').classList.add('hidden');
      form.reset(); document.getElementById('skillsTags').innerHTML=''; document.getElementById('qualificationTags').innerHTML=''; document.getElementById('benefitsTags').innerHTML='';
      loadJobs();
    } else notifyError(json.message || 'Failed to create');
  });

  // Open edit modal and populate
  async function openEditModal(id) {
    const res = await fetch(apiUrl + '?action=get&id=' + encodeURIComponent(id));
    const json = await res.json();
    if (!json.success) return notifyError('Failed to fetch job');
    const job = json.data;
    document.getElementById('editJobId').value = job.id;
    document.getElementById('editTitle').value = job.title || '';
    document.getElementById('editClient').value = job.client || '';
    document.getElementById('editJobType').value = job.job_type || '';
    document.getElementById('editSalary').value = job.salary || '';
    document.getElementById('editLocation').value = job.location || '';
    document.getElementById('editStatus').value = job.status || 'Active';
    document.getElementById('editJobDescription').value = job.job_description || '';

    // fill tags
    const fillTags = (containerId, text) => {
      const c = document.getElementById(containerId); c.innerHTML = '';
      if (!text) return;
      text.split(',').map(s => s.trim()).filter(Boolean).forEach(t => {
        const tag = document.createElement('span'); tag.className='bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm flex items-center gap-2'; tag.textContent = t;
        const btn = document.createElement('button'); btn.className='font-bold ml-2'; btn.innerHTML='&times;'; btn.onclick = () => tag.remove(); tag.appendChild(btn); c.appendChild(tag);
      });
    };
    fillTags('editSkillsTags', job.skills);
    fillTags('editQualificationTags', job.qualifications);
    fillTags('editBenefitsTags', job.benefits);

    document.getElementById('editJobModal').classList.remove('hidden');
    initEditTagInputs();
  }

  // Save edit
  document.getElementById('saveEditBtn').addEventListener('click', async () => {
    const data = new FormData();
    data.append('id', document.getElementById('editJobId').value);
    data.append('title', document.getElementById('editTitle').value);
    data.append('client', document.getElementById('editClient').value);
    data.append('job_type', document.getElementById('editJobType').value);
    data.append('salary', document.getElementById('editSalary').value);
    data.append('location', document.getElementById('editLocation').value);
    data.append('skills', collectTags(document.getElementById('editSkillsTags')));
    data.append('qualifications', collectTags(document.getElementById('editQualificationTags')));
    data.append('benefits', collectTags(document.getElementById('editBenefitsTags')));
    data.append('job_description', document.getElementById('editJobDescription').value || '');
    data.append('status', document.getElementById('editStatus').value || 'Active');
    const res = await fetch(apiUrl + '?action=update', { method: 'POST', body: data });
    const json = await res.json();
    if (json.success) {
      notifySuccess('Updated successfully');
      document.getElementById('editJobModal').classList.add('hidden');
      loadJobs();
    } else notifyError(json.message || 'Failed to update');
  });

  // Delete
  async function deleteJob(id) {
    const { value } = await Swal.fire({ title: 'Delete?', text: 'This will be removed permanently.', icon: 'warning', showCancelButton: true });
    if (!value) return;
    const res = await fetch(apiUrl + '?action=delete', { method: 'POST', body: new URLSearchParams({ id }) });
    const json = await res.json();
    if (json.success) { notifySuccess('Deleted successfully'); loadJobs(); } else notifyError(json.message || 'Failed to delete');
  }

  // View job
  async function viewJob(id) {
    const res = await fetch(apiUrl + '?action=get&id=' + encodeURIComponent(id));
    const json = await res.json();
    if (!json.success) return notifyError('Failed to fetch job');
    const job = json.data;
    // Populate modal fields
    const setText = (id, txt) => { const el = document.getElementById(id); if(el) el.textContent = txt || '—'; };
    setText('viewJobTitle', job.title || '—');
    const descEl = document.getElementById('viewJobDescription'); if(descEl) descEl.innerHTML = (job.job_description && job.job_description.trim()) ? escapeHtml(job.job_description).replace(/\n/g,'<br>') : '<span class="text-sm text-gray-600">—</span>';
    setText('viewJobType', job.job_type || '—');
    setText('viewClient', job.client || '—');
    setText('viewSalary', job.salary || '—');
    setText('viewLocation', job.location || '—');
    setText('viewStatus', job.status || '—');

    const renderTagContainer = (id, text) => {
      const container = document.getElementById(id);
      if(!container) return;
      container.innerHTML = '';
      if(!text) return;
      text.split(',').map(s=>s.trim()).filter(Boolean).forEach(t=>{
        const span = document.createElement('span');
        span.className = 'px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs';
        span.textContent = t;
        container.appendChild(span);
      });
    };
    renderTagContainer('viewSkills', job.skills);
    renderTagContainer('viewQualifications', job.qualifications);
    renderTagContainer('viewBenefits', job.benefits);

    // Show modal
    const modal = document.getElementById('viewJobModal'); if(modal) modal.classList.remove('hidden');
  }

  // Maps removed: location is address-only now

  // Initial load
  loadJobs();
  // Wire admin filters (status buttons + search apply/clear)
  function getSelectedStatus(){ const b = document.querySelector('.adminStatusBtn.selected'); return b ? b.dataset.status : 'all'; }
  // initialize: mark 'All' as selected
  const statusBtns = document.querySelectorAll('.adminStatusBtn');
  const statusPills = document.getElementById('statusPills');
  const statusIndicator = document.getElementById('statusIndicator');

  function moveIndicatorTo(btn){
    if(!btn || !statusPills || !statusIndicator) return;
    const rBtn = btn.getBoundingClientRect();
    const rParent = statusPills.getBoundingClientRect();
    const left = rBtn.left - rParent.left + statusPills.scrollLeft;
    statusIndicator.style.width = rBtn.width + 'px';
    statusIndicator.style.height = rBtn.height + 'px';
    statusIndicator.style.transform = `translateX(${left}px)`;
  }
  statusBtns.forEach((b, idx)=>{
    if(idx===0) { b.classList.add('selected'); }
    b.addEventListener('click', ()=>{
      statusBtns.forEach(x=>{ x.classList.remove('selected'); x.classList.remove('bg-emerald-600','text-white'); x.classList.add('text-neutral-700'); });
      b.classList.add('selected'); b.classList.remove('text-neutral-700'); b.classList.add('text-white');
      // animate indicator
      moveIndicatorTo(b);
      currentPage = 1; renderJobsPage(1); renderPagination();
    });
  });

  // Position indicator to initial selected
  const initial = document.querySelector('.adminStatusBtn.selected');
  if(initial) setTimeout(()=>moveIndicatorTo(initial), 50);
  window.addEventListener('resize', ()=>{ const sel = document.querySelector('.adminStatusBtn.selected'); if(sel) moveIndicatorTo(sel); });

  const searchInput = document.getElementById('adminSearch');
  const applyBtn = document.getElementById('adminSearchApply');
  const clearBtn = document.getElementById('adminSearchClear');
  if(applyBtn) applyBtn.addEventListener('click', ()=>{ currentPage = 1; renderJobsPage(1); renderPagination(); });
  if(clearBtn) clearBtn.addEventListener('click', ()=>{ if(searchInput){ searchInput.value=''; } currentPage = 1; renderJobsPage(1); renderPagination(); });
</script>

<?php include 'footer.php'; ?>
