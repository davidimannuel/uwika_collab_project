<x-layout>
  <x-slot:headingTitle>
    Account
  </x-slot>
  <x-alert name='alert' type='danger'/>
  <div class="text-start">
    <a href="{{ route('accounts.create') }}" class="btn btn-success">Create</a>
  </div>
  <div class="row">
    @foreach ($accounts as $account)
      <div class="col-4">
        <div class="card mt-3">
          <div class="card-body">
            <h5 class="card-title">{{ $account->name }}</h5>
            <p class="card-text">Rp. {{ number_format($account->balance, 0, ',', '.') }}</p>
          </div>
          <div class="card-body d-inline-flex">
            <a href="{{ route('accounts.transactions.index',$account->id) }}" class="btn btn-success me-1">Transactions</a>
            <a href="{{ route('accounts.edit',$account->id) }}" class="btn btn-primary me-1">Edit</a>
            <a href="{{ route('accounts.transfer',$account->id) }}" class="btn btn-secondary me-1">Transfer</a>
            <form action="{{ route('accounts.destroy',$account->id) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger delete-btn">Delete</button>
            </form>
          </div>
          <div class="card-footer">
            <small class="text-body-secondary">Created {{ $account->created_at->diffForHumans() }}, </small>
            <small class="text-body-secondary">Last updated {{ $account->updated_at->diffForHumans() }}</small>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="row">
    {{-- pagination links --}}
    <div> 
      {{ $accounts->links() }}
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
            document.querySelectorAll('.delete-btn').forEach(function(button) {
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
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form if the user confirms
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>
  @endpush
</x-layout>