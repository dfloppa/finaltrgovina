@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#0ABAB5] focus:ring-[#0ABAB5] rounded-md shadow-sm']) }}>
