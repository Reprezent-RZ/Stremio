<?php
// index.php
//
// Forward all requests to addon.php
// and normalize REQUEST_URI so that
// /manifest.json and /stream work correctly.

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// if the request is "/" (root), force it to "/manifest.json"
// so you can test by just opening https://yourdomain.com/
if ($uri === '/' || $uri === '/index.php') {
    $host = $_SERVER['HTTP_HOST'];
    $manifestUrl = "https://{$host}/manifest.json";
    ?>
    <!DOCTYPE html>
    <html lang="sk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hellspy Stremio Addon</title>
        <style>
            body {
                font-family: system-ui, sans-serif;
                background: radial-gradient(circle at top, #0f172a 0%, #020617 100%);
                color: #e2e8f0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
                text-align: center;
            }
            h1 {
                color: #38bdf8;
                font-size: 2rem;
                margin-bottom: 1rem;
            }
            p {
                max-width: 400px;
                line-height: 1.5;
                color: #94a3b8;
                margin-bottom: 2rem;
            }
            button {
                background: #1e293b;
                color: #e2e8f0;
                border: 1px solid #334155;
                border-radius: 0.5rem;
                padding: 0.75rem 1.5rem;
                margin: 0.5rem;
                font-size: 1rem;
                cursor: pointer;
                transition: 0.2s;
            }
            button:hover {
                background: #334155;
                transform: translateY(-2px);
            }
            #copy-info {
                margin-top: 1rem;
                color: #a3e635;
                display: none;
            }
            footer {
                position: absolute;
                bottom: 10px;
                font-size: 0.8rem;
                color: #475569;
            }
        </style>
    </head>
    <body>
        <h1>Hellspy Stremio Addon</h1>
        <p>Tento doplnok umožňuje prehrávať videá z Hellspy.to priamo v aplikácii Stremio.</p>

        <button onclick="copyLink()">📋 Kopírovať manifest link</button>
        <button onclick="installStremio()">⚡ Inštalovať do Stremio</button>

        <div id="copy-info">✅ Odkaz bol skopírovaný do schránky!</div>

        <script>
            const manifestUrl = "<?php echo $manifestUrl; ?>";
            const hostOnly = "<?php echo $host; ?>/manifest.json";

            function copyLink() {
                navigator.clipboard.writeText(manifestUrl).then(() => {
                    const info = document.getElementById('copy-info');
                    info.style.display = 'block';
                    setTimeout(() => info.style.display = 'none', 2000);
                });
            }

            function installStremio() {
                const stremioLink = "stremio://" + hostOnly;
                window.location.href = stremioLink;

                // fallback ak Stremio nereaguje
                setTimeout(() => {
                    if (!document.hidden) {
                        alert("Ak sa Stremio neotvorilo, skopíruj tento odkaz a vlož ho ručne:\n" + stremioLink);
                    }
                }, 2000);
            }
        </script>

        <footer>
            &copy; <?php echo date('Y'); ?> Hellspy Stremio Addon
        </footer>
    </body>
    </html>
    <?php
    exit;
}

require __DIR__ . '/addon.php';
