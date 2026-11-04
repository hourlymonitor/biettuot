<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BiếtTuốt') · BiếtTuốt</title>
    <style>
        :root{font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;color:#172033;background:#f6f7fb;font-synthesis:none}*{box-sizing:border-box}body{margin:0}a{color:#4f46e5;text-decoration:none}a:hover{text-decoration:underline}.topbar{background:#fff;border-bottom:1px solid #e8eaf0;position:sticky;top:0;z-index:5}.topinner{max-width:1180px;min-height:68px;margin:auto;padding:10px 20px;display:flex;align-items:center;justify-content:space-between;gap:16px}.brand{font-weight:850;font-size:21px;color:#312e81;white-space:nowrap}.wrap{max-width:1180px;margin:32px auto;padding:0 20px}.card{background:#fff;border:1px solid #e7e9f0;border-radius:16px;padding:24px;box-shadow:0 8px 30px #20284a0a}.btn{display:inline-block;border:0;border-radius:10px;padding:11px 16px;background:#4f46e5;color:#fff;font-weight:700;cursor:pointer;font:inherit}.btn:hover{background:#3730a3;text-decoration:none}.btn.secondary{background:#eef0ff;color:#3730a3}.btn.danger{background:#b42318}.input{display:block;width:100%;border:1px solid #d9dce6;border-radius:10px;padding:12px 14px;font:inherit;margin:8px 0 16px}.muted{color:#697386}.error{color:#b42318;background:#fff1f0;padding:12px;border-radius:10px;margin:12px 0}.success{color:#067647;background:#ecfdf3;padding:12px;border-radius:10px;margin:12px 0}.bubble{padding:14px 16px;margin:12px 0;border-radius:14px;white-space:pre-wrap;line-height:1.6}.bubble.user{background:#eef0ff;margin-left:12%}.bubble.assistant{background:#f3f5f7;margin-right:8%}.form-row{display:flex;gap:10px;align-items:flex-end}.form-row .input{margin-bottom:0}.navlinks{display:flex;align-items:center;gap:12px;flex-wrap:wrap}.navlinks a{font-size:14px}.navlinks form{display:inline}.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px}.tablewrap{overflow-x:auto}table{width:100%;border-collapse:collapse;text-align:left}th,td{padding:11px 9px;border-bottom:1px solid #edf0f5;vertical-align:top}label{font-weight:600;font-size:14px}footer{max-width:1180px;margin:40px auto;padding:0 20px 24px;color:#8790a0;font-size:13px}@media(max-width:760px){.topinner{align-items:flex-start;flex-direction:column}.wrap{margin:20px auto}.card{padding:18px}.form-row{display:block}.form-row .btn{margin-top:10px}}
    </style>
</head>
<body>
<header class="topbar"><div class="topinner">
    <a class="brand" href="{{ route('home') }}">✦ BiếtTuốt</a>
    @include('layouts.navigation')
</div></header>
<main class="wrap">
    @if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif
    @if(session('error'))<div class="error">{{ session('error') }}</div>@endif
    @if(session('status'))<div class="success">{{ session('status') }}</div>@endif
    @yield('content')
</main>
<footer>BiếtTuốt · Nội dung do AI tạo cần được kiểm tra trước khi sử dụng.</footer>
</body>
</html>
