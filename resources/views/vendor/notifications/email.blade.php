<x-mail::message>
    {{-- 🌟 TAMBAHAN: LOGO KWT CIBIRU DI ATAS GREETING 🌟 --}}
    <div style="text-align: center; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px;">
        @if(file_exists(public_path('images/logo-cibiru.png')))
        <img src="{{ asset('images/logo-cibiru.png') }}" alt="Logo KWT Cibiru" style="max-height: 80px; width: auto; display: block; margin: 0 auto;">
        @else
        <h2 style="color: #16a34a; font-family: 'Inter', sans-serif; font-weight: 800; margin: 0; letter-spacing: -0.5px; font-size: 1.6rem;">
            <i class="bi bi-shop"></i> KWT CIBIRU
        </h2>
        <small style="color: #64748b; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: block; margin-top: 4px;">Official Notification</small>
        @endif
    </div>

    {{-- Greeting --}}
    @if (! empty($greeting))
    # {{ $greeting }}
    @else
    @if ($level === 'error')
    # @lang('Whoops!')
    @else
    # @lang('Hello!')
    @endif
    @endif

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
    {{ $line }}

    @endforeach

    {{-- Action Button --}}
    @isset($actionText)
    <?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
    ?>
    <x-mail::button :url="$actionUrl" :color="$color">
        {{ $actionText }}
    </x-mail::button>
    @endisset

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
    {{ $line }}

    @endforeach

    {{-- Salutation --}}
    @if (! empty($salutation))
    {{ $salutation }}
    @else
    @lang('Regards,')<br>
    {{ config('app.name') }}
    @endif

    {{-- Subcopy --}}
    @isset($actionText)
    <x-slot:subcopy>
        @lang(
        "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
        'into your web browser:',
        [
        'actionText' => $actionText,
        ]
        ) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
    </x-slot:subcopy>
    @endisset
</x-mail::message>