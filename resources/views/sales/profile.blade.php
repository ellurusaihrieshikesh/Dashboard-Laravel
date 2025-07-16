<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Sales Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('sales.index') }}" class="text-xl font-bold text-blue-600">Sales Dashboard</a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('sales.index') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Profile Settings
                            </h3>
                            <div class="mt-5">
                                @if(session('success'))
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                        <span class="block sm:inline">{{ session('success') }}</span>
                                    </div>
                                @endif

                                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">
                                            Full Name
                                        </label>
                                        <div class="mt-1">
                                            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}"
                                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                   required>
                                        </div>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">
                                            Email Address
                                        </label>
                                        <div class="mt-1">
                                            <input type="email" name="email" id="email" value="{{ auth()->user()->email }}"
                                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                   required>
                                        </div>
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700">
                                            Current Password
                                        </label>
                                        <div class="mt-1">
                                            <input type="password" name="current_password" id="current_password"
                                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Enter your current password to confirm changes
                                        </p>
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="new_password" class="block text-sm font-medium text-gray-700">
                                            New Password
                                        </label>
                                        <div class="mt-1">
                                            <input type="password" name="new_password" id="new_password"
                                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Leave blank to keep current password
                                        </p>
                                        @error('new_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">
                                            Confirm New Password
                                        </label>
                                        <div class="mt-1">
                                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 