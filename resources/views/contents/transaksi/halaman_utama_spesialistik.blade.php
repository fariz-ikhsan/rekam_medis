
@include('layouts.header')
<div class="content">
    <!-- BEGIN: Top Bar -->
    <div class="top-bar">
        <!-- BEGIN: Breadcrumb -->
        <div class="-intro-x breadcrumb mr-auto hidden sm:flex"> </div>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
       @include('components.account_dropdown')
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
 
    <div class="col-span-12 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium mr-auto" style="font-size: 30px;">
                Informasi Kunjungan
            </h2>
            @if(Session::has('sukses'))
            <div id="suksesDiv" class="rounded-md flex items-center justify-center px-5 py-4 mb-2 bg-theme-18 text-theme-9">
                <i data-feather="check" class="w-6 h-6 mr-2"></i>
                <span class="text-center font-bold"> {{ Session::get('sukses') }}</span>
                <script>
                    const div = document.getElementById('mysuksesDivDiv');
                    setTimeout(() => {
                        div.style.display = 'none';
                    }, 2000);
                </script>
            </div>
            @endif
            <a href="" class="ml-auto flex text-theme-1 dark:text-theme-10"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw w-4 h-4 mr-3"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg> Reload Data </a>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5" id="dashboardAntrianPasien">
            <script>
                document.getElementById('dashboardAntrianPasien').addEventListener('click', function() {         
                  window.location.href = "{{ route('antrianpasien-spesialistik') }}";
                });
            </script>
            <div class="col-span-12  sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <div class="text-base text-gray-600 mt-1">Antrian Pasien</div>
                        </div>
                        <div class="flex justify-between items-center">                          
                            @foreach ($antrian as $record)
                                <div class="text-3xl font-bold leading-8 mt-6">{{ $record->hitung }}</div>
                            @endforeach
                            <i data-feather="users" class="icon-lg" style="width: 50px; height: 50px;"></i>
                        </div>           
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <div class="text-base text-gray-600 mt-1">Kunjungan Hari ini</div>
                        </div>   
                        <div class="flex justify-between items-center">                          
                            @foreach ($jmlKunjunganHariIni as $record)
                                <div class="text-3xl font-bold leading-8 mt-6">{{ $record->hitung }}</div>
                            @endforeach
                            <i data-feather="user-check" class="icon-lg" style="width: 50px; height: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-span-12  sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <div class="text-base text-gray-600 mt-1">Total Kunjungan Bulan ini</div>
                        </div>
                        <div class="flex justify-between items-center"> 
                            @foreach ($jmlKunjunganBlnIni as $record)
                                <div class="text-3xl font-bold leading-8 mt-6">{{ $record->hitung }}</div>
                            @endforeach
                            <i data-feather="calendar" class="icon-lg" style="width: 50px; height: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12  sm:col-span-6 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <div class="text-base text-gray-600 mt-1">Total Kunjungan Keseluruhan</div>
                        </div>
                        <div class="flex justify-between items-center"> 
                            @foreach ($jmlKunjunganKeseluruhan as $record)
                                <div class="text-3xl font-bold leading-8 mt-6">{{ $record->hitung }}</div>
                            @endforeach
                            <i data-feather="pie-chart" class="icon-lg" style="width: 50px; height: 50px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <div id="chart_div" style="width: 69%; height: 400px; min-height: 300px; max-height: 800px; margin-top: 40px;"></div>
            <div id="pie_chart" style="width: 29%; height: 400px;  margin-top: 40px;"></div>
        </div>
        

        <script type="text/javascript">
            var dateArray = []; // Initialize an empty array for dates
            var countArray = []; // Initialize an empty array for counts
             var colors = ["#007FFF","#FF2400","#FF5E0E"]

            @php
                $dataArray = json_decode($chart, true);
                if (!empty($dataArray)) {
                    $keys = array_keys($dataArray[0]);
                } else {
                    $keys = ["NULL"];
                }
            @endphp

            @foreach ($dataArray as $data)
                dateArray.push('{{ $data["bulan"] }}');
                countArray.push('{{ $data["jumlah_data"] }}');
            @endforeach
            
            var countArray = [5, 8, 3];
            var total = countArray.reduce(function (acc, val) {
                return acc + val;
            }, 0);

            var percentageArray = countArray.map(function (value) {
                return (value / total) * 100;
            });

            function convertToMonthNameFormat(dateStr) {
                // Array of month names
                var monthNames = [
                    "January", "February", "March", "April",
                    "May", "June", "July", "August",
                    "September", "October", "November", "December"
                ];

                // Split the date into year and month
                var [year, month] = dateStr.split('-');

                // Get the month name
                var monthName = monthNames[parseInt(month) - 1];

                // Combine the month name and year
                return monthName;
                }

                // Convert each date in dateArray to month name format
                var convertedArray = dateArray.map(convertToMonthNameFormat);

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
          
            function drawChart() {
                var data = new google.visualization.DataTable();

                data.addColumn('string', 'Month');
                data.addColumn('number', 'Count');
                data.addColumn({ role: 'style' }, 'Color');


                // Buat array yang berisi data yang akan dimasukkan ke dalam tabel
                var chartData = [];
                for (var i = 0; i < convertedArray.length; i++) {
                    chartData.push([convertedArray[i], parseInt(countArray[i]), colors[i]]);
                }

                // Masukkan data ke dalam tabel
                data.addRows(chartData);

                
                var options = {
                title: 'Vertical Bar Chart',
                vAxis: {
                    title: 'Jumlah Pasien',
                    format: '0'
                },
                legend: 'none', 
                hAxis: {
                    title: 'Bulan'
                },
                bars: 'vertical'
                };
            
                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            
                function resizeChart() {
                chart.draw(data, options);
                }
                
                // Menggunakan event handler window.onresize untuk mengatur ulang chart saat lebar layar berubah
                window.addEventListener('resize', resizeChart);
                resizeChart(); // Menggambar chart pertama kali
            }
          </script>

            <script type="text/javascript">
                google.charts.load('current', { 'packages': ['corechart'] });
                google.charts.setOnLoadCallback(drawChart);
           
                function drawChart() {
                    var data = new google.visualization.DataTable();

                    // Tambahkan kolom pertama dengan label 'Month' dan tipe 'string'
                    data.addColumn('string', 'Month');

                    // Tambahkan kolom kedua dengan label 'Count' dan tipe 'number'
                    data.addColumn('number', 'Count');
                    data.addColumn('string', 'Color');

                    // Buat array yang berisi data yang akan dimasukkan ke dalam tabel
                    var chartData = [];
                    for (var i = 0; i < convertedArray.length; i++) {
                        chartData.push([convertedArray[i], parseInt(percentageArray[i]), colors[i]]);
                    }

                    // Masukkan data ke dalam tabel
                    data.addRows(chartData);
                                
                var options = {
                    pieSliceText: 'percentage',
                    chartArea: { width: '90%', height: '90%' }, // Display percentage inside slices
                    pieHole: 0.5,
                    legend: 'none', // Menonaktifkan legend
                    pieSliceTextStyle: {
                        color: 'black', // Warna teks di dalam slices
                        fontSize: 12 // Ukuran teks di dalam slices
                    }
                };
            
                var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            
                chart.draw(data, options);
                }
            </script>
  
          
          
    </div>
</div>
<script>
    $(document).ready(function() {
        // Define the function to handle the AJAX request
        function performSearch(query) {
            $.ajax({
                url: window.location.href,
                type: "GET",
                data: { 'searchdata': query},
                success: function(data) {
                    $('#tableBase').html(data); $.getScript("{{ asset('dist/js/app.js')}}", function() {});
                }
            });
        }

        performSearch("");

        // Set up the keyup event handler
        $('#cariInput').keyup(function() {
            var query = $(this).val();
            performSearch(query);
        });

    });
</script>


@include('layouts.footer')