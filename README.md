# Weather Monitoring System
PHP + MySQL dashboard with dynamic background video based on time (and optional rain).

## Structure
- /web -> index.php, style.css, script.js
- /videos -> morning.mp4, maghrib.mp4, night.mp4, midnight.mp4
- /data -> (optional)

## Setup
1) Copy `web/ConnectDB.sample.php` to `web/ConnectDB.php` and fill DB creds.
2) Run: `php -S localhost:8000` inside `web/`.
