<?php
 
  $setting =\App\Models\SettingDetail::find(1);
  $user = auth()->user();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        @include('frontend.layouts.head')
        <!-- Thêm link CSS cho Bootstrap và Tailwind -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
        @yield('head_css')
    </head>
    <body class="[word-spacing:.05rem!important] font-Manrope text-[0.8rem] !leading-[1.7] font-medium">
     
    @include('frontend.layouts.header')
    @include('frontend.layouts.notification')
        @yield('content')
        @include('frontend.layouts.footer')
        @include('frontend.layouts.foot')
        @yield('scripts')
    </body>
</html>