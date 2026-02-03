<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LPS</title>
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
                <h2 class="text-3xl font-bold text-gray-800">Buat Akun Baru</h2>
                <p class="text-gray-500 mt-2">Silakan daftar untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="block mt-1 w-full border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 rounded-lg shadow-sm p-2.5 bg-gray-50"
                        placeholder="Nama lengkap">
                    @error('name')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mt-5">
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="block mt-1 w-full border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 rounded-lg shadow-sm p-2.5 bg-gray-50"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mt-5">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="block mt-1 w-full border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 rounded-lg shadow-sm p-2.5 bg-gray-50"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mt-5">
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="block mt-1 w-full border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50 rounded-lg shadow-sm p-2.5 bg-gray-50"
                        placeholder="••••••••">
                </div>

                {{-- Submit --}}
                <div class="mt-8">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out uppercase tracking-wider">
                        {{ __('Register') }}
                    </button>
                </div>

                {{-- Login link --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
