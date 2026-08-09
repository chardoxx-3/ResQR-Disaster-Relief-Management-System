<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print QR - <?= esc($full_name) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .qr-print {
            text-align: center;
            border: 2px solid #000;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }
        .qr-print img {
            max-width: 250px;
            height: auto;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .subheader {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .details {
            margin: 20px 0;
            text-align: left;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }
        .details div {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="qr-print">
        <div class="header">DISASTER RESPONSE PROGRAM</div>
        <div class="subheader">Family Member Identification QR Code</div>
        
        <div class="details">
            <div><strong>Household No:</strong> <?= esc($household_no) ?></div>
            <div><strong>Family Head:</strong> <?= esc($head_name) ?></div>
            <div><strong>Member Name:</strong> <?= esc($full_name) ?></div>
            <div><strong>Relationship:</strong> <?= esc($member['relation'] ?? 'N/A') ?></div>
            <div><strong>Birthdate:</strong> <?= !empty($member['birthdate']) ? date('M d, Y', strtotime($member['birthdate'])) : 'N/A' ?></div>
        </div>
        
        <?php 
        $qrData = json_encode([
            'type' => 'family_member',
            'head_id' => $resident['id'],
            'member_index' => $member_index,
            'name' => $full_name,
            'household' => $household_no,
            'token' => $qr_token
        ]);
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);
        ?>
        <img src="<?= $qrUrl ?>" alt="QR Code">
        
        <div class="footer">
            <p>This QR code is for official use only</p>
            <p>Valid for disaster response and relief distribution</p>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>