<x-layout>
  <x-slot:headingTitle>
    Create transaction
  </x-slot>
  <form method="POST" action="{{ route('accounts.transactions.store',$account->id) }}" >
    @csrf
    <div class="row">
      <div class="col-6">
        <x-form-field>
          <x-form-label for="transaction_at">Transaction at</x-form-label>
          <x-form-input name='transaction_at' id="transaction_at" type="datetime-local" value="{{ old('transaction_at') }}"/>
          <x-form-error name='transaction_at'/>
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="type">Type</x-form-label>
          <select class="form-select" id="type" name="type">
            <option selected>Choose...</option>
            <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>Income</option>
            <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Expenses</option>
          </select>
          <x-form-error name='type'/>
        </x-form-field>
  
        <x-form-field>
          <x-form-label for="remark">Remark</x-form-label>
          <x-form-input name='remark' id="remark" type="text" value="{{ old('remark') }}"/>
          <x-form-error name='remark'/>
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="amount">Amount</x-form-label>
          <x-form-input name='amount' id="amount" type="number" value="{{ old('amount') }}"/>
          <x-form-error name='amount'/>
        </x-form-field>
      </div>
      <div class="col-6">
        <x-form-field>
          <x-form-label>Account</x-form-label>
          <x-form-input type="text" value="{{ $account->name }}" disabled/>
        </x-form-field>
        
        <x-form-field>
          <x-form-label for="remark">Balance</x-form-label>
          <x-form-input type="number" value="{{ $account->balance }}" disabled/>
        </x-form-field>
  
        <x-form-field>
          <x-form-label for="categories">Categories</x-form-label>
          <select class="form-select" name="categories[]" id="categories" multiple style="height: 200px;">
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" 
                  {{ collect(old('categories'))->contains($category->id) ? 'selected' : '' }}>
                  {{ $category->name }}
              </option>
            @endforeach
          </select>
          <x-form-error name='categories'/>
        </x-form-field>
      </div>
      <hr class="my-4">
      <div class="text-end">
        <a href="{{ route('accounts.transactions.index', $account->id) }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary" id="save-transaction-btn">Save</button>
      </div>
    </div>
  </form>
  
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
      });
    </script>
  @endpush
</x-layout>
