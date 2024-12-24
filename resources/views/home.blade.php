@extends('layouts.app')
@section('title', 'Dashboard')
@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-xxl-8 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-8">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Congratulations {{auth()->user()->name}}! 🎉</h5>
                            <p class="mb-6">
                                Tracking your expenses is a great step towards better financial management.
                                <br />Click below to start tracking your expenses.
                            </p>

                            <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">View
                                Expense</a>
                        </div>
                    </div>

                    <div class="col-sm-4 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="../assets/img/illustrations/man-with-laptop.png" height="175" class="scaleX-n1-rtl"
                                alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">

            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Income</p>
                            <h4 class="card-title mb-3">RS. {{$transaction_income_total}}</h4>
                            {{-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +72.80%</small> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="../assets/img/icons/unicons/wallet-info.png" alt="wallet info"
                                        class="rounded" />
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Expense</p>
                            <h4 class="card-title mb-3">RS. {{$transaction_expense_total}}</h4>
                            {{-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Revenue -->
        <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
            <div class="card">
                <div class="card-header">
                    <form action="{{route('transactions.date-wise')}}" method="GET" id="date_range">
                        <div class="col-md-12 d-flex align-items-center">
                            <div class="col-2 mt-3">
                                <p class="fw-bold">Date Range (Bar)</p>
                            </div>

                            <div class="col-2 p-2">
                                <select name="type" class="form-select" id="">
                                    <option value="date">Datewise</option>
                                    <option value="month">Monthly</option>
                                    <option value="year">Yearly</option>
                                </select>
                            </div>

                            <div class="col-4">

                                <input type="text" name="dates" class="form-control">
                            </div>

                            <div class="col-2 p-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>

                        </div>
                    </form>


            </div>

            </div>
        </div>


            <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                <div class="card">

                    <div class="row row-bordered g-0">
                        <div class="col-lg-12">
                            <div class="card-header">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Total Revenue</h5>
                                </div>
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!--/ Total Revenue -->
            <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
                <div class="row">

                    <div class="col-12 mb-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Category Difference</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endsection
    @push('js')

        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            
            $('input[name="dates"]').daterangepicker();
        </script>
        <script>
            $('#date_range').on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('transactions.date-wise')}}",
                    data: $(this).serialize(),
                    success:function(data){
                        let char_2  = Chart.getChart("myChart");
                        char_2.destroy();

                        chart(
                            data.transaction_date, data.transaction_expense ,  data.transaction_income,
                        )
                    }
                });
            })
        </script>
        <script>
            const ctx = document.getElementById('myChart');

            let date = {!! $transaction_date !!}
            let transaction_expense = {!! $transaction_expense !!}
            let transaction_income = {!! $transaction_income !!}

            chart(date,transaction_expense,transaction_income);

            function chart(date,transaction_expense,transaction_income){

                new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: date,
                    datasets: [
                        {
                            label: 'Expense',
                            data: transaction_expense,
                            borderWidth: 1
                        },
                        {
                            label: 'Income',
                            data: transaction_income,
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
            }

            const piechart = document.getElementById('pieChart');

            const rgb = [];

            var categories = {!! $categories->keys() !!}
            categories.forEach((index,element )=> {

                rgb.push(getRandomRgb(element));
            });


            new Chart(piechart, {
                type: 'pie',
                data: {
                    labels: categories,
                    datasets: [{
                        label: 'Income',
                        data: {!! $categories->values() !!},
                        backgroundColor: rgb,
                        hoverOffset: categories.length
                    },
                    {
                        label: 'Expense',
                        data: {!! $category_expense->values() !!},
                        backgroundColor: rgb,
                        hoverOffset: categories.length
                    },
                    ]
                },

            });

            function getRandomRgb(element) {
                var num = Math.round(0xffffff * Math.random() + element);
                var r = num >> 16;
                var g = num >> 8 & 255;
                var b = num & 255;
                return 'rgb(' + r + ', ' + g + ', ' + b + ')';
            }

        </script>
    @endpush
