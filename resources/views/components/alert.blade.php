@props(['name','type'])
@if (session($name))
    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
      {{ session($name) }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif