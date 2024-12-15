<x-layout>
  <x-slot:headingTitle>
    Category
  </x-slot>
  <x-alert name='alert' type='danger'/>
  <div class="text-start">
    @can('create-category')
      <a href="{{ route('categories.create') }}" class="btn btn-success">Create</a>
    @else
      <button type="submit" class="btn btn-success disable" disabled>Create</button>
    @endcan
  </div>
  <div class="row">
    <div class="col-12">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $category->name }}</td>
            <td>{{ $category->created_at }}</td>
            <td>{{ $category->updated_at }}</td>
            <td class="d-inline-flex">
              @can('edit-category', $category)
                <a href="{{ route('categories.edit',$category->id) }}" class="btn btn-primary me-1">Edit</a>
              @else
                <button type="submit" class="btn btn-primary me-1 disable" disabled>Edit</button>
              @endcan
              @can('delete-category', $category)
                <form action="{{ route('categories.destroy',$category->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger delete-btn">Delete</button>
                </form>
              @else
                <button type="submit" class="btn btn-danger disable" disabled>Delete</button>
              @endcan
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{-- pagination links --}}
      <div> 
        {{ $categories->links() }}
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