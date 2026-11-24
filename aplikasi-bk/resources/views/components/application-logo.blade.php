<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-700 to-purple-500 flex items-center justify-center shadow-md shadow-purple-500/20 text-white flex-shrink-0">
        <img src="{{ asset('images/digiself-logo.png') }}" alt="" class="w-10 h-10 object-contain" />
    </div>
    <div class="flex flex-col text-left">
        <span class="font-bold text-lg leading-tight tracking-tight text-gray-900">
            DIGI<span class="text-purple-700">SELF</span>
        </span>
        <span class="text-[11px] font-semibold tracking-wide uppercase text-purple-700">
            Digital Self-Development
        </span>
    </div>
</div>
