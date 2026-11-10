@props(['name','size'=>22])
<svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($name)
@case('search')<circle cx="10.5" cy="10.5" r="7"/><path d="m16 16 5 5"/>@break
@case('bag')<path d="M5 7h14l1 14H4L5 7Z"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/>@break
@case('heart')<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>@break
@case('user')<circle cx="12" cy="7" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/>@break
@case('menu')<path d="M3 6h18M3 12h18M3 18h18"/>@break
@case('arrow')<path d="M20 12H4m6-6-6 6 6 6"/>@break
@case('close')<path d="m6 6 12 12M6 18 18 6"/>@break
@case('truck')<path d="M1 4h13v12H1zm13 5h4l4 4v3h-8"/><circle cx="5" cy="18" r="2"/><circle cx="18" cy="18" r="2"/>@break
@case('check')<path d="m5 12 4 4L20 5"/>@break
@case('shield')<path d="m12 2 9 4v6c0 5-9 10-9 10S3 17 3 12V6z"/><path d="m8 12 3 3 5-6"/>@break
@case('filter')<path d="M3 6h18M6 12h12M9 18h6"/>@break
@default <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
@endswitch
</svg>
