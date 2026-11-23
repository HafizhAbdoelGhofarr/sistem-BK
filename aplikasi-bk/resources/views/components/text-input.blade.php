@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-purple-600 focus:ring-purple-500 rounded-lg shadow-sm text-sm text-gray-800 transition duration-150']) }}>
