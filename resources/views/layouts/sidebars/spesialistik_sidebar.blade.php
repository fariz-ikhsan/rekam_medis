<nav class="side-nav">
    <div id="profileBase">
        @if(isset($datadokter))
            @foreach($datadokter as $item)
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
            <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="{{$item->photo}}">
        </div>
    </a>
    <div style="text-align: center;">
        <span class="hidden xl:block text-white text-lg ml-3"  style="width: 100%; margin: 15px -21px auto">{{$item->nama}} </span>
    </div>
            @endforeach
         @endif
    </div>
    
    <div class="side-nav__devider my-6"></div>
    <ul id="sidebar">
        <li>
            <a id="sidebarBtnHalamanUtama" href="{{route("home-spesialistik")}}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Halaman Utama </div>
            </a>
        </li>

        <div class="side-nav__devider my-6"></div>

        <li>
            <a id="sidebarBtnSpesialistik" href="{{route("antrianpasien-spesialistik")}}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="users"></i> </div>
                <div class="side-menu__title"> Antrian Pasien </div>
            </a>
        </li>

        <li>
            <a id="sidebarBtnKunjunganhariini" href="{{route("kunjunganhariini-spesialistik")}}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="users"></i> </div>
                <div class="side-menu__title"> Kunjungan Hari ini </div>
            </a>
        </li>

        <div class="side-nav__devider my-6"></div>

        <li id="liRM">
            <a id="sidebarBtnRekamMedis" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="user"></i> </div>
                <div class="side-menu__title"> Rekam Medis Pasien </div>
            </a>
        </li>
        <li id="liPM">
            <a id="sidebarBtnPemeriksaan" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="file-text"></i> </div>
                <div class="side-menu__title"> Pemeriksaan </div>
            </a>
        </li>
    </ul>
    <script>
        var first = false;
        function selectedSidebar(element){
            if (currentlySelectedElement) {
                currentlySelectedElement.querySelector("a").classList.remove("side-menu--active");
            }
            element.querySelector("a").classList.add("side-menu--active");
            currentlySelectedElement = element;
        }
        
        $(document).ready(function() {
            // Temukan semua elemen <a> di dalam elemen <li> dalam daftar <ul>
            const sidebarLinks = document.querySelectorAll('#sidebar li a');

            if(window.location.href.indexOf("rekammedis") !== -1){
                if(!first){
                    $("#sidebarBtnRekamMedis").addClass('side-menu--active');
                    first = true;
                }
            sidebarLinks.forEach(link => {
                    link.addEventListener('click', function (event) {
                        // Hentikan perilaku default dari tautan
                        event.preventDefault();

                        // Hapus kelas 'side-menu--active' dari semua elemen <a>
                        sidebarLinks.forEach(link => {
                            link.classList.remove('side-menu--active');
                        });
                        
                       
                        $("#sidebarBtnHalamanUtama").click(function() {
                            const href = this.getAttribute('href');
                            window.location.href = href;
                        });

                        $("#sidebarBtnSpesialistik").click(function() {
                            const href = this.getAttribute('href');
                            window.location.href = href;
                            
                        });

                        $("#sidebarBtnKunjunganhariini").click(function() {
                            const href = this.getAttribute('href');
                            window.location.href = href;
                            
                        });
                        this.classList.add('side-menu--active');

                        
                        $("#liRM").click(function() {
                            $("#contentRekamMedis").css("display","")
                            $("#contentPemeriksaan").css("display","none")
                        });

                        $("#liPM").click(function() {
                            $("#contentRekamMedis").css("display","none")
                            $("#contentPemeriksaan").css("display","")
                        });
                        
                    });
                });
            }else if(window.location.href.indexOf("antrianpasien") !== -1){
                $("#sidebarBtnSpesialistik").addClass('side-menu--active');
            }
            else if(window.location.href.indexOf("kunjunganhariini") !== -1){
                $("#sidebarBtnKunjunganhariini").addClass('side-menu--active');
            }
            else{
                $("#sidebarBtnHalamanUtama").addClass('side-menu--active');
            }
            
            
        });
    </script>
</nav>
