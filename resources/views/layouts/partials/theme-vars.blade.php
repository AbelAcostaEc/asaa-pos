@auth
    @php
        $settings = auth()->user()->theme_settings ?? [];
        $primary = $settings['primary'] ?? null;
        $secondary = $settings['secondary'] ?? null;
        $accent = $settings['accent'] ?? null;
        
        // Helper function to hex to rgb string (r g b)
        $hexToRgb = function($hex) {
            if (!$hex) return null;
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            return "$r $g $b";
        };

        $primaryRgb = $hexToRgb($primary);
        $secondaryRgb = $hexToRgb($secondary);
        $accentRgb = $hexToRgb($accent);
    @endphp

    @if($primaryRgb || $secondaryRgb || $accentRgb)
        <style>
            :root {
                @if($primaryRgb) --color-primary: {{ $primaryRgb }}; @endif
                @if($secondaryRgb) --color-secondary: {{ $secondaryRgb }}; @endif
                @if($accentRgb) --color-accent: {{ $accentRgb }}; @endif
            }
        </style>
    @endif
@endauth
