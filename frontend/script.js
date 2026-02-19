// Basic client-side interactions: search, apply buttons, contact form
document.addEventListener('DOMContentLoaded', () => {
  const searchForm = document.getElementById('searchForm');
  const keywordInput = document.getElementById('keyword');
  const locationInput = document.getElementById('location');

  // Smooth reveal for elements with .reveal
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const kw = (keywordInput && keywordInput.value || '').toLowerCase();
      const loc = (locationInput && locationInput.value || '').toLowerCase();
      const cards = Array.from(document.querySelectorAll('.job-card'));
      let matched = 0;
      cards.forEach(card => {
        const text = card.innerText.toLowerCase();
        if ((kw === '' || text.includes(kw)) && (loc === '' || text.includes(loc))) {
          card.style.display = '';
          matched++;
        } else {
          card.style.display = 'none';
        }
      });
      if (matched === 0) {
        const jobsContainer = document.getElementById('jobs');
        if (jobsContainer) {
          const info = document.createElement('div');
          info.className = 'p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded';
          info.innerText = 'No jobs found. Try different keywords or check back later.';
          jobsContainer.prepend(info);
          setTimeout(() => info.remove(), 3500);
        }
      }
    });
  }

  // Apply button opens contact section and prefills message
  const applyBtns = Array.from(document.querySelectorAll('.apply-btn'));
  if (applyBtns.length) {
    applyBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        const card = e.target.closest('.job-card');
        const title = card ? (card.querySelector('h4') ? card.querySelector('h4').innerText : '') : '';
        window.location.hash = '#contact';
        const msg = document.querySelector('textarea[name="message"]');
        if (msg) msg.value = `Applying for: ${title}\n\n(Write your details here...)`;
        const name = document.querySelector('input[name="name"]');
        if (name) name.focus();
      });
    });
  }

  // Contact form submit (use absolute path to backend folder)
  const contactForm = document.getElementById('contactForm');
  const contactMessage = document.getElementById('contactMessage');
  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (contactMessage) contactMessage.innerHTML = '';
      const formData = new FormData(contactForm);
      const payload = {};
      formData.forEach((v, k) => payload[k] = v);

      try {
        const res = await fetch('/backend/contact.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const json = await res.json();
        if (json.success) {
          if (contactMessage) contactMessage.innerHTML = '<div class="p-3 bg-green-50 border border-green-200 text-green-800 rounded">Message sent. Our recruiter will contact you soon.</div>';
          contactForm.reset();
        } else {
          if (contactMessage) contactMessage.innerHTML = `<div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded">Error: ${json.error || 'Unable to send message'}</div>`;
        }
      } catch (err) {
        if (contactMessage) contactMessage.innerHTML = '<div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded">Network error. Try again later.</div>';
      }
    });
  }

    // About dropdown toggle (desktop)
    const aboutToggle = document.getElementById('aboutToggle');
    const aboutDropdown = document.getElementById('aboutDropdown');
    if (aboutToggle && aboutDropdown) {
      aboutToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        aboutDropdown.classList.toggle('hidden');
        aboutToggle.setAttribute('aria-expanded', String(!aboutDropdown.classList.contains('hidden')));
      });

      // Close when clicking outside
      document.addEventListener('click', (ev) => {
        if (!aboutDropdown.classList.contains('hidden')) {
          if (!aboutDropdown.contains(ev.target) && ev.target !== aboutToggle) {
            aboutDropdown.classList.add('hidden');
          }
        }
      });

      // Close on Escape
      document.addEventListener('keydown', (ev) => {
        if (ev.key === 'Escape') aboutDropdown.classList.add('hidden');
      });
    }

    // Mobile nav toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileNav = document.getElementById('mobileNav');
    const mobileAboutToggle = document.getElementById('mobileAboutToggle');
    const mobileAboutDropdown = document.getElementById('mobileAboutDropdown');

    if (mobileToggle && mobileNav) {
      mobileToggle.addEventListener('click', () => {
        mobileNav.classList.toggle('hidden');
        mobileToggle.setAttribute('aria-expanded', String(!mobileNav.classList.contains('hidden')));
      });

      // Close mobile nav when clicking a link
      mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileNav.classList.add('hidden')));
    }

    if (mobileAboutToggle && mobileAboutDropdown) {
      mobileAboutToggle.addEventListener('click', () => mobileAboutDropdown.classList.toggle('hidden'));
    }

    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', (e) => {
        const href = a.getAttribute('href');
        if (href && href.startsWith('#') && href.length > 1) {
          const target = document.querySelector(href);
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        }
      });
    });

    // Utilities to parse backend response robustly
    function getRowsFromResponse(j){
      if(!j) return [];
      if(Array.isArray(j.data)) return j.data;
      if(Array.isArray(j.rows)) return j.rows;
      if(Array.isArray(j.result)) return j.result;
      if(j.data && typeof j.data === 'object') return Object.values(j.data).filter(x=>x);
      return [];
    }

    function escapeHtml(s){ return (s||'').toString().replace(/[&"'<>]/g,c=>({'&':'&amp;','"':'&quot;','\'':'&#39;','<':'&lt;','>':'&gt;'})[c]); }
    function formatDate(s){ const d = new Date(s); return isNaN(d.getTime()) ? '' : d.toLocaleString(); }

    // Notifications removed
});
