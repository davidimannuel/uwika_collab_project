<x-layout>
  <x-slot:headingTitle>
    Dashboard
  </x-slot>
  <div class="row">
    <div class="col-6">
      <canvas id="barChart"></canvas>
    </div>
    <div class="col-6">
      <canvas id="lineChart"></canvas>
    </div>
    <div class="col-6">
      <canvas id="pieChart"></canvas>
    </div>
  </div>

  @push('custom-js')
    <script src="{{ asset('assets/chartjs-4.4.1/chart.umd.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const barChart = document.getElementById('barChart');
        new Chart(barChart, {
          type: 'bar',
          data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
              label: '# of Votes',
              data: [12, 19, 3, 5, 2, 3],
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
        
        const lineChart = document.getElementById('lineChart');
        new Chart(lineChart, {
          data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [
              {
                label: 'Income',
                borderColor: 'rgba(211, 0, 0, 0.8)',
                type: 'line',
                data: [12, 25, 5, 6, 2, 3],
                borderWidth: 1
              },
              {
                label: 'Expenses',
                borderColor: 'rgba(0, 164, 24, 0.8)',
                type: 'line',
                data: [5, 19, 3, 5, 2, 10],
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
        
        const pieChart = document.getElementById('pieChart');
        new Chart(pieChart, {
          type: 'pie',
          data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
              label: '# of Votes',
              data: [12, 19, 3, 5, 2, 3],
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
      });
    </script>
  @endpush
</x-layout>