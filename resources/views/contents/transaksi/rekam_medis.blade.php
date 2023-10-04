
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
    <div id="contentRekamMedis">
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto" style="font-size: 30px;">
                Rekam Medis Pasien
            </h2>
        </div>
       
        <script>
            let currentlySelectedElement = null;
                  function detailrm(idvs, element){
                      $.ajax({
                          url:  window.location.href+"/detail",
                          type: "GET",
                          data: { "idvs": idvs},
                          success: function(data) {
                              if (currentlySelectedElement) {
                                  currentlySelectedElement.classList.remove("bg-theme-14");
                              }
      
                              element.classList.add("bg-theme-14");
                              currentlySelectedElement = element;
      
                              $('#detail_rekam_medis').html(data);
                          }
                      });
                  }
      
          </script>
          <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
              <!-- BEGIN: Chat Side Menu -->
              <div class="col-span-12 lg:col-span-4 xxl:col-span-3">
                  <div class="intro-y pr-1">
                      <div class="box p-2">
                          <div class="relative text-gray-700 dark:text-gray-300">
                              <input id="cariInput" name="cari" type="text" class="input input--lg w-full bg-gray-200 pr-10 placeholder-theme-13" placeholder="Cari diagnosa...">
                              <i class="w-4 h-4 hidden sm:absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                          </div>
                      </div>
                  </div>
                  <div class="intro-y pr-1">
                      <div class="box p-2">Rentang tanggal
                          <div class="relative text-gray-700 dark:text-gray-300" style="padding: 10px;">
                              <div class="relative w-56 mx-auto"> <div class="absolute rounded-l w-10 h-full flex items-center justify-center bg-gray-100 border text-gray-600 dark:bg-dark-1 dark:border-dark-4"> <i data-feather="calendar" class="w-4 h-4"></i> </div> <input type="text" class="datepicker input pl-12 border" data-daterange="true"> </div>
                          </div>
                          <div class="flex justify-center">
                            <button id="btnRentangTgl" type="button" class="button w-18 mr-1 mb-2 bg-gray-200 text-gray-600"> Tampilkan </button>
                          </div>
                          
                      </div>
                  </div>
                  <div class="tab-content">
                      <div class="tab-content__pane active" id="chats">
                          
                      </div>
                  </div>
              </div>
              <!-- END: Chat Side Menu -->
              <!-- BEGIN: Chat Content -->
              <div class="intro-y col-span-12 lg:col-span-8 xxl:col-span-9">
                  <div class="chat__box box">
                      <!-- BEGIN: Chat Active -->
                      <div class="h-full flex flex-col">
                          <div class="flex flex-col border-b border-gray-200 dark:border-dark-5 px-5 py-4" style="display: flex; justify-content: center; align-items: center; margin: 0;">
                                <div class="font-medium text-base">DETAIL REKAM MEDIS</div>
                          </div>
                          <div id="detail_rekam_medis" class="overflow-y-scroll scrollbar-hidden px-5 pt-5 flex-1">
                              
                          </div>
                      </div>
                      <!-- END: Chat Active -->
                  </div>
              </div>
              <!-- END: Chat Content -->
            </div>

            <script>
                $(document).ready(function() {
                    var startDate = "";
                    var endDate =  "";
                    var searchVal = "";
                        // Define the function to handle the AJAX request
                    function performSearch(query, start_date, end_date) {
                        $.ajax({
                            url:  window.location.href,
                            type: "GET",
                            data: { 'searchdata': query, 'startdate': start_date, 'enddate':end_date},
                            success: function(data) {
                                $('#chats').html(data);
                            }
                        });
                    }           
                    $("#btnRentangTgl").click();
                    // Set up the keyup event handler
                    $('#cariInput').keyup(function() {
                        searchVal = $(this).val();
                        $("#btnRentangTgl").click();
                    });

                    $("#btnRentangTgl").click(function() {
                        const buttonApply = $('.button-apply');
                        var dpval = $(".datepicker").val();
                        var dateArray = dpval.split('-');

                        var startDate = dateArray[0].trim();
                        var endDate = dateArray[1].trim();

                        performSearch(searchVal, startDate, endDate);
                    });
                });
            </script>
    </div>
    @include('contents.transaksi.pemeriksaan_spesialistik')

</div>



@include('layouts.footer')