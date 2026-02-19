<footer class="bg-neutral-50 border-t mt-10">
  <div class="max-w-7xl mx-auto px-6 py-6">

    <!-- Main -->
    <div class="bg-white rounded-xl border border-neutral-200/70 shadow-sm
                px-6 py-5 md:px-8 md:py-6
                grid md:grid-cols-12 gap-6 items-center">

      <!-- Brand -->
      <div class="md:col-span-7 flex gap-3 items-start">
        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500
                    text-white flex items-center justify-center
                    font-bold text-xs shadow-sm shrink-0">
          PF
        </div>

        <div>
          <h3 class="text-sm font-semibold text-emerald-800 leading-tight">
            Phil-First HR & Services
          </h3>

          <p class="mt-1 text-xs text-neutral-600 max-w-xl leading-relaxed">
            Connecting professionals with meaningful opportunities and helping
            organizations build high-performing teams.
          </p>

          <div class="mt-2 text-xs text-neutral-700 flex flex-wrap gap-x-3 gap-y-1">
            <span>
              Email:
              <a href="mailto:info@phil-first.example"
                 class="text-emerald-700 hover:underline">
                info@phil-first.example
              </a>
            </span>
            <span class="text-neutral-300">•</span>
            <span>Phone: (02) 1234-5678</span>
          </div>
        </div>
      </div>

      <!-- Social -->
      <div class="md:col-span-5 flex md:justify-end items-center gap-2">
        <span class="hidden md:block text-[11px] uppercase tracking-wide text-neutral-500 mr-2">
          Connect
        </span>

        <a href="#" aria-label="Facebook"
           class="w-8 h-8 rounded-full bg-white border border-neutral-200
                  hover:border-emerald-400 hover:bg-emerald-50
                  transition flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M22 12a10 10 0 10-11.5 9.9v-7h-2.5v-2.9h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.2c-1.2 0-1.6.7-1.6 1.6v1.9h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/>
          </svg>
        </a>

        <a href="#" aria-label="WhatsApp"
           class="w-8 h-8 rounded-full bg-white border border-neutral-200
                  hover:border-emerald-400 hover:bg-emerald-50
                  transition flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20.5 3.5A11.9 11.9 0 0012 .3a11.8 11.8 0 00-10.5 17l-1.4 5.5 5.7-1.5a11.8 11.8 0 005.7 1.5h.1c6.6 0 12-5.3 12-11.9 0-3.2-1.2-6.2-3.4-8.4z"/>
          </svg>
        </a>

        <a href="#" aria-label="Zoom"
           class="w-8 h-8 rounded-full bg-white border border-neutral-200
                  hover:border-emerald-400 hover:bg-emerald-50
                  transition flex items-center justify-center">
          <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M21 6.5A2.5 2.5 0 0018.5 4h-13A2.5 2.5 0 003 6.5v11A2.5 2.5 0 005.5 20h13a2.5 2.5 0 002.5-2.5z"/>
          </svg>
        </a>
      </div>
    </div>

    <!-- Bottom -->
    <div class="mt-3 pt-3 border-t border-neutral-200
                flex flex-col md:flex-row items-center justify-between gap-2
                text-[11px] text-neutral-400">
      <span>
        © Phil-First HR & Services <span id="year-apps"></span>
      </span>
      <div class="flex gap-3">
        <a href="#" class="hover:text-emerald-600 transition">Privacy</a>
        <a href="#" class="hover:text-emerald-600 transition">Terms</a>
      </div>
    </div>

  </div>
</footer>
<!-- Notifications: using inline fallback to avoid hosting-path 404s -->
<!-- Inline fallback for notifications (used if external file isn't found) -->
<script>
// Load `frontend/script.js` from a candidate list so header behavior (About dropdown) works from any page.
(function(){ if(window.__pf_script_loaded) return; const candidates=['/frontend/script.js','script.js','./script.js','../script.js']; let i=0; function tryLoad(){ if(i>=candidates.length) return; const s=document.createElement('script'); s.src=candidates[i]; s.onload=function(){ window.__pf_script_loaded=true; }; s.onerror=function(){ i++; tryLoad(); }; document.head.appendChild(s); } tryLoad(); })();
</script>
<script>
if(!window._notificationsInlineProvided){
  window._notificationsInlineProvided = true;
  (function(){
    const notifDropdown = () => document.getElementById('notifDropdown');
    const notifBadges = () => document.querySelectorAll('.notif-badge');
    const notifList = () => document.getElementById('notifList');
    function updateNotifCount(count){ const badges = notifBadges(); badges.forEach(b=>{ if(count>0){ b.textContent = count; b.classList.remove('hidden'); } else { b.textContent = ''; b.classList.add('hidden'); } }); }
    async function loadNotifications(){
      try{
        const listEl = notifList(); if(!listEl) return;

        // If server-rendered notifications are present (notification_widget.php output), use them.
        const firstChildText = (listEl.firstElementChild && listEl.firstElementChild.textContent || '').toLowerCase();
        const hasServerItems = listEl.children.length > 0 && !firstChildText.includes('loading') && !firstChildText.includes('no notifications');
        const dropdownOpen = notifDropdown() && !notifDropdown().classList.contains('hidden');
        if(hasServerItems){
          const unreadEls = Array.from(listEl.querySelectorAll('.bg-emerald-50'));
          const unread = unreadEls.length;
          updateNotifCount(unread);

          // Attach click handlers to server-rendered items so they mark-as-read before navigating
          unreadEls.forEach(el => {
            if (el._notifBound) return; // avoid double-binding
            el._notifBound = true;
            el.addEventListener('click', async (ev) => {
              try{
                ev.preventDefault();
                const notifId = el.getAttribute('data-notif-id') || null;
                const appId = el.getAttribute('data-application-id') || null;
                // optimistic badge decrement
                const cur = Array.from(notifBadges()).reduce((s,b)=> s + (parseInt(b.textContent)||0), 0);
                updateNotifCount(Math.max(0, cur - 1));
                await fetch('/backend/mark_notification_read.php', {
                  method: 'POST', credentials: 'same-origin', headers: {'Content-Type':'application/json'},
                  body: JSON.stringify({ notification_id: notifId, application_id: appId })
                });
              }catch(e){ /* ignore */ }
              // navigate to application details if available
              const target = el.getAttribute('data-application-id') ? ('/welcome/my-applications?application_id=' + encodeURIComponent(el.getAttribute('data-application-id'))) : '#';
              if(target) window.location.href = target;
            });
          });

          // If dropdown is not open, we can stop here (badge updated and handlers bound).
          // If dropdown IS open, continue to fetch fresh data below so the body list gets updated too.
          if(!dropdownOpen) return;
        }

        // Fallback: attempt to fetch from backend endpoints (try relative paths)
        const candidatePaths = ['../backend/get_notifications.php','backend/get_notifications.php','/backend/get_notifications.php'];
        let j = null; let usedPath = null;
        for(const p of candidatePaths){
          try{
            const resTry = await fetch(p, { credentials: 'same-origin' });
            const text = await resTry.text();
            try{ const parsed = JSON.parse(text); if(parsed && parsed.success){ j = parsed; usedPath = p; break; } }catch(pe){}
          }catch(err){}
        }
        if(!j){ listEl.innerHTML = `<div class="p-4 text-sm text-neutral-500 text-center">No notifications</div>`; updateNotifCount(0); return; }

        listEl.innerHTML = '';
        const items = j.data.filter(a=> (a.status||'').toString().toLowerCase() !== 'pending');
        if(!items.length){ listEl.innerHTML = '<div class="p-4 text-sm text-neutral-500 text-center">No notifications</div>'; updateNotifCount(0); return; }

        let unread=0; items.forEach(a=>{
          const isRead = (a.is_read === 1 || a.is_read === '1' || a.is_read === true);
          if(!isRead) unread++;
          const title = (a.resolved_job_title || a.message || ('Application ' + (a.application_id || a.id || '')));
          const when = a.created_at || '';
          const body = a.message || '';
          const link = a.application_id ? ('/welcome/my-applications?application_id='+encodeURIComponent(a.application_id)) : (a.id ? ('/welcome/my-applications?application_id='+encodeURIComponent(a.id)) : '#');
          const aEl = document.createElement('a');
          aEl.className = 'block p-4 border-b hover:bg-neutral-50 ' + (isRead ? '' : 'bg-emerald-50');
          aEl.href = link;
          aEl.innerHTML = '<p class="text-sm font-medium text-neutral-800">'+(escapeHtml(title))+' — '+(escapeHtml(a.display_status||a.status||''))+'</p><p class="text-xs text-neutral-600 mt-1">'+(escapeHtml(body))+'</p><p class="text-[11px] text-neutral-400 mt-1">'+(escapeHtml(when))+'</p>';
          if(!isRead && (a.id||a.application_id)){
            const notifId = a.id || null;
            aEl.addEventListener('click', async function(ev){ try{ ev.preventDefault(); const cur = Array.from(notifBadges()).reduce((s,b)=> s + (parseInt(b.textContent)||0), 0); updateNotifCount(Math.max(0,cur-1)); await fetch((usedPath||'/backend/mark_notification_read.php'),{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({notification_id:notifId, application_id: a.application_id || a.id})}); }catch(e){} window.location.href = this.href; });
          }
          listEl.appendChild(aEl);
        });
        updateNotifCount(unread);
      }catch(e){ const listEl = notifList(); if(!listEl) return; listEl.innerHTML = '<div class="p-4 text-sm text-red-600 text-center">Failed to load notifications</div>'; }
    }
    function init(){ document.querySelectorAll('.notif-bell').forEach(b=>b.addEventListener('click', ()=>{ const dd = notifDropdown(); if(dd) dd.classList.toggle('hidden'); loadNotifications(); })); const mb = document.getElementById('notifBtnMobile'); if(mb) mb.addEventListener('click', ()=>{ const dd = notifDropdown(); if(dd) dd.classList.toggle('hidden'); loadNotifications(); }); document.addEventListener('click', (e)=>{ if(!e.target.closest('.notif-bell') && !e.target.closest('#notifDropdown')){ const dd = notifDropdown(); if(dd) dd.classList.add('hidden'); } }); Array.from(notifBadges()).forEach(b=>{ b.classList.add('hidden'); b.textContent = ''; }); loadNotifications(); }
    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', init); else init(); window.markAllRead = function(){
      console.debug('[notifications-inline] markAllRead called');
      // Immediate UI update: hide badges and clear unread highlights
      try{ updateNotifCount(0); const listEl2 = notifList(); if(listEl2){ Array.from(listEl2.querySelectorAll('.bg-emerald-50')).forEach(it=> it.classList.remove('bg-emerald-50')); } }catch(e){}
      // Fire-and-forget server request to mark all as read
      fetch('/backend/mark_notifications_read.php',{method:'POST',credentials:'same-origin'})
        .then(async (res)=>{
          console.debug('[notifications-inline] markAllRead status', res.status);
          let j = null; try{ j = await res.json(); }catch(e){ console.error('[notifications-inline] parse json', e); }
          if(res.ok && j && j.success){ console.debug('[notifications-inline] markAllRead done'); }
          else { console.error('[notifications-inline] markAllRead failed', res.status, j); }
        }).catch(e=>{ console.error('[notifications-inline] markAllRead error', e); });
    };

    // Start background polling for live notifications (no page refresh needed)
    (function(){
      let _notifPollIntervalMs = 15000;
      let _notifPollTimer = null;
      async function pollOnce(){
        try{
          const candidatePaths = ['../backend/get_notifications.php','backend/get_notifications.php','/backend/get_notifications.php'];
          let resData = null;
          for(const p of candidatePaths){
            try{
              const r = await fetch(p, { credentials: 'same-origin' });
              if(!r.ok) continue;
              const txt = await r.text();
              try{ const j = JSON.parse(txt); if(j && j.success){ resData = j; break; } }catch(e){}
            }catch(e){}
          }
          if(!resData) return;
          const items = Array.isArray(resData.data) ? resData.data : [];
          const unread = items.filter(a => !(a.is_read === 1 || a.is_read === '1' || a.is_read === true)).length;
          try{ updateNotifCount(unread); }catch(e){}
          const dd = document.getElementById('notifDropdown');
          if(dd && !dd.classList.contains('hidden')){
            // If dropdown is open, refresh full list
            try{ await loadNotifications(); }catch(e){}
          }
        }catch(e){}
      }
      function start(){ if(_notifPollTimer) return; pollOnce(); _notifPollTimer = setInterval(pollOnce, _notifPollIntervalMs); }
      function stop(){ if(!_notifPollTimer) return; clearInterval(_notifPollTimer); _notifPollTimer = null; }
      document.addEventListener('visibilitychange', ()=>{ if(document.hidden) stop(); else start(); });
      start();
    })();
  })();
}
</script>
