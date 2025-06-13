<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Smart RT') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                background: #E0F2F1; /* Soft Green Background */
            }
            .navbar {
                background-color: #FFFFFF; /* White Navbar */
                color:rgba(52, 130, 247, 0.98);
            }
            .alert {
                border-radius: 0.5rem;
                padding: 1rem;
                margin-bottom: 1rem;
            }
            .alert-success {
                background-color: #BBDEFB; /* Soft Blue */
                color:rgb(30, 108, 224); /* Deep Blue */
            }
            .alert-error {
                background-color: #FF8B94; /* Soft Red */
                color: #C62828; /* Dark Red */
            }
            .alert-warning {
                background-color: #FFD3B6; /* Soft Yellow */
                color: #92400E; /* Dark Yellow */
            }
            .alert-info {
                background-color: #D5C6D8; /* Soft Lavender */
                color:rgb(84, 50, 255); /* Dark Purple */
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="navbar shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-error" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                </div>
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
