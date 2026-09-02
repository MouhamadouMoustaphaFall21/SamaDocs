<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'SamaDocs')</title>
  <script>
    // Apply saved theme before paint to avoid flash
    (function(){
      try {
        var saved = localStorage.getItem('samadocs-theme');
        if (saved) document.documentElement.setAttribute('data-theme', saved);
      } catch(e) {}
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>
<body>
  @yield('body')

  <div class="toast-container"></div>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
