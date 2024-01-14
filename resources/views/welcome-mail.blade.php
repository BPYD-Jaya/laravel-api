<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {{-- <link rel="stylesheet" href="resources/css/app.css"> --}}
        <title>Welcome to {{ config('app.name') }}</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            .flex {
                display: flex;
            }

            .justify-center {
                justify-content: center;
            }

            .items-center {
                align-items: center;
            }

            .bg-blue-50 {
                background-color: #edf2f7;
            }

            .bg-white {
                background-color: #ffffff;
            }

            .rounded-lg {
                border-radius: 0.375rem; /* 6px */
            }

            .shadow-lg {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .p-5 {
                padding: 1.25rem; /* 20px */
            }

            .m-4 {
                margin: 1rem; /* 16px */
            }

            .w-1/2 {
                width: 50%;
            }

            .text-center {
                text-align: center;
            }

            .font-bold {
                font-weight: bold;
            }

            .text-2xl {
                font-size: 1.5rem; /* 24px */
            }

            .mb-5 {
                margin-bottom: 1.25rem; /* 20px */
            }

            .mt-5 {
                margin-top: 1.25rem; /* 20px */
            }

            .py-2 {
                padding-top: 0.5rem; /* 8px */
                padding-bottom: 0.5rem; /* 8px */
            }

            .px-4 {
                padding-left: 1rem; /* 16px */
                padding-right: 1rem; /* 16px */
            }

            .bg-blue-500 {
                background-color: #0E57A6;
            }

            .hover\:bg-blue-700:hover {
                background-color: #1a365d;
            }

            .text-white {
                color: #ffffff;
            }
            
            /* Add your own link styling */
            a {
                text-decoration: none;
                color: #0E57A6;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="flex justify-center items-center bg-blue-500">
            <div class="bg-white rounded-lg shadow-lg p-5 m-4 w-1/2">
                <h1 class="text-center font-bold text-2xl mb-5">Welcome to {{ config('app.name') }}</h1>
                <p class="text-center">Hi, thank you for joining us.</p>
                {{-- <p class="text-center">Thank you for joining us.</p> --}}
                <p class="text-center">For latest information will be send to this email.</p>
                {{-- <div class="flex justify-center items-center mt-5">
                    <a href="" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Verify Email</a>
                </div> --}}
                <p class="text-center mt-5">Regards,</p>
                <p class="text-center">{{ config('app.name') }}</p>
            </div>
        </div>
    </body>
</html>
