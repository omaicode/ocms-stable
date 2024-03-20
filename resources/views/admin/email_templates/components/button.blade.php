<div style="margin-bottom: 16px">
    <a href="{{ $url }}" class="es-button" target="_blank"
        style="
            color: {{ $text_color ?? '#fff' }};
            border-color: {{ $btn_color ?? '#333' }};
            background:  {{ $btn_color ?? '#333' }}
        ">
        {{ $slot }}
    </a>
</div>
