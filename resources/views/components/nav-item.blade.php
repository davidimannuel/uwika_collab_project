@props(['active' => false])
<li>
  <a class="nav-link px-2 {{ $active ? 'link-secondary' : '' }}" {{ $attributes }}>{{ $slot }}</a>
</li>