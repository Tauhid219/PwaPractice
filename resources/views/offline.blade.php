<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>ইন্টারনেট সংযোগ নেই - জিনিয়াস কিডস</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="theme-color" content="#FE5D37">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Lobster+Two:wght@700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Heebo', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(circle at top, rgba(254, 93, 55, 0.14), transparent 38%),
                linear-gradient(180deg, #fff8f5 0%, #fff 48%, #fff4ee 100%);
            color: #28303f;
        }

        .offline-shell {
            width: min(100%, 560px);
            background: #fff;
            border: 1px solid rgba(254, 93, 55, 0.12);
            border-radius: 24px;
            padding: 40px 28px 28px;
            box-shadow: 0 18px 50px rgba(40, 48, 63, 0.12);
            text-align: center;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(254, 93, 55, 0.1);
            color: #d94f2c;
            font-size: 0.92rem;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .wifi-svg {
            width: 88px;
            height: 88px;
            margin: 0 auto 18px;
            display: block;
            animation: pulse 2.2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.55; transform: scale(0.96); }
        }

        h1 {
            font-family: 'Lobster Two', cursive;
            font-size: clamp(2rem, 6vw, 2.8rem);
            color: #fe5d37;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #243041;
            margin-bottom: 14px;
        }

        p {
            font-size: 1rem;
            line-height: 1.75;
            color: #5d6677;
        }

        .message {
            margin-bottom: 18px;
        }

        .note-box {
            margin: 22px 0 28px;
            padding: 16px 18px;
            border-radius: 16px;
            background: #fff7f3;
            color: #5a4a44;
            text-align: left;
            border: 1px solid rgba(254, 93, 55, 0.12);
        }

        .note-box strong {
            display: block;
            color: #28303f;
            margin-bottom: 6px;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 168px;
            padding: 14px 22px;
            border-radius: 999px;
            border: none;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #fe5d37;
            color: #fff;
            box-shadow: 0 10px 24px rgba(254, 93, 55, 0.22);
        }

        .btn-primary:hover {
            background: #eb4f28;
        }

        .btn-outline {
            background: #fff;
            color: #fe5d37;
            border: 2px solid rgba(254, 93, 55, 0.18);
        }

        .btn-outline:hover {
            border-color: #fe5d37;
            background: #fff7f3;
        }

        .reconnect-text {
            margin-top: 18px;
            font-size: 0.92rem;
            color: #7b8392;
        }

        @media (max-width: 575.98px) {
            body {
                padding: 16px;
            }

            .offline-shell {
                padding: 32px 20px 24px;
                border-radius: 20px;
            }

            .btn {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
    <main class="offline-shell">
        <div class="status-chip">
            <span>সংযোগ বিচ্ছিন্ন</span>
        </div>

        <svg class="wifi-svg" xmlns="http://www.w3.org/2000/svg" fill="#FE5D37" viewBox="0 0 16 16" aria-hidden="true">
            <path d="M10.706 3.294A12.6 12.6 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.52.52 0 0 0 .668.05A11.45 11.45 0 0 1 8 4c.63 0 1.249.05 1.852.148l.854-.854zM8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065 8.45 8.45 0 0 1 3.51-1.27L8 6zm2.596 1.404.785-.785q.603.268 1.167.59a.52.52 0 0 1 .056.672.48.48 0 0 1-.635.074 8.5 8.5 0 0 0-1.373-.551M8 10c-.862 0-1.68.187-2.417.518A.485.485 0 0 0 5.29 11.3a.52.52 0 0 0 .637.069A4.5 4.5 0 0 1 8 11c.348 0 .687.04 1.013.116l.97-.97A5.5 5.5 0 0 0 8 10m4.905-4.905.747-.747q.376.282.727.59a.48.48 0 0 1 .034.725.52.52 0 0 1-.653.046 12 12 0 0 0-.855-.614M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
            <path d="M13.646 1.146a.5.5 0 0 1 .708.708l-12 12a.5.5 0 0 1-.708-.708z"/>
        </svg>

        <h1>উফ!</h1>
        <h2>ইন্টারনেট সংযোগ পাওয়া যাচ্ছে না</h2>
        <p class="message">
            জিনিয়াস কিডস অ্যাপটি পুরোপুরি অনলাইনে কাজ করে। এখন সংযোগ না থাকায়
            কুইজ, লাইভ এক্সাম, প্রগ্রেস ও অন্যান্য পেজ খোলা যাচ্ছে না।
        </p>

        <div class="note-box">
            <strong>এখন কী করবেন</strong>
            মোবাইল ডাটা বা ওয়াই-ফাই সংযোগ চালু আছে কি না দেখুন। সংযোগ ফিরে এলে
            আবার চেষ্টা করুন, অথবা হোম পেজে ফিরে নতুন করে অ্যাপ ব্যবহার শুরু করুন।
        </div>

        <div class="btn-group">
            <button type="button" onclick="window.location.reload()" class="btn btn-primary">আবার চেষ্টা করুন</button>
            <a href="/" class="btn btn-outline">হোম পেজে ফিরুন</a>
        </div>

        <p id="reconnect-text" class="reconnect-text">সংযোগ ফিরে এলে এই পেজটি নিজে থেকেই রিফ্রেশ হবে।</p>
    </main>

    <script>
        window.addEventListener('online', () => {
            window.location.reload();
        });
    </script>
</body>
</html>
