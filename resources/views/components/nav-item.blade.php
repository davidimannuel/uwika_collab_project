@props(['active' => false, 'htmlTag' => 'a'])
@php
  $defaultClass = $active ? 'link-secondary' : '';
  $defaultClass = 'nav-link px-2 '.$defaultClass;
@endphp
<li>
  <{{$htmlTag}}  {{ $attributes->merge(['class' => $defaultClass]) }}>{{ $slot }}</{{$htmlTag}}>
</li>