<x-layout>
  <x-slot:headingTitle>
    User
  </x-slot>
  <div class="row">
    <div class="col-12">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Status</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $user->name }}</td>
            <td>
              <span class="badge bg-{{ ($user->status == \App\Models\User::STATUS_ACTIVE) ? 'success' : 'danger' }}">
                {{ $user->status }}</span>
            </td>
            <td>{{ $user->created_at }}</td>
            <td>{{ $user->updated_at }}</td>
            <td class="d-inline-flex">
              <form action="{{ route('admin.users.status.update',$user->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-{{ ($user->status == \App\Models\User::STATUS_ACTIVE) ? 'danger' : 'success' }} patch-btn">
                  {{ ($user->status == \App\Models\User::STATUS_ACTIVE) ? 'Inactivate' : 'Activate' }}
                </button>
              </form>
              <a href="{{ route('admin.users.password.edit',$user->id) }}" class="btn btn-primary">Password</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div> 
        {{ $users->links() }}
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
            document.querySelectorAll('.patch-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the form from submitting immediately
                    let form = this.closest('form'); // Find the form associated with the delete button
                    // Trigger SweetAlert confirmation
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "user status will be change",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes!'
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