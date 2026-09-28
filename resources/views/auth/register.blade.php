<x-layouts.app title="Create account — Green Dot">
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-72px)] px-4 py-12">
        <div class="w-full max-w-[420px] bg-surface border border-line rounded-[20px] p-8 shadow-card">

            <h1 class="font-display font-extrabold text-[32px] tracking-[-0.03em]">Create account</h1>
            <p class="text-[15px] text-muted mt-2 mb-8">Your PSN ID is public — it's how others will find you.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- PSN ID --}}
                <div class="mb-5">
                    <label class="block text-[13px] font-semibold mb-1.5" for="psn_id">PSN ID</label>
                    <div class="flex items-center h-[56px] bg-surface border border-line rounded-[14px] px-4">
                        <input
                            id="psn_id"
                            type="text"
                            name="psn_id"
                            value="{{ old('psn_id') }}"
                            autocomplete="off"
                            spellcheck="false"
                            maxlength="16"
                            class="w-full bg-transparent text-[16px] font-mono outline-none"
                        >
                    </div>
                    <p class="text-[13px] text-muted mt-1.5">3–16 characters: letters, numbers, - and _</p>
                    @error('psn_id')
                        <p class="text-[13px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                <div class="mb-5">
                    <label class="block text-[13px] font-semibold mb-1.5" for="password">Password</label>
                    <div class="flex items-center h-[56px] bg-surface border border-line rounded-[14px] px-4">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            class="w-full bg-transparent text-[16px] outline-none"
                        >
                    </div>
                    @error('password')
                        <p class="text-[13px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label class="block text-[13px] font-semibold mb-1.5" for="password_confirmation">Confirm password</label>
                    <div class="flex items-center h-[56px] bg-surface border border-line rounded-[14px] px-4">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="w-full bg-transparent text-[16px] outline-none"
                        >
                    </div>
                    @error('password_confirmation')
                        <p class="text-[13px] text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full h-[48px] bg-green text-green-ink rounded-[14px] font-semibold text-[15px] cursor-pointer border-0">
                    Create account
                </button>
            </form>

            <p class="text-[14px] text-muted text-center mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-green font-medium">Sign in</a>
            </p>

        </div>
    </div>
</x-layouts.app>
