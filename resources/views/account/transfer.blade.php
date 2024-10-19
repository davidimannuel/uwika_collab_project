<x-layout>
  <x-slot:headingTitle>
    Transfer balance
  </x-slot>
  <x-alert name='error-alert' type='danger'/>
  <x-alert name='success-alert' type='success'/>
  <div class="row">
    <div class="col-6">
      <form method="POST" action="{{ route('accounts.processTransfer', $originAccount) }}" >
        @csrf
        <x-form-field>
          <x-form-label for="name">Origin account</x-form-label>
          <x-form-input name='name' type="text" disabled value="{{ $originAccount->name }}"/>
          <x-form-error name='name'/>
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="name">Origin account balance</x-form-label>
          <x-form-input type="text" value="Rp. {{ number_format($originAccount->balance, 0, ',', '.') }}" disabled/>
          <x-form-error name='name'/>
        </x-form-field>

        <x-form-field>
          <x-form-label for="destination_account_id">Destination account</x-form-label>
          <select class="form-select" name="destination_account_id" id="destination_account_id">
            @foreach ($destinationAccounts as $account)
              <option value="{{ $account->id }}" 
                  {{ old('destination_account_id') == $account->id ? 'selected' : '' }}>
                  {{ $account->name }}
              </option>
            @endforeach
          </select>
          <x-form-error name='destination_account_id'/>
        </x-form-field>

        <x-form-field>
          <x-form-label for="transaction_at">Transaction at</x-form-label>
          <x-form-input name='transaction_at' id="transaction_at" type="datetime-local" value="{{ old('transaction_at',now()) }}"/>
          <x-form-error name='transaction_at'/>
        </x-form-field>

        <x-form-field>
          <x-form-label for="amount">Amount</x-form-label>
          <x-form-input name='amount' id="amount" type="number" value="{{ old('amount',0) }}"/>
          <x-form-error name='amount'/>
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="admin_fee">Admin fee</x-form-label>
          <x-form-input name='admin_fee' id="admin_fee" type="number" value="{{ old('admin_fee',0) }}"/>
          <x-form-error name='admin_fee'/>
        </x-form-field>

        <hr class="my-4">
        <div class="text-end">
          <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back</a>
          <button type="submit" class="btn btn-primary" id="save-transaction-btn">Save</button>
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
        var button = document.getElementById('save-transaction-btn');
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