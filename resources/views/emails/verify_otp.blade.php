<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Email Anda</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f172a;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
        }
        .content h2 {
            font-size: 20px;
            color: #0f172a;
            margin-top: 0;
            font-weight: 600;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
            color: #475569;
        }
        .otp-container {
            background-color: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #2563eb;
            margin: 0;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dibitech</h1>
        </div>
        <div class="content">
            <h2>Halo, {{ $name }}!</h2>
            <p>Terima kasih telah mendaftar di Dibitech. Untuk menyelesaikan proses pendaftaran dan memverifikasi alamat email Anda, silakan gunakan kode One-Time Password (OTP) di bawah ini:</p>
            
            <div class="otp-container">
                <p class="otp-code">{{ $otp }}</p>
            </div>
            
            <p>Kode ini hanya berlaku selama <strong>10 menit</strong>. Jangan pernah membagikan kode ini kepada siapa pun, termasuk pihak yang mengatasnamakan Dibitech.</p>
            <p>Jika Anda tidak merasa mendaftar akun di Dibitech, Anda dapat mengabaikan atau menghapus email ini.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Dibitech. Hak cipta dilindungi undang-undang.</p>
            <p>Ini adalah email otomatis, mohon tidak membalas ke alamat email ini.</p>
        </div>
    </div>
</body>
</html>
