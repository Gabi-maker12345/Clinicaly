@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm', 'style' => 'background: var(--sf2); color: var(--tx); border: 1.5px solid var(--bd); padding: 10px 12px; outline: none; transition: border-color 0.2s; font-family: inherit;']) !!} 
       onfocus="this.style.borderColor = 'var(--in)'" onblur="this.style.borderColor = 'var(--bd)'"
       {{ $disabled ? 'disabled' : '' }}>
