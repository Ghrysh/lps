<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Modern Orange</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div
            class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border-t-4 border-orange-500">

            <div class="mb-8 text-center">
                <h2 class="text-3xl font-bold text-gray-800">Selamat Datang</h2>
                <p class="text-gray-500 mt-2">Silakan masuk ke akun Anda</p>
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus
                        class="block mt-1 w-full border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 rounded-lg shadow-sm p-2.5 bg-gray-50"
                        placeholder="nama@email.com">
                    @if ($errors->get('email'))
                        <ul class="text-sm text-red-600 space-y-1 mt-2">
                            @foreach ((array) $errors->get('email') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    </div>
                    <input id="password" type="password" name="password" required
                        class="block mt-1 w-full border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 rounded-lg shadow-sm p-2.5 bg-gray-50"
                        placeholder="••••••••">
                    @if ($errors->get('password'))
                        <ul class="text-sm text-red-600 space-y-1 mt-2">
                            @foreach ((array) $errors->get('password') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="flex items-center justify-between mt-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600 italic">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-orange-600 hover:text-orange-700 font-semibold"
                            href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out uppercase tracking-wider">
                        {{ __('Log in') }}
                    </button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Daftar
                            sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
