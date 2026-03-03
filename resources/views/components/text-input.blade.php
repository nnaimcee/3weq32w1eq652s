@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm text-slate-800 placeholder-slate-400 transition focus:bg-white']) }}>
