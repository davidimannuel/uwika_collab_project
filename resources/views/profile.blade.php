<x-layout>
  <x-slot:headingTitle>
    Profile
  </x-slot>
  
  <x-alert name='success-alert' type='success'/>

  <h5>{{ auth()->user()->email }}</h5>
  <div class="row">
    <div class="col-6">
      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <!-- Name Field -->
        <x-form-field>
          <x-form-label for="name">Name</x-form-label>
          <x-form-input name="name" type="text" value="{{ old('name', auth()->user()->name) }}" />
          <x-form-error name="name" />
        </x-form-field>

        <!-- Current Password Field -->
        <x-form-field>
          <x-form-label for="current_password">Current Password</x-form-label>
          <x-form-input name="current_password" id="current_password" type="password" required />
          <x-form-error name="current_password" />
        </x-form-field>

        <!-- New Password Field -->
        <x-form-field>
          <x-form-label for="new_password">New Password</x-form-label>
          <x-form-input name="new_password" id="new_password" type="password" />
          <x-form-error name="new_password" />
        </x-form-field>

        <!-- Confirm New Password Field -->
        <x-form-field>
          <x-form-label for="new_password_confirmation">Confirm New Password</x-form-label>
          <x-form-input name="new_password_confirmation" id="new_password_confirmation" type="password" />
          <x-form-error name="new_password_confirmation" />
        </x-form-field>

        <hr class="my-4">
        <div class="text-end">
          <button type="submit" class="btn btn-primary" id="save-btn">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  @push('custom-css')
  <link href="{{ asset('assets/sweetalert2-11.14.1/dist/sweetalert2.min.css') }}"  rel="stylesheet">
  @endpush
  
  @push('custom-js')
    <script src="{{ asset('assets/sweetalert2-11.14.1/dist/sweetalert2.all.min.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Select all delete buttons
          var button = document.getElementById('save-btn');
          button.addEventListener('click', function(event) {
              event.preventDefault(); // Prevent the form from submitting immediately
              let form = this.closest('form'); // Find the form associated with the delete button
              // Trigger SweetAlert confirmation
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, save it!'
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Submit the form if the user confirms
                      form.submit();
                  }
              })
          });

          const isDebtCheckbox = document.getElementById('is_debt');
          const debtDueAtInput = document.getElementById('debt_due_at');
          // Function to toggle the disabled attribute of the debt_due_at input
          function toggleDebtDueAtActive() {
              debtDueAtInput.disabled = !isDebtCheckbox.checked;
          }
          // Initialize the state of the debt_due_at input
          toggleDebtDueAtActive();
          // Add event listener for the checkbox to call toggleDebtDueAtActive when changed
          isDebtCheckbox.addEventListener('change', toggleDebtDueAtActive);

          // unselect categories
          document.getElementById('reset-categories').addEventListener('click', function() {
            const categories = document.getElementById('categories');
            for (let i = 0; i < categories.options.length; i++) {
              categories.options[i].selected = false; // Unselect each option
            }
          });
      });
    </script>
  @endpush
</x-layout>
