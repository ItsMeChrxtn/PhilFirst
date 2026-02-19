<?php include 'header_clean.php'; ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ================= CMS HEADER ================= -->
<div class="relative overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm mb-6">
  <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-transparent"></div>

  <div class="relative p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h2 class="text-2xl font-semibold text-neutral-900 tracking-tight flex items-center gap-3">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white shadow">
          <i class="fa-solid fa-layer-group"></i>
        </span>
        Content Management
      </h2>
      <p class="text-sm text-neutral-500 mt-1">
        Manage homepage sections and dynamic website pages.
      </p>
      <div class="mt-3 flex items-center gap-2 text-xs text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-200">
        <i class="fa-solid fa-info-circle"></i>
        <span>Changes auto-sync to live pages (no refresh needed)</span>
      </div>
    </div>
    <button onclick="initializeSections()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2" title="Ensure all homepage sections exist">
      <i class="fa-solid fa-sync"></i> Initialize Sections
    </button>
  </div>
</div>

<!-- ================= HOMEPAGE SECTIONS ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-rocket text-emerald-600"></i>
    Hero Section (Landing Page)
  </h3>
  
  <!-- Hero Text Content -->
  <div class="mb-4">
    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Text Content</h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <?php
      $heroSections = [
        ['slug'=>'hero-tagline','title'=>'Tagline'],
        ['slug'=>'hero-title','title'=>'Main Title'],
        ['slug'=>'hero-description','title'=>'Description']
      ];
      foreach($heroSections as $s):
      ?>
      <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
        <div class="flex justify-between items-center mb-2">
          <h4 class="font-medium text-neutral-800 text-xs"><?php echo $s['title']; ?></h4>
          <button onclick="editSection('<?php echo $s['slug']; ?>')"
            class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
            Edit
          </button>
        </div>
        <div id="<?php echo $s['slug']; ?>-preview"
          class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-14 overflow-hidden">
          Loading...
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <!-- Hero Background Images -->
  <div>
    <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Background Slider Images</h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <?php
      $heroBgs = [
        ['slug'=>'hero-bg-1','title'=>'Background 1'],
        ['slug'=>'hero-bg-2','title'=>'Background 2'],
        ['slug'=>'hero-bg-3','title'=>'Background 3']
      ];
      foreach($heroBgs as $hb):
      ?>
      <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
        <div class="flex justify-between items-center mb-2">
          <h4 class="font-medium text-neutral-800 text-xs"><?php echo $hb['title']; ?></h4>
          <button onclick="editSection('<?php echo $hb['slug']; ?>')"
            class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
            Edit
          </button>
        </div>
        <div id="<?php echo $hb['slug']; ?>-preview"
          class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-10 overflow-hidden">
          Loading...
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ================= CONTACT SECTION ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-envelope text-emerald-600"></i>
    Contact Section
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <?php
    $contactSections = [
      ['slug'=>'contact-title','title'=>'Section Title'],
      ['slug'=>'contact-description','title'=>'Description'],
      ['slug'=>'contact-phone','title'=>'Phone Number'],
      ['slug'=>'contact-email','title'=>'Email Address'],
      ['slug'=>'contact-address','title'=>'Office Address'],
      ['slug'=>'contact-hours','title'=>'Office Hours'],
      ['slug'=>'contact-note','title'=>'Footer Note']
    ];
    foreach($contactSections as $s):
    ?>
    <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
      <div class="flex justify-between items-center mb-2">
        <h4 class="font-medium text-neutral-800 text-xs"><?php echo $s['title']; ?></h4>
        <button onclick="editSection('<?php echo $s['slug']; ?>')"
          class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
          Edit
        </button>
      </div>
      <div id="<?php echo $s['slug']; ?>-preview"
        class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-14 overflow-hidden">
        Loading...
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================= WHY HR IS IMPORTANT (6 cards) ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-question-circle text-emerald-600"></i>
    Why HR is Important (6 Cards)
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <?php
    $whyHR = [
      ['slug'=>'why-hr-1','title'=>'Recruitment & Retention'],
      ['slug'=>'why-hr-2','title'=>'Training & Development'],
      ['slug'=>'why-hr-3','title'=>'Employee Satisfaction'],
      ['slug'=>'why-hr-4','title'=>'Compliance'],
      ['slug'=>'why-hr-5','title'=>'Performance Management'],
      ['slug'=>'why-hr-6','title'=>'Strategic Management']
    ];
    foreach($whyHR as $w):
    ?>
    <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
      <div class="flex justify-between items-center mb-2">
        <h4 class="font-medium text-neutral-800 text-xs"><?php echo $w['title']; ?></h4>
        <button onclick="editSection('<?php echo $w['slug']; ?>')"
          class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
          Edit
        </button>
      </div>
      <div id="<?php echo $w['slug']; ?>-preview"
        class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-10 overflow-hidden">
        Loading...
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================= MISSION VISION VALUES ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-compass text-emerald-600"></i>
    Mission, Vision, Values
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php
    $mvv = [
      ['slug'=>'mission','title'=>'Mission'],
      ['slug'=>'vision','title'=>'Vision'],
      ['slug'=>'values','title'=>'Values']
    ];
    foreach($mvv as $m):
    ?>
    <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
      <div class="flex justify-between items-center mb-2">
        <h4 class="font-medium text-neutral-800 text-sm"><?php echo $m['title']; ?></h4>
        <button onclick="editSection('<?php echo $m['slug']; ?>')"
          class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
          Edit
        </button>
      </div>
      <div id="<?php echo $m['slug']; ?>-preview"
        class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-12 overflow-hidden">
        Loading...
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================= HOW IT WORKS - RECRUITMENT STEPS ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-gears text-emerald-600"></i>
    How It Works - Recruitment Steps
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <?php
    $recruitment = [
      ['slug'=>'recruit-step-1','title'=>'Step 1: Job Posting'],
      ['slug'=>'recruit-step-2','title'=>'Step 2: Screening'],
      ['slug'=>'recruit-step-3','title'=>'Step 3: Interview/Tests'],
      ['slug'=>'recruit-step-4','title'=>'Step 4: Job Offer'],
      ['slug'=>'recruit-step-5','title'=>'Step 5: Onboarding']
    ];
    foreach($recruitment as $r):
    ?>
    <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
      <div class="flex justify-between items-center mb-2">
        <h4 class="font-medium text-neutral-800 text-xs"><?php echo $r['title']; ?></h4>
        <button onclick="editSection('<?php echo $r['slug']; ?>')"
          class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
          Edit
        </button>
      </div>
      <div id="<?php echo $r['slug']; ?>-preview"
        class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-10 overflow-hidden">
        Loading...
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================= OUR HR SERVICES (6 services) ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-briefcase text-emerald-600"></i>
    Our HR Services (Title + Description)
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    <?php
    $services = [
      ['slug'=>'service-1','title'=>'Recruitment & Staffing'],
      ['slug'=>'service-2','title'=>'Payroll Management'],
      ['slug'=>'service-3','title'=>'Benefits Administration'],
      ['slug'=>'service-4','title'=>'Training & Development'],
      ['slug'=>'service-5','title'=>'Performance Management'],
      ['slug'=>'service-6','title'=>'Employee Relations']
    ];
    foreach($services as $srv):
    ?>
    <div class="bg-neutral-50 rounded-lg border border-neutral-200 p-3">
      <div class="flex justify-between items-center mb-2">
        <h4 class="font-medium text-neutral-800 text-xs"><?php echo $srv['title']; ?></h4>
        <button onclick="editSection('<?php echo $srv['slug']; ?>')"
          class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
          Edit
        </button>
      </div>
      <div id="<?php echo $srv['slug']; ?>-preview"
        class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-10 overflow-hidden">
        Loading...
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================= ORGANIZATION CHART ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6 mb-6">
  <h3 class="font-semibold text-neutral-800 mb-4 flex items-center gap-2">
    <i class="fa-solid fa-sitemap text-emerald-600"></i>
    Organization Chart
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php
    $orgChart = [
      ['slug'=>'org-president','title'=>'President','default'=>'Rodel P. Torio'],
      ['slug'=>'org-ceo','title'=>'CEO','default'=>'Carlitos S. Ruiz'],
      ['slug'=>'org-gm','title'=>'GM / Treasurer','default'=>'Sharon C. Alcantara'],
      ['slug'=>'org-secretary','title'=>'Corporate Secretary','default'=>'Hazel Grace D. Caluya'],
      ['slug'=>'org-hr-supervisor','title'=>'HR Supervisor','default'=>'Marilyn Pabalate'],
      ['slug'=>'org-accounting','title'=>'Accounting Officer','default'=>'Ma. Christina Ricamara'],
      ['slug'=>'org-marketing','title'=>'Marketing Officer','default'=>'Adelyn Broquiza'],
      ['slug'=>'org-hr-coordinator','title'=>'HR Coordinator','default'=>'Robert James Cuasay'],
      ['slug'=>'org-accounting-asst','title'=>'Accounting Assistant','default'=>'Ma. Arlene Carbonilla'],
      ['slug'=>'org-housekeeping','title'=>'Housekeeping Supervisor','default'=>'Jocelyn Estrada'],
      ['slug'=>'org-exec-housekeeper','title'=>'Executive Housekeeper','default'=>'Tata Ayunan'],
      ['slug'=>'org-branch-staff','title'=>'Branch Staff','default'=>'Pangasinan Branch'],
    ];
    foreach($orgChart as $org):
    ?>
    <div class="bg-neutral-50 rounded-xl border border-neutral-200 p-3">
      <div class="flex justify-between items-center mb-2">
        <h4 class="font-medium text-neutral-800 text-xs">
          <?php echo $org['title']; ?>
        </h4>
        <button onclick="editSection('<?php echo $org['slug']; ?>')"
          class="px-2 py-1 rounded bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition">
          Edit
        </button>
      </div>
      <div id="<?php echo $org['slug']; ?>-preview"
        class="text-xs text-neutral-500 bg-white border border-neutral-200 rounded p-2 max-h-12 overflow-hidden">
        Loading...
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ================= FRONTEND APP SECTIONS (frontend/index.php) ================= -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

<?php
$sections = [
  ['slug'=>'hero','icon'=>'rocket','title'=>'Hero Section'],
  ['slug'=>'why-apply','icon'=>'lightbulb','title'=>'Why Apply Section'],
  ['slug'=>'video-guide','icon'=>'video','title'=>'Video Guide Section'],
  ['slug'=>'cta-final','icon'=>'star','title'=>'Call To Action Section']
];
foreach($sections as $s):
?>

<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6">
  <div class="flex justify-between items-center mb-3">
    <h3 class="font-semibold text-neutral-800 flex items-center gap-2">
      <i class="fa-solid fa-<?php echo $s['icon']; ?> text-emerald-600"></i>
      <?php echo $s['title']; ?>
    </h3>

    <button onclick="editSection('<?php echo $s['slug']; ?>')"
      class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition">
      Edit
    </button>
  </div>

  <div id="<?php echo $s['slug']; ?>-preview"
    class="text-sm text-neutral-500 bg-neutral-50 border border-neutral-200 rounded-xl p-4">
    Loading...
  </div>
  
  <?php if($s['slug'] === 'video-guide'): ?>
  <div class="mt-3 pt-3 border-t border-neutral-200">
    <button onclick="showVideoUploadModal()"
      class="w-full px-3 py-2 rounded-lg bg-blue-100 text-blue-700 text-sm font-medium hover:bg-blue-200 transition flex items-center justify-center gap-2">
      <i class="fa-solid fa-upload"></i>
      Upload Video
    </button>
  </div>
  <?php endif; ?>
</div>

<?php endforeach; ?>

</div>

<!-- ================= WEBSITE PAGES ================= -->
<div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-6">

  <div class="flex items-center justify-between mb-4">
    <h3 class="font-semibold text-neutral-800">Website Pages</h3>

    <button id="addPageBtn"
      class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-emerald-700 transition-all">
      <i class="fa-solid fa-plus"></i>
      Add Page
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full">
      <thead class="bg-neutral-50 border-b border-neutral-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">ID</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Title</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Slug</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Status</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Created</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-neutral-500">Actions</th>
        </tr>
      </thead>
      <tbody id="pagesTableBody" class="divide-y divide-neutral-200"></tbody>
    </table>
  </div>

</div>

<!-- ================= SECTION MODAL ================= -->
<div id="sectionModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Edit Section</h3>
      <button onclick="closeSectionModal()" class="text-gray-400 text-2xl">&times;</button>
    </div>

    <form id="sectionForm" class="space-y-4">
      <input type="hidden" id="sectionSlug">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
        <input type="text" id="sectionTitle"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          placeholder="Enter section title">
      </div>

      <div id="contentField">
        <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
        <textarea id="sectionContent" rows="6"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          placeholder="Enter section content"></textarea>
      </div>

      <div id="imageUrlField" class="hidden space-y-3">
        <label class="block text-sm font-medium text-gray-700">
          Image <span class="text-xs text-gray-500">(Upload the card/person photo)</span>
        </label>
        
        <!-- File Upload -->
        <div class="border-2 border-dashed border-neutral-300 rounded-lg p-6 text-center hover:border-emerald-500 transition cursor-pointer"
             onclick="document.getElementById('imageFileInput').click()">
          <i class="fa-solid fa-cloud-arrow-up text-4xl text-neutral-400 mb-3"></i>
          <p class="text-sm font-medium text-gray-700 mb-1">Click to upload image</p>
          <p class="text-xs text-gray-500">JPG, PNG, GIF, WebP • Max 5MB</p>
          <input type="file" id="imageFileInput" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="hidden">
        </div>
        
        <div id="imageFileName" class="text-sm text-emerald-600 font-medium"></div>

        <div id="imagePreviewWrap" class="hidden">
          <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
          <img id="imagePreview" src="" alt="Current" class="w-full h-40 object-cover rounded-lg border border-neutral-200">
        </div>
        
        <!-- Hidden URL field (auto-filled by upload) -->
        <input type="hidden" id="sectionImageUrl">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select id="sectionStatus"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="published">Published</option>
          <option value="draft">Draft</option>
        </select>
      </div>

      <div class="flex justify-end gap-2 pt-4 border-t border-neutral-200">
        <button type="button"
          onclick="closeSectionModal()"
          class="px-4 py-2 rounded bg-neutral-100 hover:bg-neutral-200 transition font-medium">
          Cancel
        </button>

        <button type="submit"
          id="sectionSaveBtn"
          class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700 transition font-medium flex items-center gap-2">
          <span>Save Changes</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ================= PAGE MODAL ================= -->
<div id="pageModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 id="modalTitle" class="text-lg font-semibold">Create Page</h3>
      <button onclick="closePageModal()" class="text-gray-400 text-2xl">&times;</button>
    </div>

    <form id="pageForm" class="space-y-4">
      <input type="hidden" id="pageId">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
        <input type="text" id="pageTitle"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          placeholder="Enter page title">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
        <input type="text" id="pageSlug"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          placeholder="Enter page slug (e.g., about-us)">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
        <textarea id="pageContent" rows="6"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
          placeholder="Enter page content"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select id="pageStatus"
          class="w-full border border-neutral-200 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="draft">Draft</option>
          <option value="published">Published</option>
        </select>
      </div>

      <div class="flex justify-end gap-2 pt-4 border-t border-neutral-200">
        <button type="button"
          onclick="closePageModal()"
          class="px-4 py-2 rounded bg-neutral-100 hover:bg-neutral-200 transition font-medium">
          Cancel
        </button>

        <button type="submit"
          id="pageSaveBtn"
          class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700 transition font-medium flex items-center gap-2">
          <span>Save Page</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ================= VIDEO UPLOAD MODAL ================= -->
<div id="videoUploadModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Upload Video</h3>
      <button onclick="closeVideoUploadModal()" class="text-gray-400 text-2xl">&times;</button>
    </div>

    <form id="videoUploadForm" class="space-y-4">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Select Video File</label>
        <div class="border-2 border-dashed border-neutral-300 rounded-lg p-6 text-center hover:border-emerald-500 transition cursor-pointer" 
             onclick="document.getElementById('videoFile').click()">
          <i class="fa-solid fa-cloud-arrow-up text-4xl text-neutral-400 mb-2"></i>
          <p class="text-sm text-gray-600">Click to select or drag and drop</p>
          <p class="text-xs text-gray-500 mt-1">MP4, WebM, OGG • Max 500MB</p>
          <input type="file" id="videoFile" accept="video/mp4,video/webm,video/ogg" class="hidden">
        </div>
        <div id="videoFileName" class="mt-2 text-sm text-emerald-600"></div>
      </div>

      <div id="videoPreview" class="hidden">
        <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
        <video id="videoPreviewElement" class="w-full rounded-lg border border-neutral-200" controls></video>
      </div>

      <div class="flex justify-end gap-2 pt-4 border-t border-neutral-200">
        <button type="button"
          onclick="closeVideoUploadModal()"
          class="px-4 py-2 rounded bg-neutral-100 hover:bg-neutral-200 transition font-medium">
          Cancel
        </button>

        <button type="submit"
          id="videoUploadBtn"
          class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700 transition font-medium flex items-center gap-2">
          <span>Upload Video</span>
        </button>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
const API_BASE = '/backend/cms.php';

function isImageSlug(slug){
  return slug.startsWith('hero-bg-') || slug.endsWith('-img');
}

const DEFAULTS = {
  // Hero Section
  'hero-tagline': {'title': 'Hero Tagline', 'content': 'Trusted HR & Recruitment Partner'},
  'hero-title': {'title': 'Hero Main Title', 'content': 'Unlock the Potential of Your People'},
  'hero-description': {'title': 'Hero Description', 'content': 'Empower your workforce for success and growth through strategic, people-first HR solutions aligned with your goals.'},
  
  // Contact Section
  'contact-title': {'title': 'Contact Section Title', 'content': 'Contact Us'},
  'contact-description': {'title': 'Contact Section Description', 'content': 'Get in touch with Phil-First HR & Services. You may reach us through the details below or visit our office location.'},
  'contact-phone': {'title': 'Contact Phone', 'content': '+63 917 123 4567'},
  'contact-email': {'title': 'Contact Email', 'content': 'info@philfirst.ph'},
  'contact-address': {'title': 'Contact Address', 'content': '123 PhilFirst St., Pasig City, Metro Manila, Philippines'},
  'contact-hours': {'title': 'Contact Office Hours', 'content': 'Monday – Friday<br>9:00 AM – 6:00 PM'},
  'contact-note': {'title': 'Contact Footer Note', 'content': 'For inquiries, please contact us during office hours.'},
  
  // Hero Slider Background Images
  'hero-bg-1': {'title': 'Hero Background 1', 'content': 'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=1600&q=80'},
  'hero-bg-2': {'title': 'Hero Background 2', 'content': 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1600&q=80'},
  'hero-bg-3': {'title': 'Hero Background 3', 'content': 'https://images.unsplash.com/photo-1581092795368-32ffdb3f8ebc?auto=format&fit=crop&w=1600&q=80'},
  
  // Why HR is Important - 6 Cards (exact from frontend/index.php)
  'why-hr-1': {'title': 'Recruitment & Retention', 'content': 'HR attracts, hires, and retains top talent, ensuring the right people are in the right roles to drive company success.'},
  'why-hr-1-img': {'title': 'Why HR Card 1 Image', 'content': 'https://images.pexels.com/photos/7144228/pexels-photo-7144228.jpeg'},
  'why-hr-2': {'title': 'Training & Development', 'content': 'HR organizes training programs to enhance skills, boost productivity, and prepare employees for leadership roles.'},
  'why-hr-2-img': {'title': 'Why HR Card 2 Image', 'content': 'https://plus.unsplash.com/premium_photo-1770123618996-53cfd9eac78b?w=600&auto=format&fit=crop&q=60'},
  'why-hr-3': {'title': 'Employee Satisfaction', 'content': 'HR creates a positive work environment, manages benefits, and resolves conflicts to improve morale and retention.'},
  'why-hr-3-img': {'title': 'Why HR Card 3 Image', 'content': 'https://plus.unsplash.com/premium_photo-1682310144714-cb77b1e6d64a?w=600&auto=format&fit=crop&q=60'},
  'why-hr-4': {'title': 'Compliance', 'content': 'HR ensures compliance with labor laws, develops policies, and prevents potential lawsuits or penalties.'},
  'why-hr-4-img': {'title': 'Why HR Card 4 Image', 'content': 'https://www.bing.com/th/id/OIP.vj3U_3qh9Ex23BY4QdEjPgHaDs?w=323&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2'},
  'why-hr-5': {'title': 'Performance Management', 'content': 'HR implements performance appraisal systems to evaluate employees, identify improvements, and recognize top performers.'},
  'why-hr-5-img': {'title': 'Why HR Card 5 Image', 'content': 'https://images.unsplash.com/photo-1507099985932-87a4520ed1d5?w=600&auto=format&fit=crop&q=60'},
  'why-hr-6': {'title': 'Strategic Management', 'content': 'HR aligns workforce with company goals, manages organizational changes, and helps in strategic decision-making.'},
  'why-hr-6-img': {'title': 'Why HR Card 6 Image', 'content': 'https://images.unsplash.com/flagged/photo-1570551502611-c590dc45f644?w=600&auto=format&fit=crop&q=60'},
  
  // Mission, Vision, Values (exact from frontend/index.php)
  'mission': {'title': 'MISSION', 'content': 'Become a trusted partner helping clients maximize performance by aligning human resources with strategic objectives through ethical and effective HR services.'},
  'vision': {'title': 'VISION', 'content': 'Empower businesses with a skilled, motivated workforce aligned to strategic goals; be recognized as a leading HR agency prioritizing growth and well-being.'},
  'values': {'title': 'VALUES', 'content': 'Respect · Integrity · Equality · Development · Work-Life Balance · Engagement · Improvement'},
  
  // How It Works - Recruitment Steps (exact from frontend/index.php)
  'recruit-step-1': {'title': 'Job Posting', 'content': 'Resumes collected via social media, job fairs, walk-ins, or email.'},
  'recruit-step-2': {'title': 'Screening', 'content': 'Shortlist candidates who meet the basic job requirements.'},
  'recruit-step-3': {'title': 'Interview / Tests', 'content': 'Interviews in-person, phone, or video; include assessments as needed.'},
  'recruit-step-4': {'title': 'Job Offer', 'content': 'Offer includes salary, benefits, start date; client final interview as needed.'},
  'recruit-step-5': {'title': 'Onboarding', 'content': 'Complete paperwork, orientation, training, and integration for new hires.'},
  
  // Our HR Services (exact from frontend/index.php)
  'service-1': {'title': 'Recruitment & Staffing', 'content': 'End-to-end recruitment services including talent sourcing, screening, interviewing, and placement tailored to your business goals.'},
  'service-2': {'title': 'Payroll Management', 'content': 'Accurate, compliant payroll processing including salaries, deductions, taxes, benefits, and reporting.'},
  'service-3': {'title': 'Benefits Administration', 'content': 'Management of employee benefits, insurance, leaves, and incentive programs with full compliance.'},
  'service-4': {'title': 'Training & Development', 'content': 'Structured programs to upskill employees, improve performance, and boost workforce capability.'},
  'service-5': {'title': 'Performance Management', 'content': 'Design and implementation of performance evaluation frameworks that align outcomes with business objectives.'},
  'service-6': {'title': 'Employee Relations', 'content': 'Proactive engagement and conflict-resolution strategies to foster a collaborative and positive workplace culture.'},
  
  // Organization Chart (exact names and titles from frontend/index.php)
  'org-president': {'title': 'President', 'content': 'Rodel P. Torio'},
  'org-president-img': {'title': 'President Photo', 'content': 'https://ui-avatars.com/api/?name=Rodel+Torio'},
  'org-ceo': {'title': 'CEO', 'content': 'Carlitos S. Ruiz'},
  'org-ceo-img': {'title': 'CEO Photo', 'content': 'https://ui-avatars.com/api/?name=Carlitos+Ruiz'},
  'org-gm': {'title': 'GM / Treasurer', 'content': 'Sharon C. Alcantara'},
  'org-gm-img': {'title': 'GM Photo', 'content': 'https://ui-avatars.com/api/?name=Sharon+Alcantara'},
  'org-secretary': {'title': 'Corporate Secretary', 'content': 'Hazel Grace D. Caluya'},
  'org-secretary-img': {'title': 'Secretary Photo', 'content': 'https://ui-avatars.com/api/?name=Hazel+Caluya'},
  'org-hr-supervisor': {'title': 'HR Supervisor', 'content': 'Marilyn Pabalate'},
  'org-hr-supervisor-img': {'title': 'HR Supervisor Photo', 'content': 'https://ui-avatars.com/api/?name=Marilyn+Pabalate'},
  'org-accounting': {'title': 'Accounting Officer', 'content': 'Ma. Christina Ricamara'},
  'org-accounting-img': {'title': 'Accounting Officer Photo', 'content': 'https://ui-avatars.com/api/?name=Christina+Ricamara'},
  'org-marketing': {'title': 'Marketing Officer', 'content': 'Adelyn Broquiza'},
  'org-marketing-img': {'title': 'Marketing Officer Photo', 'content': 'https://ui-avatars.com/api/?name=Adelyn+Broquiza'},
  'org-hr-coordinator': {'title': 'HR Coordinator', 'content': 'Robert James Cuasay'},
  'org-hr-coordinator-img': {'title': 'HR Coordinator Photo', 'content': 'https://ui-avatars.com/api/?name=Robert+Cuasay'},
  'org-accounting-asst': {'title': 'Accounting Assistant', 'content': 'Ma. Arlene Carbonilla'},
  'org-accounting-asst-img': {'title': 'Accounting Assistant Photo', 'content': 'https://ui-avatars.com/api/?name=Arlene+Carbonilla'},
  'org-housekeeping': {'title': 'Housekeeping Supervisor', 'content': 'Jocelyn Estrada'},
  'org-housekeeping-img': {'title': 'Housekeeping Supervisor Photo', 'content': 'https://ui-avatars.com/api/?name=Jocelyn+Estrada'},
  'org-exec-housekeeper': {'title': 'Executive Housekeeper', 'content': 'Tata Ayunan'},
  'org-exec-housekeeper-img': {'title': 'Executive Housekeeper Photo', 'content': 'https://ui-avatars.com/api/?name=Tata+Ayunan'},
  'org-branch-staff': {'title': 'Branch Staff', 'content': 'Pangasinan Branch'},
  'org-branch-staff-img': {'title': 'Branch Staff Photo', 'content': 'https://ui-avatars.com/api/?name=Branch+Staff'},
  
  // Frontend App Sections (index.php applicant portal)
  'hero': {'title': 'Welcome to Philfirst', 'content': 'Start your journey with a professional recruitment platform.'},
  'why-apply': {'title': 'Why Apply Through Philfirst?', 'content': 'We make job application simple, fast, and professional.'},
  'video-guide': {'title': 'How to Use Philfirst', 'content': 'Watch this quick guide to learn how to apply and track your application.'},
  'video-guide-url': {'title': 'Video Guide URL', 'content': 'your-video.mp4'},
  'cta-final': {'title': 'Your Career Starts Here', 'content': 'Don\'t miss the opportunity. Apply now and take the first step toward your professional future.'}
};

// ==================== IMAGE FILE UPLOAD HANDLER ====================
document.getElementById('imageFileInput').addEventListener('change', async function(e) {
  const file = e.target.files[0];
  if (!file) return;
  
  // Validate file type
  const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
  if (!validTypes.includes(file.type)) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid File Type',
      text: 'Please upload a valid image file (JPG, PNG, GIF, or WebP)'
    });
    e.target.value = '';
    return;
  }
  
  // Validate file size (5MB max)
  if (file.size > 5 * 1024 * 1024) {
    Swal.fire({
      icon: 'error',
      title: 'File Too Large',
      text: 'Image must be less than 5MB'
    });
    e.target.value = '';
    return;
  }
  
  // Show loading
  Swal.fire({
    title: 'Uploading...',
    html: 'Please wait while we upload your image',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });
  
  // Upload file
  const formData = new FormData();
  formData.append('image', file);
  
  try {
    const response = await fetch('/backend/upload_image.php', {
      method: 'POST',
      body: formData
    });
    
    const result = await response.json();
    
    if (result.success) {
      // Populate URL field with uploaded image path
      document.getElementById('sectionImageUrl').value = result.url;

      const imagePreview = document.getElementById('imagePreview');
      const imagePreviewWrap = document.getElementById('imagePreviewWrap');
      if (imagePreview && imagePreviewWrap) {
        imagePreview.src = result.url;
        imagePreviewWrap.classList.remove('hidden');
      }
      
      // Show filename
      document.getElementById('imageFileName').innerHTML = 
        `<i class="fa-solid fa-check text-emerald-600"></i> ${result.filename}`;
      
      Swal.fire({
        icon: 'success',
        title: 'Uploaded!',
        text: 'Image uploaded successfully',
        timer: 1500,
        showConfirmButton: false
      });
    } else {
      throw new Error(result.error || 'Upload failed');
    }
  } catch (error) {
    console.error('Upload error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Upload Failed',
      text: error.message || 'Failed to upload image'
    });
    e.target.value = '';
  }
});

// ==================== LOAD ALL SECTIONS ====================
async function loadSections(){
  for(const [slug, defaults] of Object.entries(DEFAULTS)){
    try {
      const res = await fetch(`${API_BASE}?action=get-by-slug&slug=${slug}`);
      const data = await res.json();
      const preview = document.getElementById(`${slug}-preview`);
      
      if(data.success){
        if (isImageSlug(slug)) {
          const src = data.data.content || '';
          preview.innerHTML = src
            ? `<img src="${escapeHtml(src)}" class="w-full h-20 object-cover rounded border border-neutral-200" alt="">`
            : `<strong>${escapeHtml(data.data.title)}</strong><br><br>No image set`;
        } else {
          const content = data.data.content.substring(0, 150);
          preview.innerHTML = `<strong>${escapeHtml(data.data.title)}</strong><br><br>${escapeHtml(content)}...`;
        }
      } else {
        if (isImageSlug(slug)) {
          const src = defaults.content || '';
          preview.innerHTML = src
            ? `<img src="${escapeHtml(src)}" class="w-full h-20 object-cover rounded border border-neutral-200" alt="">`
            : `<strong>${defaults.title}</strong><br><br>No image set`;
        } else {
          preview.innerHTML = `<strong>${defaults.title}</strong><br><br>${defaults.content}`;
        }
      }
    } catch(e){
      console.error(e);
    }
  }
}

// ==================== INITIALIZE SECTIONS ====================
async function initializeSections(){
  Swal.fire({
    title: 'Initializing...',
    icon: 'info',
    didOpen: async () => {
      Swal.showLoading();
      
      try {
        for(const [slug, defaults] of Object.entries(DEFAULTS)){
          const res = await fetch(`${API_BASE}?action=get-by-slug&slug=${slug}`);
          const data = await res.json();
          
          if(!data.success){
            // Create if doesn't exist
            const createRes = await fetch(`${API_BASE}?action=create`, {
              method: 'POST',
              headers: {'Content-Type': 'application/json'},
              body: JSON.stringify({
                title: defaults.title,
                slug: slug,
                content: defaults.content,
                status: 'published'
              })
            });
          }
        }
        
        Swal.fire({
          icon: 'success',
          title: 'Done!',
          text: 'All sections have been initialized',
          timer: 1500
        });
        loadSections();
      } catch(e) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to initialize sections'
        });
        console.error(e);
      }
    }
  });
}

// ==================== LOAD PAGES TABLE ====================
async function loadPages(){
  const res = await fetch(`${API_BASE}?action=list&limit=100`);
  const data = await res.json();
  const tbody = document.getElementById('pagesTableBody');
  tbody.innerHTML = '';

  if(data.success && data.data.length > 0){
    data.data.forEach(page=>{
      const date = new Date(page.created_at).toLocaleDateString();
      const status = page.status === 'published' ? '<span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-800">Published</span>' : '<span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Draft</span>';
      
      tbody.innerHTML += `
        <tr class="hover:bg-neutral-50 transition">
          <td class="px-4 py-3 text-sm">#${page.id}</td>
          <td class="px-4 py-3 font-medium text-sm">${escapeHtml(page.title)}</td>
          <td class="px-4 py-3 text-sm"><code class="bg-gray-100 px-2 py-1 rounded text-xs">${escapeHtml(page.slug)}</code></td>
          <td class="px-4 py-3 text-sm">${status}</td>
          <td class="px-4 py-3 text-sm">${date}</td>
          <td class="px-4 py-3 flex gap-2">
            <button onclick="editPage(${page.id})" class="px-3 py-1 rounded text-sm bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">
              <i class="fa-solid fa-edit"></i>
            </button>
            <button onclick="deletePage(${page.id})" class="px-3 py-1 rounded text-sm bg-red-100 text-red-700 hover:bg-red-200 transition">
              <i class="fa-solid fa-trash"></i>
            </button>
          </td>
        </tr>
      `;
    });
  } else {
    tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No pages created yet. <button onclick="showAddPageModal()" class="text-emerald-600 font-medium hover:underline">Create one</button></td></tr>';
  }
}

// ==================== EDIT SECTION ====================
async function editSection(slug){
  try {
    // Clear any previous file upload state
    document.getElementById('imageFileInput').value = '';
    document.getElementById('imageFileName').innerHTML = '';
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewWrap = document.getElementById('imagePreviewWrap');
    if (imagePreview) imagePreview.src = '';
    if (imagePreviewWrap) imagePreviewWrap.classList.add('hidden');
    
    const res = await fetch(`${API_BASE}?action=get-by-slug&slug=${slug}`);
    const data = await res.json();
    let page = data.success ? data.data : null;
    
    if(!page){
      page = {id: null, ...DEFAULTS[slug], slug: slug, status: 'published'};
    }
    
    document.getElementById('sectionSlug').value = slug;
    document.getElementById('sectionTitle').value = page.title;
    document.getElementById('sectionContent').value = page.content;
    document.getElementById('sectionStatus').value = page.status;
    
    // Hero backgrounds are image-only sections
    const imageOnlySections = ['hero-bg-1', 'hero-bg-2', 'hero-bg-3'];
    const contentField = document.getElementById('contentField');
    
    // Check if this section has an associated image
    const sectionsWithImages = ['why-hr-1', 'why-hr-2', 'why-hr-3', 'why-hr-4', 'why-hr-5', 'why-hr-6', 
                                 'org-president', 'org-ceo', 'org-gm', 'org-secretary', 'org-hr-supervisor',
                                 'org-accounting', 'org-marketing', 'org-hr-coordinator', 'org-accounting-asst',
                                 'org-housekeeping', 'org-exec-housekeeper', 'org-branch-staff'];
    
    const imageField = document.getElementById('imageUrlField');
    const imageUrlInput = document.getElementById('sectionImageUrl');
    const imageFileNameDiv = document.getElementById('imageFileName');
    
    if(imageOnlySections.includes(slug)){
      // Hide content field for image-only sections
      contentField.classList.add('hidden');
      
      // This section's content IS the image URL
      imageUrlInput.value = page.content;
      const filename = page.content.split('/').pop();
      imageFileNameDiv.innerHTML = `<i class="fa-solid fa-image text-emerald-600"></i> Current: ${filename}`;
      if (imagePreview && imagePreviewWrap && page.content) {
        imagePreview.src = page.content;
        imagePreviewWrap.classList.remove('hidden');
      }
      imageField.classList.remove('hidden');
    } else {
      // Show content field for normal sections
      contentField.classList.remove('hidden');
      
      if(sectionsWithImages.includes(slug)){
        // Load image URL from the -img slug
        const imgSlug = slug + '-img';
        const imgRes = await fetch(`${API_BASE}?action=get-by-slug&slug=${imgSlug}`);
        const imgData = await imgRes.json();
        
        if(imgData.success){
          imageUrlInput.value = imgData.data.content;
          // Show current filename
          const filename = imgData.data.content.split('/').pop();
          imageFileNameDiv.innerHTML = `<i class="fa-solid fa-image text-emerald-600"></i> Current: ${filename}`;
          if (imagePreview && imagePreviewWrap && imgData.data.content) {
            imagePreview.src = imgData.data.content;
            imagePreviewWrap.classList.remove('hidden');
          }
        } else if(DEFAULTS[imgSlug]){
          imageUrlInput.value = DEFAULTS[imgSlug].content;
          const filename = DEFAULTS[imgSlug].content.split('/').pop();
          imageFileNameDiv.innerHTML = `<i class="fa-solid fa-image text-gray-500"></i> Default: ${filename}`;
          if (imagePreview && imagePreviewWrap && DEFAULTS[imgSlug].content) {
            imagePreview.src = DEFAULTS[imgSlug].content;
            imagePreviewWrap.classList.remove('hidden');
          }
        } else {
          imageUrlInput.value = '';
          imageFileNameDiv.innerHTML = '<i class="fa-solid fa-info-circle text-gray-400"></i> No image uploaded yet';
        }
        
        imageField.classList.remove('hidden');
      } else if(slug.endsWith('-img')){
        // This IS an image URL field itself
        imageUrlInput.value = '';
        imageFileNameDiv.innerHTML = '';
        imageField.classList.add('hidden');
      } else {
        imageField.classList.add('hidden');
        imageUrlInput.value = '';
        imageFileNameDiv.innerHTML = '';
      }
    }
    
    document.getElementById('sectionModal').classList.remove('hidden');
  } catch(e){
    alert('Error loading section');
    console.error(e);
  }
}

// ==================== SAVE SECTION ====================
document.getElementById('sectionForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  
  const slug = document.getElementById('sectionSlug').value;
  const title = document.getElementById('sectionTitle').value.trim();
  let content = document.getElementById('sectionContent').value.trim();
  const status = document.getElementById('sectionStatus').value;
  const imageUrl = document.getElementById('sectionImageUrl').value.trim();
  
  // Image-only sections (hero backgrounds) - content IS the image URL
  const imageOnlySections = ['hero-bg-1', 'hero-bg-2', 'hero-bg-3'];
  
  if(imageOnlySections.includes(slug)){
    // For image-only sections, use the uploaded image URL as content
    if(imageUrl){
      content = imageUrl;
    } else {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Image',
        text: 'Please upload a background image'
      });
      return;
    }
  } else {
    // For regular sections, validate normal content
    if(!content){
      Swal.fire({
        icon: 'warning',
        title: 'Missing Content',
        text: 'Content is required'
      });
      return;
    }
  }
  
  if(!title){
    Swal.fire({
      icon: 'warning',
      title: 'Missing Title',
      text: 'Title is required'
    });
    return;
  }
  
  const saveBtn = document.getElementById('sectionSaveBtn');
  const originalText = saveBtn.innerHTML;
  saveBtn.disabled = true;
  saveBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i><span>Saving...</span>';
  
  try {
    // Save main content
    const res = await fetch(`${API_BASE}?action=get-by-slug&slug=${slug}`);
    const data = await res.json();
    
    const url = `${API_BASE}?action=${data.success ? 'update' : 'create'}`;
    const payload = {title, slug, content, status};
    if(data.success) payload.id = data.data.id;
    
    const saveRes = await fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload)
    });
    
    const result = await saveRes.json();
    
    // Save image URL if provided (for sections with separate images, not image-only sections)
    if(imageUrl && !document.getElementById('imageUrlField').classList.contains('hidden') && !imageOnlySections.includes(slug)){
      const imgSlug = slug + '-img';
      const imgRes = await fetch(`${API_BASE}?action=get-by-slug&slug=${imgSlug}`);
      const imgData = await imgRes.json();
      
      const imgUrl = `${API_BASE}?action=${imgData.success ? 'update' : 'create'}`;
      const imgPayload = {
        title: title + ' Image',
        slug: imgSlug,
        content: imageUrl,
        status: 'published'
      };
      if(imgData.success) imgPayload.id = imgData.data.id;
      
      await fetch(imgUrl, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(imgPayload)
      });
    }
    
    if(result.success){
      Swal.fire({
        icon: 'success',
        title: 'Saved!',
        html: 'Section updated successfully<br><small class="text-emerald-600">Auto-syncing to live pages...</small>',
        timer: 2000,
        showConfirmButton: false
      });
      document.getElementById('sectionModal').classList.add('hidden');
      loadSections();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: result.error || 'Failed to save section'
      });
    }
  } catch(e){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'An error occurred while saving'
    });
    console.error(e);
  } finally {
    saveBtn.disabled = false;
    saveBtn.innerHTML = originalText;
  }
});

// ==================== ADD PAGE ====================
function showAddPageModal(){
  document.getElementById('pageId').value = '';
  document.getElementById('pageTitle').value = '';
  document.getElementById('pageSlug').value = '';
  document.getElementById('pageContent').value = '';
  document.getElementById('pageStatus').value = 'draft';
  document.getElementById('modalTitle').textContent = 'Create New Page';
  document.getElementById('pageModal').classList.remove('hidden');
}

// ==================== EDIT PAGE ====================
async function editPage(id){
  try {
    const res = await fetch(`${API_BASE}?action=get&id=${id}`);
    const data = await res.json();
    
    if(data.success){
      const page = data.data;
      document.getElementById('pageId').value = page.id;
      document.getElementById('pageTitle').value = page.title;
      document.getElementById('pageSlug').value = page.slug;
      document.getElementById('pageContent').value = page.content;
      document.getElementById('pageStatus').value = page.status;
      document.getElementById('modalTitle').textContent = `Edit: ${page.title}`;
      document.getElementById('pageModal').classList.remove('hidden');
    }
  } catch(e){
    alert('Error loading page');
    console.error(e);
  }
}

// ==================== SAVE PAGE ====================
document.getElementById('pageForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  
  const id = document.getElementById('pageId').value;
  const title = document.getElementById('pageTitle').value.trim();
  const slug = document.getElementById('pageSlug').value.trim();
  const content = document.getElementById('pageContent').value.trim();
  const status = document.getElementById('pageStatus').value;
  
  if(!title || !slug || !content){
    Swal.fire({
      icon: 'warning',
      title: 'Missing Fields',
      text: 'Title, slug, and content are required'
    });
    return;
  }
  
  const saveBtn = document.getElementById('pageSaveBtn');
  const originalText = saveBtn.innerHTML;
  saveBtn.disabled = true;
  saveBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i><span>Saving...</span>';
  
  try {
    const url = `${API_BASE}?action=${id ? 'update' : 'create'}`;
    const payload = {title, slug, content, status};
    if(id) payload.id = parseInt(id);
    
    const res = await fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload)
    });
    
    const data = await res.json();
    if(data.success){
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: id ? 'Page updated successfully!' : 'Page created successfully!',
        timer: 1500
      });
      document.getElementById('pageModal').classList.add('hidden');
      loadPages();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.error || 'Failed to save page'
      });
    }
  } catch(e){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'An error occurred while saving'
    });
    console.error(e);
  } finally {
    saveBtn.disabled = false;
    saveBtn.innerHTML = originalText;
  }
});

// ==================== DELETE PAGE ====================
async function deletePage(id){
  Swal.fire({
    title: 'Delete Page?',
    text: 'This action cannot be undone',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!'
  }).then(async (result) => {
    if (!result.isConfirmed) return;
    
    Swal.fire({
      title: 'Deleting...',
      icon: 'info',
      didOpen: async () => {
        Swal.showLoading();
        
        try {
          const res = await fetch(`${API_BASE}?action=delete`, {
            method: 'DELETE',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id})
          });
          
          const data = await res.json();
          if(data.success){
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Page has been deleted',
              timer: 1500
            });
            loadPages();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.error || 'Failed to delete page'
            });
          }
        } catch(e){
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while deleting'
          });
          console.error(e);
        }
      }
    });
  });
}

// ==================== UTILITIES ====================
function closePageModal(){ 
  document.getElementById('pageModal').classList.add('hidden'); 
}

function closeSectionModal(){ 
  document.getElementById('sectionModal').classList.add('hidden');
  document.getElementById('imageFileInput').value = '';
  document.getElementById('imageFileName').innerHTML = '';
}

function closeVideoUploadModal(){ 
  document.getElementById('videoUploadModal').classList.add('hidden'); 
  document.getElementById('videoFile').value = '';
  document.getElementById('videoFileName').textContent = '';
  document.getElementById('videoPreview').classList.add('hidden');
}

function showVideoUploadModal(){
  document.getElementById('videoUploadModal').classList.remove('hidden');
}

// ==================== VIDEO UPLOAD ====================
const videoFileInput = document.getElementById('videoFile');
videoFileInput.addEventListener('change', (e) => {
  const file = e.target.files[0];
  if (file) {
    document.getElementById('videoFileName').textContent = `Selected: ${file.name}`;
    
    // Show video preview
    const reader = new FileReader();
    reader.onload = (event) => {
      const videoElement = document.getElementById('videoPreviewElement');
      videoElement.src = event.target.result;
      document.getElementById('videoPreview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }
});

document.getElementById('videoUploadForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const file = document.getElementById('videoFile').files[0];
  if (!file) {
    Swal.fire({
      icon: 'warning',
      title: 'No File Selected',
      text: 'Please select a video file'
    });
    return;
  }

  const uploadBtn = document.getElementById('videoUploadBtn');
  const originalText = uploadBtn.innerHTML;
  uploadBtn.disabled = true;
  uploadBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i><span>Uploading...</span>';

  try {
    const formData = new FormData();
    formData.append('video', file);

    const res = await fetch('/backend/upload_video.php', {
      method: 'POST',
      body: formData
    });

    const data = await res.json();
    if (data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Video uploaded successfully',
        timer: 1500
      });

      // Store video URL in a separate section 'video-guide-url'
      const checkRes = await fetch(`${API_BASE}?action=get-by-slug&slug=video-guide-url`);
      const checkData = await checkRes.json();
      
      const videoAction = checkData.success ? 'update' : 'create';
      const videoPayload = {
        title: 'Video Guide URL',
        slug: 'video-guide-url',
        content: data.url,
        status: 'published'
      };
      
      if (checkData.success) {
        videoPayload.id = checkData.data.id;
      }

      const updateRes = await fetch(`${API_BASE}?action=${videoAction}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(videoPayload)
      });
      
      const updateData = await updateRes.json();
      if (updateData.success) {
        loadSections();
      }

      closeVideoUploadModal();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Upload Failed',
        text: data.error || 'Failed to upload video'
      });
    }
  } catch (e) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'An error occurred during upload'
    });
    console.error(e);
  } finally {
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = originalText;
  }
});

function escapeHtml(text){
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// ==================== INIT ====================
document.getElementById('addPageBtn').addEventListener('click', showAddPageModal);

// Auto-initialize sections on first load
async function autoInitSections(){
  for(const [slug, defaults] of Object.entries(DEFAULTS)){
    try {
      const res = await fetch(`${API_BASE}?action=get-by-slug&slug=${slug}`);
      const data = await res.json();
      
      if(!data.success || !data.data){
        // Create if doesn't exist
        const createRes = await fetch(`${API_BASE}?action=create`, {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({
            title: defaults.title,
            slug: slug,
            content: defaults.content,
            status: 'published'
          })
        });
      }
    } catch(e){
      console.error('Error auto-init section:', slug, e);
    }
  }
  loadSections();
  loadPages();
}

// Run auto-init on page load
autoInitSections();
setInterval(loadPages, 30000); // Refresh every 30 seconds
</script>
