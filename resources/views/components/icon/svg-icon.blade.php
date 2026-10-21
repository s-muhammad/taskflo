@props(['name', 'class' => 'w-6 h-6'])

<svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
    @if($name === 'check')
        <path d="M5 13l4 4L19 7" />

    @elseif($name === 'menu')
        <path d="M4 6h16M4 12h16M4 18h16" />

    @elseif($name === 'close')
        <path d="M6 18L18 6M6 6l12 12" />

    @elseif($name === 'clock')
        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

    @elseif($name === 'list')
        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />

    @elseif($name === 'chart')
        <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
        <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />

    @elseif($name === 'arrow-left')
        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" />

    @elseif($name === 'eye')
        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

    @elseif($name === 'bolt')
        <path d="M13 10V3L4 14h7v7l9-11h-7z" />

    @elseif($name === 'star')
        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292c.099.301.409.499.725.499h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034c-.262.191-.378.516-.313.833l.829 3.361c.203.824-.722 1.457-1.54 1.137l-3.068-1.613a.75.75 0 00-.698 0l-3.068 1.613c-.818.32-1.743-.313-1.54-1.137l.829-3.361c.065-.317-.051-.642-.313-.833l-2.8-2.034c-.783-.57-.381-1.81.588-1.81H5.258c.316 0 .626-.198.725-.499l1.07-3.292z" />

    @elseif($name === 'calendar')
        <path d="M8 2v4M16 2v4" />
        <rect x="3" y="4" width="18" height="18" rx="2" />
        <path d="M3 10h18" />

    @elseif($name === 'calendar-check')
        <path d="M8 2v4M16 2v4" />
        <rect x="3" y="4" width="18" height="18" rx="2" />
        <path d="M3 10h18M9 16l2 2 4-4" />

    @elseif($name === 'chart-column')
        <path d="M3 3v16a2 2 0 002 2h16" />
        <path d="M18 17V9M13 17V5M8 17v-4" />

    @elseif($name === 'trending-up')
        <path d="M22 7l-8.5 8.5-5-5L2 17" />
        <path d="M16 7h6v6" />

    @elseif($name === 'target')
        <circle cx="12" cy="12" r="9" />
        <circle cx="12" cy="12" r="5" />
        <circle cx="12" cy="12" r="1.5" />

    @elseif($name === 'tag')
        <path d="M12.6 2.6A2 2 0 0011.2 2H4a2 2 0 00-2 2v7.2a2 2 0 00.6 1.4l8.8 8.8a2 2 0 002.8 0l6.6-6.6a2 2 0 000-2.8z" />
        <circle cx="7.5" cy="7.5" r="1" />

    @elseif($name === 'bell')
        <path d="M10.3 21a2 2 0 003.4 0" />
        <path d="M3.3 15.3A1 1 0 004 17h16a1 1 0 00.7-1.7C19.4 14 18 12.5 18 8A6 6 0 006 8c0 4.5-1.4 6-2.7 7.3" />

    @elseif($name === 'check-circle')
        <circle cx="12" cy="12" r="9" />
        <path d="M8.5 12.5l2.5 2.5 4.5-5" />

    @elseif($name === 'sparkles')
        <path d="M9.9 15.5A2 2 0 018.5 14l-6.1-1.6a.5.5 0 010-1L8.5 9.9A2 2 0 009.9 8.5l1.6-6.1a.5.5 0 01.9 0l1.6 6.1A2 2 0 0015.5 9.9l6.1 1.6a.5.5 0 010 .9L15.5 14a2 2 0 01-1.4 1.4l-1.6 6.1a.5.5 0 01-.9 0z" />
        <path d="M20 3v4M22 5h-4M4 17v2M5 18H3" />

    @elseif($name === 'users')
        <path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path d="M22 21v-2a4 4 0 00-3-3.9M16 3.1a4 4 0 010 7.8" />

    @elseif($name === 'layers')
        <path d="M12.8 2.2a2 2 0 00-1.6 0L2.6 6.1a1 1 0 000 1.8l8.6 3.9a2 2 0 001.6 0l8.6-3.9a1 1 0 000-1.8z" />
        <path d="M22 17.6l-9.2 4.2a2 2 0 01-1.6 0L2 17.6M22 12.6l-9.2 4.2a2 2 0 01-1.6 0L2 12.6" />

    @elseif($name === 'pencil')
        <path d="M21.2 6.8a1 1 0 00-4-4L3.8 16.2a2 2 0 00-.5.8l-1.3 4.4a.5.5 0 00.6.6l4.4-1.3a2 2 0 00.8-.5z" />

    @elseif($name === 'plus')
        <path d="M5 12h14M12 5v14" />

    @elseif($name === 'activity')
        <path d="M22 12h-2.5a2 2 0 00-1.9 1.5l-2.4 8.4a.25.25 0 01-.5 0L9.2 2.2a.25.25 0 00-.5 0L6.4 10.6A2 2 0 004.5 12H2" />

    @elseif($name === 'list-checks')
        <path d="M3 7l2 2 4-4M3 17l2 2 4-4M13 6h8M13 12h8M13 18h8" />

    @elseif($name === 'moon')
        <path d="M12 3a6 6 0 009 9 9 9 0 11-9-9z" />

    @elseif($name === 'repeat')
        <path d="m17 2 4 4-4 4" />
        <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
        <path d="m7 22-4-4 4-4" />
        <path d="M21 13v1a4 4 0 0 1-4 4H3" />

    @elseif($name === 'shield-check')
        <path d="M12 22s8-3.5 8-10V5l-8-3-8 3v7c0 6.5 8 10 8 10z" />
        <path d="M9 12l2 2 4-4" />
    @endif
</svg>
