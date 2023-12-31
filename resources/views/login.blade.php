<!DOCTYPE html>
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.png" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Midone admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>RS Medika Batanghari</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <div class="my-auto">
                        <img alt="Midone Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="dist/images/logo.png">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Selamat Datang di 
                            <br>
                            Sistem Informasi Rekam Medis
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white dark:text-gray-500">RS Mitra Medika Batanghari</div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                             <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center">Masuk ke Sistem Informasi</h2>
                            <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
                            <div class="intro-x mt-8">
                                <input name="login_id" type="text" class="intro-x login__input input input--lg border border-gray-300 block" placeholder="User ID" required autofocus>                          
                                <input name="password" type="password" class="intro-x login__input input input--lg border border-gray-300 block mt-4" placeholder="Password">
                            </div>

                            @if(Session::has('error'))
                            <div  style="color: red; font-weight: bold;">
                                {{ Session::get('error') }}
                            </div>
                            @endif


                            <div class="intro-x mt-5 xl:mt-8 text-center">
                                <button class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3 align-top">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>