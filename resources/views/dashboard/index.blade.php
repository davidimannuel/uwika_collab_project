<x-layout>
  <x-slot:headingTitle>
    Dashboard {{auth()->user()->name}} 
  </x-slot>
  <div class="row">
    <div class="col-4">
      <canvas id="incomeThisMonthByCategoryChart"></canvas>
    </div>
    <div class="col-8">
      <canvas id="incomeExpensesThisYearByAccountChart"></canvas>
    </div>
  </div>
  <div class="row">
    <div class="col-4">
      <canvas id="expensesThisMonthByCategoryChart"></canvas>
    </div>
    <div class="col-8">
      <canvas id="expenseIncomeThisYearByMonthChart"></canvas>
    </div>
  </div>

  @push('custom-js')
    <script src="{{ asset('assets/chartjs-4.4.1/chart.umd.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // incomeExpensesThisYearByAccountChart
        // Fetch data from your route
        fetch("{{ route('dashboard.incomeExpensesThisYearByAccount') }}")  // Replace this with your actual URL
          .then(response => response.json())
          .then(data => {
            // Extract account names, income, and expense values from the response data
            const accountNames = data.data.map(item => item.account_name);
            const incomeData = data.data.map(item => parseFloat(item.total_income));
            const expenseData = data.data.map(item => parseFloat(item.total_expense));

            // Create the chart
            const incomeExpensesThisYearByAccountChart = document.getElementById('incomeExpensesThisYearByAccountChart');
            new Chart(incomeExpensesThisYearByAccountChart, {
              type: 'bar',
              data: {
                labels: accountNames, // Account names as the labels
                datasets: [
                  {
                    label: 'Income',
                    data: incomeData, // Income data for each account
                    backgroundColor: 'rgba(0, 164, 24, 0.8)',
                    borderWidth: 1
                  },
                  {
                    label: 'Expenses',
                    data: expenseData, // Expense data for each account
                    backgroundColor: 'rgba(211, 0, 0, 0.8)',
                    borderWidth: 1
                  }
                ]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          })
          .catch(error => {
            console.error('Error fetching incomeExpensesThisYearByAccountChart data:', error);
          });

        
        // expenseIncomeThisYearByMonthChart
        fetch("{{ route('dashboard.incomeExpensesThisYearByMonth') }}")
          .then(response => response.json())
          .then(result => {
            const data = result.data;
            // Extract labels and data from the API response
            const labels = data.map(item => `${item.month}`);
            const incomeData = data.map(item => parseFloat(item.total_income));
            const expenseData = data.map(item => parseFloat(item.total_expense));
            const expenseIncomeThisYearByMonthChart = document.getElementById('expenseIncomeThisYearByMonthChart');
            new Chart(expenseIncomeThisYearByMonthChart, {
              data: {
                labels: labels,
                datasets: [
                  {
                    label: 'Income',
                    borderColor: 'rgba(211, 0, 0, 0.8)',
                    type: 'line',
                    data: incomeData,
                    borderWidth: 1
                  },
                  {
                    label: 'Expenses',
                    borderColor: 'rgba(0, 164, 24, 0.8)',
                    type: 'line',
                    data: expenseData,
                    borderWidth: 1
                  }
                ]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          }).catch(error => {
            console.error('Error fetching expenseIncomeThisYearByMonthChart data:', error);
          });

        // expenses income ThisMonthByCategoryChart
        fetch("{{ route('dashboard.incomeExpensesByCategoryThisMonth') }}")
          .then(response => response.json())
          .then(result => {
            const data = result.data;
            // Initialize empty arrays for income and expense
            const incomeLabels = [];
            const expenseLabels = [];
            const incomeData = [];
            const expenseData = [];

            // Iterate through the data
            data.forEach(item => {
              // If the income is greater than 0, add to income arrays
              if (parseFloat(item.total_income) > 0) {
                incomeLabels.push(item.category);
                incomeData.push(parseFloat(item.total_income));
              }

              // If the expense is greater than 0, add to expense arrays
              if (parseFloat(item.total_expense) > 0) {
                expenseLabels.push(item.category);
                expenseData.push(parseFloat(item.total_expense));
              }
            });
            const expensesThisMonthByCategoryChart = document.getElementById('expensesThisMonthByCategoryChart');
            new Chart(expensesThisMonthByCategoryChart, {
              type: 'pie',
              data: {
                labels: expenseLabels,
                datasets: [{
                  label: 'Expenses by Category this Month',
                  data: expenseData,
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
            
            const incomeThisMonthByCategoryChart = document.getElementById('incomeThisMonthByCategoryChart');
            new Chart(incomeThisMonthByCategoryChart, {
              type: 'pie',
              data: {
                labels: incomeLabels,
                datasets: [{
                  label: 'Income by Category this Month',
                  data: incomeData,
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });
          }).catch(error => {
            console.error('Error fetching expenseIncomeThisYearByMonthChart data:', error);
          });
      });
    </script>
  @endpush
</x-layout>