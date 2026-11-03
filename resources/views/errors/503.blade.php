<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challora — Back Shortly</title>
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

        .dot {
            width: 6px;
            height: 6px;
            background: #FF4D30;
            border-radius: 0;
            animation: blink 1.2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.2; }
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
        }

        .btn:hover {
            background: #FF4D30;
            border-color: #FF4D30;
            color: #fff;
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
        <div class="dot"></div>
        Redeploying
    </div>

    <h1>Back in a moment.</h1>

    <p>
        Challora is being updated with new improvements.
        This usually takes less than a minute.
    </p>

    <a href="/" class="btn">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
        </svg>
        Go to Homepage
    </a>

    <div class="wordmark">CHALLORA</div>
</body>
</html>
