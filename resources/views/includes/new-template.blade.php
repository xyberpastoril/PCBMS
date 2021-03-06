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
        @include('includes.global-topnav')

        <div id="layoutSidenav">
            @include('includes.global-sidenav')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        @yield('content')
                    </div>
                </main>
                @include('includes.global-footer')
            </div>
        </div>

        @yield('modals')

        <div id="toast-container" class="toast-container position-fixed top-0 mt-5 end-0 p-3"></div>

        <form id="form-logout" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="{{ url('/js/toast.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ url('/js/scripts.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="{{ url('/js/datatables-simple-demo.js') }}"></script>
        @stack('app-scripts')

        <script>
            setInterval(function(){
                var date = new Date();
                var hours = date.getHours();
                var days = date.getDay(); 
                var minutes = date.getMinutes();
                var seconds = date.getSeconds();
                var ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                seconds = seconds < 10 ? '0'+seconds : seconds;
                var strTime = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                $('#time').html(strTime);
                console.log(strTime);
            }, 1000);
        </script>
    </body>
</html>