<!DOCTYPE HTML>
<html>
    <head>
        @include('includes.meta')
        @include('includes.global-styles')
        @stack('global-styles')
        @include('includes.global-scripts')
        @stack('global-scripts')
    </head>

    <body>
        @yield('content')

        <div id="toast-container" class="toast-container position-fixed top-0 mt-5 end-0 p-3"></div>

        <form id="form-logout" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="{{ url('/js/toast.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ url('/js/scripts.js') }}"></script>
        @stack('app-scripts')
    </body>
</html>