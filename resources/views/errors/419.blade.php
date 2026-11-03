<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challora — Page Expired</title>
    <link rel="icon" type="image/png" href="/web-icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=Space+Grotesk:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #070707;
            color: #E5E5DF;
            font-family: 'Newsreader', Georgia, serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .logo-img {
            width: 56px;
            height: 56px;
            object-fit: contain;
            margin-bottom: 40px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,77,48,0.1);
            border: 1px solid #FF4D30;
            color: #FF4D30;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 6px 14px;
            border-radius: 0;
            margin-bottom: 32px;
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1;
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        p {
            font-family: 'Newsreader', Georgia, serif;
            font-size: 1.15rem;
            color: #9c9c9c;
            text-align: center;
            max-width: 480px;
            line-height: 1.65;
            margin-bottom: 48px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #ECECE7;
            color: #000;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 15px 30px;
            text-decoration: none;
            border: 1px solid #ECECE7;
            transition: all 0.2s;
            margin: 0 8px 16px;
        }

        .btn:hover {
            background: #FF4D30;
            border-color: #FF4D30;
            color: #fff;
        }

        .btn-secondary {
            background: transparent;
            color: #ECECE7;
            border-color: #333;
        }

        .btn-secondary:hover {
            background: #171717;
            color: #fff;
            border-color: #777;
        }

        .wordmark {
            font-family: 'Space Grotesk', sans-serif;
            position: fixed;
            bottom: -0.1em;
            left: 0;
            width: 100%;
            font-size: 18vw;
            font-weight: 800;
            text-align: center;
            letter-spacing: -0.04em;
            color: #121212;
            line-height: 1;
            pointer-events: none;
            user-select: none;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <img src="/web-icon.png" alt="Challora" class="logo-img">

    <div class="status-pill">
        419 — Page Expired
    </div>

    <h1>Session expired.</h1>

    <p>
        Your session has timed out or the page token has expired.
        This usually happens after a long period of inactivity or after a server redeploy.
    </p>

    <div style="display:flex;flex-wrap:wrap;justify-content:center;">
        <a href="javascript:history.back()" class="btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Go Back
        </a>
        <a href="/" class="btn btn-secondary">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
            </svg>
            Homepage
        </a>
    </div>

    <div class="wordmark">CHALLORA</div>
</body>
</html>
