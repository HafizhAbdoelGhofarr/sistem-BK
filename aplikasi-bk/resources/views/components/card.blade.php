@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white border border-gray-100 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md hover:border-purple-100/80']) }}>
    @if ($title)
        <div class="mb-4 pb-3 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-800">{{ $title }}</h3>
                @if ($subtitle)
                    <p class="text-xs text-gray-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if (isset($action))
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
