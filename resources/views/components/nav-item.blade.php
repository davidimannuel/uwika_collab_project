@props(['active' => false, 'htmlTag' => 'a', 'disabled' => false])
@php
  $defaultClass = $active ? 'link-secondary' : '';
  $defaultClass = 'nav-link px-2 '.$defaultClass;
  if ($disabled) {
    $defaultClass .= ' disabled';
  }
@endphp
<li>
  <{{$htmlTag}}  {{ $attributes->merge(['class' => $defaultClass]) }}>{{ $slot }}</{{$htmlTag}}>
</li>