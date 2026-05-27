This folder is intended to hold the Sneat template static assets (css, js, img, vendor).

Steps to complete integration:
1. Copy the contents of your attached Sneat html folder into this folder, preserving subfolders (css, js, img, vendor).
2. Make sure the main CSS and JS files are available at:
   - public/sneat/assets/css/style.css
   - public/sneat/assets/js/main.js
3. Adjust paths in resources/views/layouts/sneat.blade.php if your template uses different filenames.
4. Convert any HTML pages you want to Laravel Blade views under resources/views/sneat/ and extend the layout.

If you want, run the provided copy scripts in the project root to bulk copy assets (adjust source path first).
