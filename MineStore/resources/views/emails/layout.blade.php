<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MineStore')</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #1b1b18; margin: 0; padding: 0; background: #f5f5f5; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 20px; }
        .content { background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #5baa47; }
        .header h1 { color: #5baa47; font-size: 1.5rem; margin: 0; }
        .footer { text-align: center; margin-top: 24px; padding-top: 16px; font-size: 0.75rem; color: #706f6c; }
        a { color: #5baa47; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { display: inline-block; padding: 12px 24px; background: #5baa47; color: white !important; border-radius: 4px; text-decoration: none; font-weight: bold; margin: 16px 0; }
        .btn:hover { background: #4a9939; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="content">
            <div class="header">
                <h1>MineStore</h1>
            </div>
            @yield('content')
            <div class="footer">
                <p>MineStore · Site fictif</p>
            </div>
        </div>
    </div>
</body>
</html>
