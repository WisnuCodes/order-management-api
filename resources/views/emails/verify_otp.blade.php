<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Dibitech</title>
</head>
<body style="background-color: #f4f4f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; margin: 0; padding: 40px 20px; color: #09090b; line-height: 1.6;">
    
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e4e4e7; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);">
        <!-- Header -->
        <tr>
            <td style="background-color: #09090b; padding: 32px 40px; text-align: center;">
                <!-- Menggunakan text icon untuk email agar aman di semua klien -->
                <div style="display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); width: 48px; height: 48px; border-radius: 12px; line-height: 48px; font-size: 24px; color: white; font-weight: bold; margin-bottom: 12px;">D</div>
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.02em;">Dibitech</h1>
            </td>
        </tr>
        
        <!-- Body Content -->
        <tr>
            <td style="padding: 48px 40px;">
                <h2 style="font-size: 20px; color: #09090b; margin-top: 0; margin-bottom: 24px; font-weight: 700;">Halo, {{ $name }} 👋</h2>
                <p style="font-size: 16px; color: #52525b; margin-bottom: 32px; line-height: 1.6;">
                    Selamat datang di <strong>Dibitech</strong>! Keamanan akun Anda adalah prioritas utama kami. Untuk memverifikasi alamat email dan melanjutkan pendaftaran, silakan gunakan kode OTP eksklusif Anda di bawah ini:
                </p>
                
                <!-- OTP Box -->
                <div style="background-color: #fafafa; border: 1px solid #e4e4e7; border-radius: 12px; padding: 32px 24px; text-align: center; margin: 32px 0;">
                    <p style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: #71717a; margin-top: 0; margin-bottom: 12px; font-weight: 600;">KODE VERIFIKASI ANDA</p>
                    <p style="font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #09090b; margin: 0; font-family: monospace;">
                        {{ $otp }}
                    </p>
                </div>
                
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td style="padding-bottom: 16px;">
                            <p style="font-size: 14px; color: #52525b; margin: 0; display: flex; align-items: center;">
                                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #f59e0b; margin-right: 12px;"></span>
                                Kode ini akan kedaluwarsa dalam <strong>10 menit</strong>.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="font-size: 14px; color: #52525b; margin: 0; display: flex; align-items: center;">
                                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #ef4444; margin-right: 12px;"></span>
                                Jangan bagikan kode ini kepada pihak mana pun demi keamanan akun Anda.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- Footer -->
        <tr>
            <td style="background-color: #fafafa; padding: 32px 40px; text-align: center; border-top: 1px solid #e4e4e7;">
                <p style="font-size: 13px; color: #71717a; margin: 0 0 12px 0;">
                    Jika Anda tidak membuat permintaan pendaftaran ini, harap abaikan email ini dengan aman.
                </p>
                <p style="font-size: 12px; color: #a1a1aa; margin: 0;">
                    &copy; {{ date('Y') }} Dibitech Inc. Seluruh hak cipta dilindungi.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
