<!DOCTYPE HTML>
<html>
    <head>
        @include('includes.meta')
        @include('includes.assets')
        @stack('styles')
        @stack('header_scripts')
    </head>

    <body>
        @include('includes.navbar')

        <main class="container mt-5 pt-5">
            @yield('content')

        </main>

        <div id="toast-container" class="toast-container position-fixed bottom-0 start-0 p-3"></div>

        <script src="/js/toast.js"></script>
        @stack('scripts')
    </body>
</html>