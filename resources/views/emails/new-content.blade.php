<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex, nofollow">
<style>
    body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 40px auto; background: white; border-radius: 4px; overflow: hidden; }
    .header { background: #001e3e; padding: 24px 32px; }
    .body { padding: 32px; }
    .badge { display: inline-block; background: #0071c5; color: white; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; margin-bottom: 16px; }
    h1 { color: #001e3e; font-size: 22px; font-weight: 400; margin: 0 0 12px; }
    p { color: #555; font-size: 14px; line-height: 1.6; margin: 0 0 24px; }
    .btn { display: inline-block; background: #0071c5; color: white; text-decoration: none; padding: 12px 28px; font-size: 14px; font-weight: 700; border-radius: 2px; }
    .footer { padding: 20px 32px; background: #f9f9fb; border-top: 1px solid #e5e7eb; color: #9ca3af; font-size: 12px; }
</style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <span style="color:white;font-size:18px;font-weight:800;font-style:italic;">Bhaiya Asset Library</span>
            <span style="color:rgba(255,255,255,0.7);font-size:11px;display:block;letter-spacing:2px;text-transform:uppercase;">partner marketing studio</span>
        </div>
        <div class="body">
            <span class="badge">New {{ $type }}</span>
            <h1>{{ $title }}</h1>
            <p>{{ $body }}</p>
            <a href="{{ $url }}" class="btn">View {{ $type }}</a>
        </div>
        <div class="footer">
            You received this email because you are a registered partner. &copy; {{ date('Y') }} Bhaiya Asset Library.
        </div>
    </div>
</body>
</html>
