# Frontend

This folder contains the landing page for Phil - First HR.


## Admin interface

A professional admin dashboard is provided at `frontend/admin`. It includes separate pages:

- `dashboard.php` — Overview and quick actions
- `job_management.php` — Create / edit / deactivate job postings
- `applicants.php` — View applicants, profiles and downloads
- `application_processing.php` — Shortlist, schedule interviews, update status
- `clients.php` — Manage company clients
- `users.php` — Admin & staff accounts
- `reports.php` — Charts and export options
- `content.php` — Company profile, announcements & FAQs
- `settings.php` — Email templates, categories, security

Files are self-contained PHP templates using Bootstrap 5. To preview locally run a PHP server from the `frontend` folder:

```powershell
cd 'c:\Users\ACER\Dropbox\Projects\PhilFirst\frontend'
php -S localhost:8000
```

Then open `http://localhost:8000/admin/` in your browser.


When deploying to Hostinger, place the `frontend` contents inside the public folder (for example `public_html` or the site root) and ensure the `backend` folder is uploaded alongside it (see root README).
