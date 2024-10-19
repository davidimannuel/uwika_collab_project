<x-layout>
  <x-slot:headingTitle>
    Support
  </x-slot>

  <div class="row">
    if you have any issues please to email admins below
    <ul>
      @foreach ($admins as $admin)
      <li> {{ $admin->name }} ( {{ $admin->email }} ) </li>
      @endforeach
    </ul>
  </div>
</x-layout>