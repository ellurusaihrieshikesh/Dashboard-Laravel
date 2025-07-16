<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Switch Account - Sales Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Switch Account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Select an account to switch to
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mt-8 space-y-6">
                @foreach($users as $user)
                    <form action="{{ route('switch.account') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label for="password_{{ $user->id }}" class="block text-sm font-medium text-gray-700">
                                    Enter your password to switch
                                </label>
                                <input type="password" name="password" id="password_{{ $user->id }}" required
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Enter your password">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Switch to this account
                                </button>
                            </div>
                        </div>
                    </form>
                @endforeach

                <div class="text-center">
                    <a href="{{ route('sales.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>