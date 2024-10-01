<x-layout>
  <x-slot:headingTitle>
    Create account
  </x-slot>
  
  <div class="row">
    <div class="col-6">
      <form method="POST" action="{{ route('accounts.store') }}" >
        @csrf
        <x-form-field>
          <x-form-label for="name">Name</x-form-label>
          <x-form-input name='name' type="text"/>
          <x-form-error name='name'/>
        </x-form-field>

        <hr class="my-4">
        <div class="text-end">
          <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</x-layout>