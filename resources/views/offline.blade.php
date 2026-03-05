<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>ইন্টারনেট কানেকশন নেই — জিনিয়াস কিডস</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="theme-color" content="#FE5D37">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Lobster+Two:wght@700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Heebo', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            color: #333;
        }

        .offline-container {
            max-width: 500px;
            background: #fff;
            border-radius: 20px;
            padding: 50px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .wifi-icon {
            font-size: 80px;
            color: #FE5D37;
            margin-bottom: 20px;
            display: block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.95); }
        }

        h1 {
            font-family: 'Lobster Two', cursive;
            font-size: 2.5rem;
            color: #FE5D37;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 1.4rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 15px;
        }

        p {
            font-size: 1rem;
            color: #777;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 14px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #FE5D37;
            color: #fff;
        }

        .btn-primary:hover {
            background: #e84d2a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(254, 93, 55, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #FE5D37;
            border: 2px solid #FE5D37;
            margin-left: 10px;
        }

        .btn-outline:hover {
            background: #FE5D37;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Inline SVG WiFi-Off icon */
        .wifi-svg {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <!-- Inline SVG so no external icon library needed -->
        <svg class="wifi-svg" xmlns="http://www.w3.org/2000/svg" fill="#FE5D37" viewBox="0 0 16 16">
            <path d="M10.706 3.294A12.6 12.6 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.52.52 0 0 0 .668.05A11.45 11.45 0 0 1 8 4c.63 0 1.249.05 1.852.148l.854-.854zM8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065 8.45 8.45 0 0 1 3.51-1.27L8 6zm2.596 1.404.785-.785q.603.268 1.167.59a.52.52 0 0 1 .056.672.48.48 0 0 1-.635.074 8.5 8.5 0 0 0-1.373-.551M8 10c-.862 0-1.68.187-2.417.518A.485.485 0 0 0 5.29 11.3a.52.52 0 0 0 .637.069A4.5 4.5 0 0 1 8 11c.348 0 .687.04 1.013.116l.97-.97A5.5 5.5 0 0 0 8 10m4.905-4.905.747-.747q.376.282.727.59a.48.48 0 0 1 .034.725.52.52 0 0 1-.653.046 12 12 0 0 0-.855-.614M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
            <path d="M13.646 1.146a.5.5 0 0 1 .708.708l-12 12a.5.5 0 0 1-.708-.708z"/>
        </svg>

        <h1>উফ্!</h1>
        <h2>ইন্টারনেট কানেকশন নেই</h2>
        <p>আপনি বর্তমানে অফলাইনে আছেন। লাইভ কুইজ ও উত্তর দেখতে অ্যাপটির ইন্টারনেট সংযোগ প্রয়োজন।<br>অনুগ্রহ করে আপনার ডাটা অথবা ওয়াই-ফাই কানেকশন চেক করুন।</p>

        <div class="btn-group">
            <button onclick="window.location.reload()" class="btn btn-primary">আবার চেষ্টা করুন</button>
            <a href="/" class="btn btn-outline">হোম পেজ</a>
        </div>
    </div>
</body>
</html>
