@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="content">
  <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
    <div class="flex-grow-1 mb-1 mb-md-0">
      <h1 class="h3 fw-bold mb-1">
        Dashboard
      </h1>
      <h2 class="h6 fw-medium fw-medium text-muted mb-0">
        Welcome <a class="fw-semibold" href="#">{{ auth()->user()->name }}</a>, everything looks great.
      </h2>
    </div>
    <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
      <a class="btn btn-sm btn-alt-secondary space-x-1" href="{{ route('admin.settings.index') }}">
        <i class="fa fa-cogs opacity-50"></i>
        <span>Settings</span>
      </a>
      <div class="dropdown d-inline-block">
        <button type="button" class="btn btn-sm btn-alt-secondary space-x-1" id="dropdown-analytics-overview" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-fw fa-calendar-alt opacity-50"></i>
          <span>All time</span>
          <i class="fa fa-fw fa-angle-down"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end fs-sm" aria-labelledby="dropdown-analytics-overview">
          <a class="dropdown-item fw-medium" href="{{ route('admin.dashboard', ['period'=>'30_days']) }}">Last 30 days</a>
          <a class="dropdown-item fw-medium" href="{{ route('admin.dashboard', ['period'=>'last_month']) }}">Last month</a>
          <a class="dropdown-item fw-medium" href="{{ route('admin.dashboard', ['period'=>'3_month']) }}">Last 3 months</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item fw-medium" href="{{ route('admin.dashboard', ['period'=>'year']) }}">This year</a>
          <a class="dropdown-item fw-medium" href="{{ route('admin.dashboard', ['period'=>'last_year']) }}">Last Year</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard', ['period'=>'all_time']) }}">
            <span>All time</span>
            <i class="fa fa-check"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
  <!-- Overview -->
  <div class="row items-push">
    <div class="col-sm-6 col-md-4 col-xxl-3">
      <!-- Pending Orders -->
      <div class="block block-rounded d-flex flex-column h-100 mb-0">
        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
          <dl class="mb-0">
            <dt class="fs-3 fw-bold">{{ $summary['pending'] ?? 0 }}</dt>
            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Pending Orders</dd>
          </dl>
          <div class="item item-rounded-lg bg-body-light">
            <i class="far fa-gem fs-3 text-primary"></i>
          </div>
        </div>
        <div class="bg-body-light rounded-bottom">
          <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('admin.orders.index') }}">
            <span>View all orders</span>
            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 col-xxl-3">
      <!-- New Customers -->
      <div class="block block-rounded d-flex flex-column h-100 mb-0">
        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
          <dl class="mb-0">
            <dt class="fs-3 fw-bold">{{ $totalCustomers ?? 0 }}</dt>
            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">New Customers</dd>
          </dl>
          <div class="item item-rounded-lg bg-body-light">
            <i class="far fa-user-circle fs-3 text-primary"></i>
          </div>
        </div>
        <div class="bg-body-light rounded-bottom">
          <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('admin.customers.index') }}">
            <span>View all customers</span>
            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
          </a>
        </div>
      </div>
      <!-- END New Customers -->
    </div>

    <div class="col-sm-6 col-md-4 col-xxl-3">
      <!-- New Customers -->
      <div class="block block-rounded d-flex flex-column h-100 mb-0">
        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
          <dl class="mb-0">
            <dt class="fs-3 fw-bold">{{ $summary['paid'] ?? 0 }}</dt>
            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Total Income</dd>
          </dl>
          <div class="item item-rounded-lg bg-body-light">
            <i class="fab fa-bitcoin fa-2x text-primary"></i>
          </div>
        </div>
        <div class="bg-body-light rounded-bottom">
          <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('admin.customers.index') }}">
            <span>Income</span>
            <!-- <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i> -->
          </a>
        </div>
      </div>
      <!-- END New Customers -->
    </div>

    {{-- <div class="col-sm-6 col-xxl-3">
      <!-- Messages -->
      <div class="block block-rounded d-flex flex-column h-100 mb-0">
        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
          <dl class="mb-0">
            <dt class="fs-3 fw-bold">45</dt>
            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Messages</dd>
          </dl>
          <div class="item item-rounded-lg bg-body-light">
            <i class="far fa-paper-plane fs-3 text-primary"></i>
          </div>
        </div>
        <div class="bg-body-light rounded-bottom">
          <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
            <span>View all messages</span>
            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
          </a>
        </div>
      </div>
      <!-- END Messages -->
    </div>
    <div class="col-sm-6 col-xxl-3">
      <!-- Conversion Rate -->
      <div class="block block-rounded d-flex flex-column h-100 mb-0">
        <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
          <dl class="mb-0">
            <dt class="fs-3 fw-bold">4.5%</dt>
            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Conversion Rate</dd>
          </dl>
          <div class="item item-rounded-lg bg-body-light">
            <i class="fa fa-chart-bar fs-3 text-primary"></i>
          </div>
        </div>
        <div class="bg-body-light rounded-bottom">
          <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
            <span>View statistics</span>
            <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
          </a>
        </div>
      </div>
      <!-- END Conversion Rate-->
    </div> --}}
  </div>
  <!-- END Overview -->

  {{--
  <!-- Statistics -->
  <div class="row">
    <div class="col-xl-8 col-xxl-9 d-flex flex-column">
      <!-- Earnings Summary -->
      <div class="block block-rounded flex-grow-1 d-flex flex-column">
        <div class="block-header block-header-default">
          <h3 class="block-title">Earnings Summary</h3>
          <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
              <i class="si si-refresh"></i>
            </button>
            <button type="button" class="btn-block-option">
              <i class="si si-settings"></i>
            </button>
          </div>
        </div>
        <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">

          <canvas id="js-chartjs-earnings"></canvas>
        </div>
        <div class="block-content bg-body-light">
          <div class="row items-push text-center w-100">
            <div class="col-sm-4">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                  <i class="fa fa-caret-up fs-base text-success"></i>
                  <span>2.5%</span>
                </dt>
                <dd class="fs-sm fw-medium text-muted mb-0">Customer Growth</dd>
              </dl>
            </div>
            <div class="col-sm-4">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                  <i class="fa fa-caret-up fs-base text-success"></i>
                  <span>3.8%</span>
                </dt>
                <dd class="fs-sm fw-medium text-muted mb-0">Page Views</dd>
              </dl>
            </div>
            <div class="col-sm-4">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold d-inline-flex align-items-center space-x-2">
                  <i class="fa fa-caret-down fs-base text-danger"></i>
                  <span>1.7%</span>
                </dt>
                <dd class="fs-sm fw-medium text-muted mb-0">New Products</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
      <!-- END Earnings Summary -->
    </div>
    <div class="col-xl-4 col-xxl-3 d-flex flex-column">
      <div class="row items-push flex-grow-1">
        <div class="col-md-6 col-xl-12">
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div class="block-content flex-grow-1 d-flex justify-content-between">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">570</dt>
                <dd class="fs-sm fw-medium text-muted mb-0">Total Orders</dd>
              </dl>
              <div>
                <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-danger bg-danger-light">
                  <i class="fa fa-caret-down me-1"></i>
                  2.2%
                </div>
              </div>
            </div>
            <div class="block-content p-1 text-center overflow-hidden">
              <!-- Total Orders Chart Container -->
              <canvas id="js-chartjs-total-orders" style="height: 90px;"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-12">
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div class="block-content flex-grow-1 d-flex justify-content-between">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">$5,234.21</dt>
                <dd class="fs-sm fw-medium text-muted mb-0">Total Earnings</dd>
              </dl>
              <div>
                <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-success bg-success-light">
                  <i class="fa fa-caret-up me-1"></i>
                  4.2%
                </div>
              </div>
            </div>
            <div class="block-content p-1 text-center overflow-hidden">
              <!-- Total Earnings Chart Container -->
              <canvas id="js-chartjs-total-earnings" style="height: 90px;"></canvas>
            </div>
          </div>
        </div>
        <div class="col-xl-12">
          <div class="block block-rounded d-flex flex-column h-100 mb-0">
            <div class="block-content flex-grow-1 d-flex justify-content-between">
              <dl class="mb-0">
                <dt class="fs-3 fw-bold">264</dt>
                <dd class="fs-sm fw-medium text-muted mb-0">New Customers</dd>
              </dl>
              <div>
                <div class="d-inline-block px-2 py-1 rounded-3 fs-xs fw-semibold text-success bg-success-light">
                  <i class="fa fa-caret-up me-1"></i>
                  9.3%
                </div>
              </div>
            </div>
            <div class="block-content p-1 text-center overflow-hidden">
              <!-- New Customers Chart Container -->
              <canvas id="js-chartjs-new-customers" style="height: 90px;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <!-- END Last 2 Weeks -->
    </div>
  </div>
  <!-- END Statistics -->
  --}}
  <!-- Recent Orders -->
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Recent Orders</h3>
      {{-- <div class="block-options space-x-1">
        <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#one-dashboard-search-orders" data-class="d-none">
          <i class="fa fa-search"></i>
        </button>
        <div class="dropdown d-inline-block">
          <button type="button" class="btn btn-sm btn-alt-secondary" id="dropdown-recent-orders-filters" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-flask"></i>
            Filters
            <i class="fa fa-angle-down ms-1"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-md dropdown-menu-end fs-sm" aria-labelledby="dropdown-recent-orders-filters">
            <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
              Pending
              <span class="badge bg-primary rounded-pill">20</span>
            </a>
            <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
              Active
              <span class="badge bg-primary rounded-pill">72</span>
            </a>
            <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
              Completed
              <span class="badge bg-primary rounded-pill">890</span>
            </a>
            <a class="dropdown-item fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
              All
              <span class="badge bg-primary rounded-pill">997</span>
            </a>
          </div>
        </div>
      </div> --}}
    </div>

    <div class="block-content block-content-full">
      <!-- Recent Orders Table -->
      <div class="table-responsive">
        <table class="table table-hover table-vcenter">
          <thead>
            <tr>
              <th>Order ID</th>
              <th class="d-none d-xl-table-cell">Customer</th>
              <th>Status</th>
              <th class="d-none d-sm-table-cell text-end">Created</th>
              <th class="d-none d-sm-table-cell text-end">Value</th>
            </tr>
          </thead>
          <tbody class="fs-sm">
            @forelse ($orders as $order)
            <tr>
              <td>
                <a class="fw-semibold" href="{{ route('admin.orders.edit', $order->id) }}">#ORDER-{{$order->id}}</a>
              </td>
              <td class="d-none d-xl-table-cell">
                <a class="fw-semibold" href="javascript:void(0)">{{ $order->user->name }}</a>
              </td>
              <td>
                <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-{{ App\Enums\OrderStatusEnum::getCssColor($order->status) }}-light text-{{ App\Enums\OrderStatusEnum::getCssColor($order->status) }} text-capitalize">{{ $order->status->value }}</span>
              </td>
              <td class="d-none d-sm-table-cell fw-semibold text-muted text-end">{{ $order->created_at->diffForHumans() }}</td>
              <td class="d-none d-sm-table-cell text-end">
                <strong>{{ AppHelper::moneyWithSymbol($order->total) }}</strong>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5">😀 No Orders Yet</td>
            </tr>

            @endforelse
          </tbody>
        </table>
      </div>
      <!-- END Recent Orders Table -->
    </div>
    <div class="block-content block-content-full bg-body-light">
      {{ $orders->links() }}
    </div>
  </div>
  <!-- END Recent Orders -->
</div>
<!-- END Page Content -->
@endsection

@section('js')
{{-- <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/chart.js/chart.umd.js') }}"></script>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    class pageDashboard {
      /*
       * Init Charts
       *
       */
      static initCharts() {
        // Set Global Chart.js configuration
        Chart.defaults.color = '#818d96';
        Chart.defaults.scale.grid.lineWidth = 0;
        Chart.defaults.scale.beginAtZero = true;
        Chart.defaults.datasets.bar.maxBarThickness = 45;
        Chart.defaults.elements.bar.borderRadius = 4;
        Chart.defaults.elements.bar.borderSkipped = false;
        Chart.defaults.elements.point.radius = 0;
        Chart.defaults.elements.point.hoverRadius = 0;
        Chart.defaults.plugins.tooltip.radius = 3;
        Chart.defaults.plugins.legend.labels.boxWidth = 10;

        // Get Chart Containers
        let chartEarningsCon = document.getElementById('js-chartjs-earnings');
        let chartTotalOrdersCon = document.getElementById('js-chartjs-total-orders');
        let chartTotalEarningsCon = document.getElementById('js-chartjs-total-earnings');
        let chartNewCustomersCon = document.getElementById('js-chartjs-new-customers');

        // Set Chart and Chart Data variables
        let chartEarnings, chartTotalOrders, chartTotalEarnings, chartNewCustomers;

        // Init Chart Earnings
        if (chartEarningsCon !== null) {
          chartEarnings = new Chart(chartEarningsCon, {
            type: 'bar',
            data: {
              labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
              datasets: [{
                  label: 'This Week',
                  fill: true,
                  backgroundColor: 'rgba(100, 116, 139, .7)',
                  borderColor: 'transparent',
                  pointBackgroundColor: 'rgba(100, 116, 139, 1)',
                  pointBorderColor: '#fff',
                  pointHoverBackgroundColor: '#fff',
                  pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
                  data: [716, 628, 1056, 560, 956, 890, 790]
                },
                {
                  label: 'Last Week',
                  fill: true,
                  backgroundColor: 'rgba(100, 116, 139, .15)',
                  borderColor: 'transparent',
                  pointBackgroundColor: 'rgba(100, 116, 139, 1)',
                  pointBorderColor: '#fff',
                  pointHoverBackgroundColor: '#fff',
                  pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
                  data: [1160, 923, 1052, 1300, 880, 926, 963]
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                x: {
                  display: false,
                  grid: {
                    drawBorder: false
                  }
                },
                y: {
                  display: false,
                  grid: {
                    drawBorder: false
                  }
                }
              },
              interaction: {
                intersect: false,
              },
              plugins: {
                legend: {
                  labels: {
                    boxHeight: 10,
                    font: {
                      size: 14
                    }
                  }
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return context.dataset.label + ': $' + context.parsed.y;
                    }
                  }
                }
              }
            }
          });
        }

        // Init Chart Total Orders
        if (chartTotalOrdersCon !== null) {
          chartTotalOrders = new Chart(chartTotalOrdersCon, {
            type: 'line',
            data: {
              labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
              datasets: [{
                label: 'Total Orders',
                fill: true,
                backgroundColor: 'rgba(220, 38, 38, .15)',
                borderColor: 'transparent',
                pointBackgroundColor: 'rgba(220, 38, 38, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(220, 38, 38, 1)',
                data: [33, 29, 32, 37, 38, 30, 34, 28, 43, 45, 26, 45, 49, 39],
              }]
            },
            options: {
              maintainAspectRatio: false,
              tension: .4,
              scales: {
                x: {
                  display: false
                },
                y: {
                  display: false
                }
              },
              interaction: {
                intersect: false,
              },
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return ' ' + context.parsed.y + ' Orders';
                    }
                  }
                }
              }
            }
          });
        }

        // Init Chart Total Earnings
        if (chartTotalEarningsCon !== null) {
          chartTotalEarnings = new Chart(chartTotalEarningsCon, {
            type: 'line',
            data: {
              labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
              datasets: [{
                label: 'Total Earnings',
                fill: true,
                backgroundColor: 'rgba(101, 163, 13, .15)',
                borderColor: 'transparent',
                pointBackgroundColor: 'rgba(101, 163, 13, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(101, 163, 13, 1)',
                data: [716, 1185, 750, 1365, 956, 890, 1200, 968, 1158, 1025, 920, 1190, 720, 1352],
              }]
            },
            options: {
              maintainAspectRatio: false,
              tension: .4,
              scales: {
                x: {
                  display: false
                },
                y: {
                  display: false
                }
              },
              interaction: {
                intersect: false,
              },
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return ' $' + context.parsed.y;
                    }
                  }
                }
              }
            }
          });
        }

        // Init Chart New Customers
        if (chartNewCustomersCon !== null) {
          chartNewCustomers = new Chart(chartNewCustomersCon, {
            type: 'line',
            data: {
              labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
              datasets: [{
                label: 'Total Orders',
                fill: true,
                backgroundColor: 'rgba(101, 163, 13, .15)',
                borderColor: 'transparent',
                pointBackgroundColor: 'rgba(101, 163, 13, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(101, 163, 13, 1)',
                data: [25, 15, 36, 14, 29, 19, 36, 41, 28, 26, 29, 33, 23, 41],
              }]
            },
            options: {
              maintainAspectRatio: false,
              tension: .4,
              scales: {
                x: {
                  display: false
                },
                y: {
                  display: false
                }
              },
              interaction: {
                intersect: false,
              },
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return ' ' + context.parsed.y + ' Customers';
                    }
                  }
                }
              }
            }
          });
        }
      }

      /*
       * Init functionality
       *
       */
      static init() {
        this.initCharts();
      }
    }

    // Initialize when page loads
    One.onLoad(() => pageDashboard.init());
  });
</script> --}}
@endsection