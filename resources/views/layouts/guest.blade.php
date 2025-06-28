<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sans Cargo Prima Nusantara</title>

  <!-- Tailwind CSS with Forms plugin -->
  <script src="https://cdn.tailwindcss.com?plugins=forms"></script>

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#4CAF50',
          }
        }
      }
    }
  </script>

  <style>
    [x-cloak] { display: none !important; }
    input, select, textarea {
      display: block;
      width: 100%;
      appearance: none;
      border-radius: 0.375rem;
      border-width: 1px;
      border-color: #D1D5DB;
      padding: 0.5rem 0.75rem;
      font-size: 0.875rem;
      line-height: 1.25rem;
    }
    input:focus, select:focus, textarea:focus {
      outline: none;
      --tw-ring-color: rgba(76, 175, 80, 0.5);
      --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
      --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
      box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
      border-color: #4CAF50;
    }
  </style>

  @yield('styles')
</head>
<body class="bg-gray-50">
  {{-- Tidak ada navbar/sidebar di layout ini --}}

  <div class="min-h-screen flex items-center justify-center px-4">
    @yield('content')
  </div>

  <!-- Alpine.js -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  @yield('scripts')
</body>
</html>
