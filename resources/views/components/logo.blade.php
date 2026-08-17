@props(['class' => 'h-10 w-10'])

<svg class="{{ $class }}" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <defs>
        <linearGradient id="logoGrad" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#a855f7"/>
            <stop offset="100%" stop-color="#7c3aed"/>
        </linearGradient>
    </defs>
    <path d="M50 6 L88 28 L88 72 L50 94 L12 72 L12 28 Z"
          stroke="url(#logoGrad)" stroke-width="6" fill="none" stroke-linejoin="round"/>
    <path d="M35 38 Q50 30 65 38 Q60 50 50 50 Q40 50 35 62 Q50 70 65 62"
          stroke="url(#logoGrad)" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
