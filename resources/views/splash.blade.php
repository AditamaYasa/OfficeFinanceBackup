<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TigaJaya Finance')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex items-center justify-center font-poppins bg-white">
    <img src="{{ asset('assets/picture/logo.png') }}" 
        alt="Logo TigaJaya Finance"
        class="w-40 md:w-56 lg:w-64 h-auto splash-animation object-contain"> 
<script>
setTimeout(function(){
    @if(Auth::guard('admin')->check())
        window.location.href = "{{ route('home') }}";
    @elseif(Auth::guard('web')->check())
        window.location.href = "{{ route('home') }}";
    @else
        window.location.href = "{{ route('login') }}";
    @endif
}, 3000);
</script>
</body>
</html>
