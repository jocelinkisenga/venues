@props(['name'])
@php($paths = \App\Support\Icons::paths($name))
<svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
     fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
    @foreach($paths as $d)
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}" />
    @endforeach
</svg>