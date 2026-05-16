@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->class(['rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50','border-red-500' => $errors->has($attributes['name'])]) !!}>

@error($attributes['name'])
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
