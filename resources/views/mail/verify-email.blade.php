<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Green Dot verification code</title>
    <style>
        body { margin: 0; padding: 0; background: #0f1117; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #e2e8f0; }
        .wrapper { max-width: 480px; margin: 48px auto; padding: 0 16px; }
        .card { background: #1a1d27; border: 1px solid #2a2d3a; border-radius: 20px; padding: 40px; }
        .logo { font-size: 20px; font-weight: 800; color: #3de27f; letter-spacing: -0.03em; margin-bottom: 32px; }
        h1 { font-size: 24px; font-weight: 800; letter-spacing: -0.03em; margin: 0 0 8px; }
        .subtitle { color: #8b92a5; font-size: 15px; line-height: 1.6; margin: 0 0 32px; }
        .code-block { background: #0f1117; border: 1px solid #2a2d3a; border-radius: 14px; padding: 24px; text-align: center; margin-bottom: 24px; }
        .code { font-family: 'SF Mono', 'Fira Code', 'Courier New', monospace; font-size: 40px; font-weight: 700; letter-spacing: 0.2em; color: #3de27f; }
        .expiry { font-size: 13px; color: #8b92a5; margin-top: 8px; }
        .divider { border: none; border-top: 1px solid #2a2d3a; margin: 24px 0; }
        .footer { font-size: 13px; color: #8b92a5; line-height: 1.6; }
        .footer a { color: #3de27f; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="logo">Green Dot</div>

            <h1>Verify your email</h1>
            <p class="subtitle">Enter the code below to activate your Green Dot account. It expires in 15 minutes.</p>

            <div class="code-block">
                <div class="code">{{ $code }}</div>
                <div class="expiry">Expires in 15 minutes</div>
            </div>

            <hr class="divider">

            <div class="footer">
                If you didn't create a Green Dot account, you can safely ignore this email.<br>
                Need help? Contact us at <a href="mailto:support@greendot.gg">support@greendot.gg</a>
            </div>
        </div>
    </div>
</body>
</html>
