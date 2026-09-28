@props(['size' => 'sm'])

@php
    $sizes = [
        'sm' => ['text' => 'text-[21px]', 'dot' => 'w-[7px] h-[7px]', 'margin' => 'mb-[3px] ml-[3px]'],
        'lg' => ['text' => 'text-[24px]', 'dot' => 'w-[8px] h-[8px]', 'margin' => 'mb-[3px] ml-[3px]'],
    ];
    $s = $sizes[$size] ?? $sizes['sm'];
@endphp

<a href="{{ route('discover') }}"
   aria-label="Green Dot home"
   class="flex items-end text-text no-underline">
    <span class="font-display font-extrabold tracking-[-0.03em] leading-none {{ $s['text'] }}">Green Dot</span>
    <span class="rounded-full bg-dot gd-pulse {{ $s['dot'] }} {{ $s['margin'] }}"
          style="box-shadow: var(--gd-glow)"
          aria-hidden="true"></span>
</a>
