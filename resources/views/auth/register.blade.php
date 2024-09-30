<x-layout>
  <x-slot:headingTitle>
    Register
  </x-slot>
  
  <div class="row">
    <div class="col-6">
      <form method="POST" action="{{ route('register.store') }}" >
        @csrf
        <x-form-field>
          <x-form-label for="name">Name</x-form-label>
          <x-form-input name='name' type="text" placeholder="john doe" />
          <x-form-error name='name' />
        </x-form-field>

        <x-form-field>
          <x-form-label for="email">Email</x-form-label>
          <x-form-input name='email' type="email" placeholder="john@example.com" />
          <x-form-error name='email' />
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="password">Password</x-form-label>
          <x-form-input name='password' type="password"/>
          <x-form-error name='password' />
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="password_confirmation">Password confirmation</x-form-label>
          <x-form-input name='password_confirmation' type="password"/>
          <x-form-error name='password_confirmation' />
        </x-form-field>

        <hr class="my-4">
        <div class="text-end">
          <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
          <button type="submit" class="btn btn-primary">Register</button>
        </div>
      </form>
    </div>
  </div>
</x-layout>