<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest QR - Smart Mobile Kitchen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 36px 40px;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff4e0;
            color: #c98a1f;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 14px;
        }
        h1 {
            font-size: 1.3rem;
            margin: 0 0 6px;
            color: #1f2937;
        }
        p.sub {
            color: #6b7280;
            font-size: 0.85rem;
            margin: 0 0 22px;
        }
        .qr-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            display: inline-block;
            margin-bottom: 18px;
        }
        .qr-box img {
            display: block;
            width: 260px;
            height: 260px;
        }
        .note {
            font-size: 0.78rem;
            color: #6b7280;
            line-height: 1.5;
            background: #f9fafb;
            border-radius: 10px;
            padding: 12px 14px;
            text-align: left;
        }
        .note i { color: #c98a1f; margin-right: 6px; }
        .btn-row {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn {
            border: none;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print {
            background: #c98a1f;
            color: #fff;
        }
        .btn-back {
            background: #f3f4f6;
            color: #374151;
        }
        @media print {
            .btn-row, .badge { display: none; }
            body { background: #fff; }
            .card { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="card">
        <span class="badge"><i class="fa-solid fa-user-group"></i> Guest Claim QR</span>
        <h1>Smart Mobile Kitchen</h1>
        <p class="sub">Scan this code to claim a meal as an unregistered guest</p>

<div class="qr-box" style="position: relative;">
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&ecc=H&margin=8&data=<?= urlencode($guest_token) ?>" alt="Guest QR Code">
    <img src="/uploads/logo.png" alt="System Logo" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 45px; height: 45px; border-radius: 50%; background: #fff; padding: 4px; box-shadow: 0 0 0 4px #fff; object-fit: contain;">
    <div style="margin-top: 12px; font-size: 1.1rem; font-weight: 700; color: #c98a1f; letter-spacing: 0.5px;">
        GUEST QR
    </div>
</div>

        <div class="note">
            <i class="fa-solid fa-circle-info"></i>
            This is a shared, standing QR code. Every scan is logged as a new
            guest claim (Guest 1, Guest 2, Guest 3, ...) against the active
            event's cooked food stock. Print and post this at the kitchen
            counter for walk-in / unregistered beneficiaries.
        </div>

        <div class="btn-row">
            <button class="btn btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print
            </button>
            <a href="/beneficiaries" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</body>
</html>