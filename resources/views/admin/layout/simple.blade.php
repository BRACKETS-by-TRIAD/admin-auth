<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Interface</title>

    <!-- Icons -->
    <link href="/coreui/css/font-awesome.min.css" rel="stylesheet">
    <link href="/coreui/css/simple-line-icons.css" rel="stylesheet">
    <link href="http://coreui.io/demo/Static_Demo/assets/css/style.css" rel="stylesheet">

    <!-- Main styles for this application -->
    {{--<link href="/coreui/css/style.css" rel="stylesheet">--}}
{{--    <link href="{{ mix('css/admin/app.css') }}" rel="stylesheet">--}}

</head>

<body class="">
    <div class="container" id="app">
        @yield('content')
    </div>

    <script src="{{ mix('/js/admin/admin.js') }}"></script>
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
</body>

</html>