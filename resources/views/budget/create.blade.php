<x-layout>
  <x-slot:headingTitle>
    Create budget
  </x-slot>
  
  <form method="POST" action="{{ route('budgets.store') }}" >
  @csrf
    <div class="row">
      <div class="col-6">
          <x-form-field>
            <x-form-label for="name">Name</x-form-label>
            <x-form-input name='name' type="text"/>
            <x-form-error name='name'/>
          </x-form-field>

          <x-form-field>
            <x-form-label for="category_id">Category</x-form-label>
            <select class="form-select" name="category_id" id="category_id">
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" 
                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
              @endforeach
            </select>
            <x-form-error name='category_id'/>
          </x-form-field>

          <x-form-field>
            <x-form-label for="threshold_amount">Threshold amount</x-form-label>
            <x-form-input name='threshold_amount' id="threshold_amount" type="number" value="{{ old('threshold_amount',0) }}"/>
            <x-form-error name='threshold_amount'/>
          </x-form-field>

          <x-form-field>
            <x-form-label for="transaction_type">Transaction Type</x-form-label>
            <select class="form-select" id="transaction_type" name="transaction_type">
              <option value="debit" {{ old('transaction_type') == 'debit' ? 'selected' : '' }}>Income</option>
              <option value="credit" {{ old('transaction_type') == 'credit' ? 'selected' : '' }}>Expenses</option>
            </select>
            <x-form-error name='transaction_type'/>
          </x-form-field>
      </div>
      
      <div class="col-6">
        <x-form-field>
          <x-form-label for="start_at">Start at</x-form-label>
          <x-form-input name='start_at' id="start_at" type="date" value="{{ old('start_at', now()->format('Y-m-d')) }}"/>
          <x-form-error name='start_at'/>
        </x-form-field>

        <x-form-field>
          <x-form-label for="end_at">End at</x-form-label>
          <x-form-input name='end_at' id="end_at" type="date" value="{{ old('end_at', now()->format('Y-m-d')) }}"/>
          <x-form-error name='end_at'/>
        </x-form-field>
      </div>

      <hr class="my-4">
      <div class="text-end">
        <a href="{{ route('budgets.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    <div>
  </form>
</x-layout>