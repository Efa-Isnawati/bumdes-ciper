@extends('layouts.admin')

@section('title')
    Dashboard
@endsection

@section('content')
<!-- Section Content -->
<div
    class="section-content section-dashboard-home"
    data-aos="fade-up"
    >
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Admin Dashboard</h2>
            <p class="dashboard-subtitle">
                This is Bumdes Umkm Administrator Panel
            </p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-2">
                        <div class="card-body">
                          <a href="{{'admin/customer'}}">
                            <div class="dashboard-card-title">
                                Customer
                            </div>
                            <div class="dashboard-card-subtitle">
                                {{ $customer }}
                            </div>
                        </a></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="dashboard-card-title">
                                Revenue
                            </div>
                            <div class="dashboard-card-subtitle">
                                 Rp {{ number_format($revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="dashboard-card-title">
                                Transaction
                            </div>
                            <div class="dashboard-card-subtitle">
                                {{ $transaction }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ... Your HTML code ... -->

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="dashboard-card-title">
            Total Pesanan masuk perbulan
          </div>
          <div class="dashboard-card-subtitle">
            {{-- {!! $chart->render() !!} --}}
            <canvas id="transactions-chart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

 <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dashboard-card-title">
                            {{-- Total Pesanan masuk perbulan --}}
                        </div>
                        <div class="dashboard-card-subtitle">
                            <canvas id="transactions-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var chartData = {
        labels: {!! $labels !!}, // Use the PHP variable for labels
        datasets: [
            {
                label: 'Transaksi Bulanan',
                data: {!! $data !!}, // Use the PHP variable for data
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            },
        ],
    };

    var chartCanvas = document.getElementById('transactions-chart').getContext('2d');
    new Chart(chartCanvas, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                },
            },
        },
    });
</script>
@endsection