<x-layout>
  <x-slot:headingTitle>
    Account
  </x-slot>
  <x-alert name='alert'/>
  <div class="text-start">
    <a href="{{ route('accounts.create') }}" class="btn btn-success">Create</a>
  </div>
  <div class="row">
    <div class="col-11">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Balance</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($accounts as $account)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $account->name }}</td>
            <td>{{ $account->created_at }}</td>
            <td>{{ $account->updated_at }}</td>
            <td>{{ number_format($account->balance, 0, ',', '.') }}</td>
            <td class="d-inline-flex">
              <a href="{{ route('accounts.transactions.index',$account->id) }}" class="btn btn-success me-1">Transactions</a>
              <a href="{{ route('accounts.edit',$account->id) }}" class="btn btn-primary me-1">Edit</a>
              <form action="{{ route('accounts.destroy',$account->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger delete-btn">Delete</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{-- pagination links --}}
      <div> 
        {{ $accounts->links() }}
      </div>
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