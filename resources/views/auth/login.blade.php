<x-layouts.app title="Sign in — Green Dot">
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-72px)] px-4 py-12">
        <div class="w-full max-w-[420px] bg-surface border border-line rounded-[20px] p-8 shadow-card">

            <h1 class="font-display font-extrabold text-[32px] tracking-[-0.03em]">Sign in</h1>
            <p class="text-[15px] text-muted mt-2 mb-8">Welcome back. Find your next teammate.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label class="block text-[13px] font-semibold mb-1.5" for="email">Email</label>
                    <div class="flex items-center h-[56px] bg-surface border border-line rounded-[14px] px-4">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            class="w-full bg-transparent text-[16px] font-mono outline-none"
                        >
                    </div>
                    @error('email')
                        <p class="text-[13px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label class="block text-[13px] font-semibold mb-1.5" for="password">Password</label>
                    <div class="flex items-center h-[56px] bg-surface border border-line rounded-[14px] px-4">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            class="w-full bg-transparent text-[16px] outline-none"
                        >
                    </div>
                    @error('password')
                        <p class="text-[13px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2 mb-6">
                    <input id="remember" type="checkbox" name="remember" class="rounded">
                    <label for="remember" class="text-[14px] text-muted">Remember me</label>
                </div>

                <button type="submit" class="w-full h-[48px] bg-green text-green-ink rounded-[14px] font-semibold text-[15px] cursor-pointer border-0">
                    Sign in
                </button>
            </form>

            <p class="text-[14px] text-muted text-center mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-green font-medium">Create one</a>
            </p>

        </div>
    </div>
</x-layouts.app>
