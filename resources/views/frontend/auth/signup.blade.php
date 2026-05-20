<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center p-4 font-['Outfit']">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="flex justify-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-lg">
                <svg width="24" height="24" viewBox="0 0 20 20" fill="white">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z" />
                </svg>
            </div>
        </div>
      @if($errors->any())
            <div class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/20">
                <svg class="shrink-0 text-red-500 mt-0.5" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                </svg>
                <p class="text-sm text-red-700 dark:text-red-400">{{ $errors->first() }}</p>
            </div>
            @endif
        <div class="rounded-xl border border-gray-100 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">Create Account</h1>
                <p class="text-lg text-gray-500 dark:text-gray-400 mt-1">Join us today! Please enter your details.</p>
            </div>

            <form action="{{ route('signup') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    {{-- Full Name --}}
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Your Name" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('name') border-red-500 @enderror" />
                        @error('name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="test@gmail.com" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('email') border-red-500 @enderror" />
                        @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Employee Id</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="Enter Employee Id" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('employee_id') border-red-500 @enderror" />
                        @error('employee_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('password') border-red-500 @enderror" />
                        @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="mb-1.5 block text-lg font-medium text-gray-700 dark:text-gray-400">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white shadow-md hover:bg-blue-700 transition-all">
                        Create Account
                    </button>
                </div>
            </form>

            {{-- Login Link --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Already have an account?
                    <a href="{{ route('signin') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
