<x-layout>
  <x-slot:headingTitle>
    Debt
  </x-slot>
  <x-alert name='success-alert' type='success'/>
  <div class="text-start">
    <a href="{{ route('debts.index') }}" class="btn btn-primary">Back</a>
    @if ($debt->status !== 'paid')
      @can('create-debt-repayment')
        <a href="{{ route('debts.repayments.create', $debt->id) }}" class="btn btn-success">Pay debt</a>
      @else
        <button type="submit" class="btn btn-success disable" disabled>Pay debt</button>
      @endcan
    @endif
  </div>
  <div class="row">
    <div class="col-8">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Account</th>
            <th>Remark</th>
            <th>Amount</th>
            <th>Transaction At</th>
            <th>Categories</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($debt->repayments as $repayment)
          <tr>
            <th>{{ $loop->iteration }}</th>
            <td>{{ $repayment->transaction->account->name }}</td>
            <td>{{ $repayment->transaction->remark }}</td>
            <td>
              @if ($repayment->transaction->type === 'debit')
                <span class="badge bg-success">{{ number_format($repayment->transaction->amount, 0, ',', '.') }}</span>
              @else
                <span class="badge bg-danger">-{{ number_format($repayment->transaction->amount, 0, ',', '.') }}</span>
              @endif
            </td>
            <td>{{ $repayment->transaction->transaction_at }}</td>
            <td>
              @foreach ($repayment->transaction->categories as $category)
                  @php
                      // Determine badge color based on the iteration index
                      $badgeClass = '';
                      if ($category->id % 4 == 0) {
                          $badgeClass = 'dark'; // Red for even indexes
                      } else if ($category->id % 3 == 0) {
                          $badgeClass = 'info'; // Red for even indexes
                      } else if ($category->id % 2 == 0) {
                          $badgeClass = 'primary'; // Red for even indexes
                      } else {
                          $badgeClass = 'success'; // Yellow for odd indexes
                      }
                  @endphp
                  <span class="badge rounded-pill text-bg-{{ $badgeClass }}">
                      {{ $category->name }}
                  </span>
              @endforeach
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="col-4">
      <x-form-field>
        <x-form-label>Account</x-form-label>
        <x-form-input type="text" value="{{ $debt->transaction->account->name }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Remark</x-form-label>
        <x-form-input type="text" value="{{ $debt->transaction->remark }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Type</x-form-label>
        <x-form-input type="text" value="{{ $debt->transaction->type == 'debit' ? 'Expenses' : 'Income' }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Amount</x-form-label>
        <x-form-input type="text" value="Rp. {{ number_format($debt->transaction->amount, 0, ',', '.') }}" disabled/>
      </x-form-field>
      
      <x-form-field>
        <x-form-label>Paid Amount</x-form-label>
        <x-form-input type="text" value="Rp. {{ number_format($debt->paid_amount, 0, ',', '.') }}" disabled/>
      </x-form-field>
    </div>
  </div>
</x-layout>