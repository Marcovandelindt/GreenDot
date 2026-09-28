<x-layouts.app title="Verify your email — Green Dot">
    <div class="flex flex-col items-center justify-center min-h-[calc(100vh-72px)] px-4 py-12">
        <div class="w-full max-w-[420px] bg-surface border border-line rounded-[20px] p-8 shadow-card">

            <div class="w-12 h-12 rounded-[14px] bg-surface-2 border border-line flex items-center justify-center mb-6">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted" aria-hidden="true">
                    <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
            </div>

            <h1 class="font-display font-extrabold text-[28px] tracking-[-0.03em]">Check your inbox</h1>
            <p class="text-[15px] text-muted mt-2 mb-6 leading-relaxed">
                We sent a verification link to <strong class="text-text font-semibold">{{ auth()->user()->email }}</strong>.
                Click the link to activate your account.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="flex items-center gap-2 px-4 py-3 rounded-[12px] bg-green-soft text-green-text text-[14px] font-semibold mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                    A new link has been sent.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full h-[48px] bg-green text-green-ink rounded-[14px] font-semibold text-[15px] cursor-pointer border-0">
                    Resend verification email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full h-[44px] bg-transparent border border-line text-muted rounded-[14px] font-semibold text-[14px] cursor-pointer hover:text-text hover:border-text transition-colors">
                    Sign out
                </button>
            </form>

        </div>
    </div>
</x-layouts.app>
