# Phil - First Human Resources & Services Inc. (Landing)

Project scaffold: simple landing page (frontend) + backend PHP contact endpoint.

Deployment notes for Hostinger:

- Upload `frontend/` files (all files inside) to your site's public folder (e.g., `public_html`).
- Upload `backend/` folder to the same public folder so path becomes `yourdomain.com/backend/contact.php`.
- Ensure PHP is enabled on Hostinger (most shared hosting has it enabled).
- Ensure `backend/contacts.txt` is writable by the web server (set CHMOD 664 or 666 if needed).
- If your final public path differs, update the fetch path in `frontend/script.js` (line that calls `../backend/contact.php`).

Quick local test:

1. Open `frontend/index.html` in a browser to view UI (contact form will not work locally unless you run a local PHP server).
2. To test backend locally: from the workspace root run a PHP built-in server in a terminal:

```powershell
cd frontend
php -S localhost:8000
```

Then open `http://localhost:8000` and ensure `backend/contact.php` is accessible at `../backend/contact.php` relative to `index.html` (or adjust paths if testing differently).
