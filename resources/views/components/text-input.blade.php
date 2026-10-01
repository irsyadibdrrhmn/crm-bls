@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-emerald-100 shadow-sm focus:border-emerald-500 focus:ring-emerald-500']) }}>
