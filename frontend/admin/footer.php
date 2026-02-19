  </main>
</div> <!-- end flex layout -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Set page title dynamically based on file name
  (function(){
    try{
      const path = location.pathname.split('/').pop() || 'dashboard.php';
      const title = path.replace('.php','').replace('_',' ');
      const el = document.getElementById('page-title');
      if(el) el.innerText = title.charAt(0).toUpperCase() + title.slice(1);
    }catch(e){}
  })();

  // Chart initialization
  (function(){
    const canvas = document.getElementById('applicationsChart');
    if(!canvas) return;
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: Array.from({length:30}, (_,i)=>i+1),
        datasets: [{
          data: Array.from({length:30},()=>Math.floor(Math.random()*40)+10),
          borderColor:'#198754',
          backgroundColor:'rgba(25,135,84,.08)',
          fill:true,
          tension:.35
        }]
      },
      options:{
        plugins:{ legend:{display:false} },
        scales:{ y:{ grid:{color:'#f3f4f6'} }, x:{ grid:{display:false} } }
      }
    });
  })();
</script>

  <!-- Notifications: using inline fallback to avoid hosting-path 404s -->
  <!-- Inline fallback for notifications (used if external file isn't found) -->
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
          const res = await fetch('/backend/get_notifications.php',{credentials:'same-origin'});
          const j = await res.json();
          const listEl = notifList(); if(!listEl) return; listEl.innerHTML = '';
          if(!j.success || !Array.isArray(j.data) || !j.data.length){ listEl.innerHTML = '<div class="p-4 text-sm text-neutral-500 text-center">No notifications</div>'; updateNotifCount(0); return; }
          const items = j.data.filter(a=> (a.status||'').toString().toLowerCase() !== 'pending'); if(!items.length){ listEl.innerHTML = '<div class="p-4 text-sm text-neutral-500 text-center">No notifications</div>'; updateNotifCount(0); return; }
          let unread=0; items.forEach(a=>{
            const isRead = (a.is_read === 1 || a.is_read === '1' || a.is_read === true);
            if(!isRead) unread++;
            const title = (a.message && a.message.toString()) || ('Application ' + (a.application_id || a.id || ''));
            const when = a.created_at || '';
            const body = a.message || '';
            const link = a.application_id ? ('my_applications.php?application_id='+encodeURIComponent(a.application_id)) : (a.id ? ('my_applications.php?application_id='+encodeURIComponent(a.id)) : '#');
            const aEl = document.createElement('a'); aEl.className = 'block p-4 border-b hover:bg-neutral-50 ' + (isRead ? '' : 'bg-emerald-50'); aEl.href = link; aEl.innerHTML = '<p class="text-sm font-medium text-neutral-800">'+(escapeHtml(title))+' — '+(escapeHtml(a.status||''))+'</p><p class="text-xs text-neutral-600 mt-1">'+(escapeHtml(body))+'</p><p class="text-[11px] text-neutral-400 mt-1">'+when+'</p>';
            if(!isRead && (a.id||a.application_id)){
              const notifId = a.id || null; aEl.addEventListener('click', async function(ev){ try{ ev.preventDefault(); const cur = Array.from(notifBadges()).reduce((s,b)=> s + (parseInt(b.textContent)||0), 0); updateNotifCount(Math.max(0,cur-1)); await fetch('/backend/mark_notification_read.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify({notification_id:notifId, application_id: a.application_id || a.id})}); }catch(e){} window.location.href = this.href; });
            }
            listEl.appendChild(aEl);
          });
          updateNotifCount(unread);
        }catch(e){ const listEl = notifList(); if(!listEl) return; listEl.innerHTML = '<div class="p-4 text-sm text-red-600 text-center">Failed to load notifications</div>'; }
      }
      function init(){ document.querySelectorAll('.notif-bell').forEach(b=>b.addEventListener('click', ()=>{ const dd = notifDropdown(); if(dd) dd.classList.toggle('hidden'); loadNotifications(); })); const mb = document.getElementById('notifBtnMobile'); if(mb) mb.addEventListener('click', ()=>{ const dd = notifDropdown(); if(dd) dd.classList.toggle('hidden'); loadNotifications(); }); document.addEventListener('click', (e)=>{ if(!e.target.closest('.notif-bell') && !e.target.closest('#notifDropdown')){ const dd = notifDropdown(); if(dd) dd.classList.add('hidden'); } }); Array.from(notifBadges()).forEach(b=>{ b.classList.add('hidden'); b.textContent = ''; }); loadNotifications(); }
      if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', init); else init(); window.markAllRead = async function(){
        console.debug('[notifications-inline-admin] markAllRead called');
        try{
          const res = await fetch('/backend/mark_notifications_read.php',{method:'POST',credentials:'same-origin'});
          console.debug('[notifications-inline-admin] markAllRead status', res.status);
          let j = null; try{ j = await res.json(); }catch(e){ console.error('[notifications-inline-admin] parse json', e); }
          if(res.ok && j && j.success){ updateNotifCount(0); await loadNotifications(); console.debug('[notifications-inline-admin] markAllRead done'); }
          else { console.error('[notifications-inline-admin] markAllRead failed', res.status, j); }
        }catch(e){ console.error('[notifications-inline-admin] markAllRead error', e); }
      };
    })();
  }
  </script>
</body>
</html>
