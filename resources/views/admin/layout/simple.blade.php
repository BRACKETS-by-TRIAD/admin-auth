@extends('brackets/admin::admin.layout.master')

@section('content')
    <div class="container" id="app">
        @yield('body')
    </div>
@endsection

@section('styles')
    <link href="http://coreui.io/demo/Static_Demo/assets/css/style.css" rel="stylesheet">
@endsection

@section('bottom-scripts')
    @parent
    <script>
        function verticalAlignMiddle()
        {
            var bodyHeight = $(window).height();
            var formHeight = $('.vamiddle').height();
            var marginTop = (bodyHeight / 2) - (formHeight / 2);
            if (marginTop > 0)
            {
                $('.vamiddle').css('margin-top', marginTop);
            }
        }
        $(document).ready(function()
        {
            verticalAlignMiddle();
        });
        $(window).bind('resize', verticalAlignMiddle);
    </script>
@endsection
