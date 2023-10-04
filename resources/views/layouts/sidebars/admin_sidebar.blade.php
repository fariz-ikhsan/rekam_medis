<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Midone Tailwind HTML Admin Template" class="w-6" src="dist/images/logo.png">
        <span class="hidden xl:block text-white text-lg ml-3"> Admin </span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul id="sidebar">
        <li>
            <a id="sidebarBtnDataPasien" href="{{ route('datapasien.index') }}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="users"></i> </div>
                <div class="side-menu__title"> Data Pasien </div>
            </a>
        </li>
        <li>
            <a id="sidebarBtnDataDokter" href="{{ route('datadokter.index') }}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="user"></i> </div>
                <div class="side-menu__title"> Data Dokter </div>
            </a>
        </li>
        <li>
            <a id="sidebarBtnDataJadwal" href="{{ route('datajadwal.index') }}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="file-text"></i> </div>
                <div class="side-menu__title"> Data Jadwal Praktek </div>
            </a>
        </li>
        <li>
            <a id="sidebarBtnDataPoli" href="{{ route('datapoli.index') }}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="file-text"></i> </div>
                <div class="side-menu__title"> Data Poli </div>
            </a>
        </li>
        <li>
            <a id="sidebarBtnDataSuster" href="{{ route('datasuster.index') }}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="user"></i> </div>
                <div class="side-menu__title"> Data Suster </div>
            </a>
        </li>
        <li>
            <a id="sidebarBtnDataStaffPendaftaran" href="{{ route('datastaffpendaftaran.index') }}" class="side-menu side-menu">
                <div class="side-menu__icon"> <i data-feather="user"></i> </div>
                <div class="side-menu__title"> Data Staff Pendaftaran </div>
            </a>
        </li>
    </ul>
    <script>
        $(document).ready(function() {
            var currentURL = window.location.href;
            var urlParts = currentURL.split('/');
            var filteredURL = urlParts[urlParts.length - 1].toLowerCase();
    
            $('#sidebar li a').each(function() {
                var id = $(this).attr('id');
                var matches = id.match(/sidebarBtn(.*)/);
    
                if (matches && matches.length > 1) {
                    var filteredId = matches[1].toLowerCase();
    
                    if (filteredURL === filteredId) {
                        $(this).addClass('side-menu--active');
                        return false;
                    }
                }
            });
        });
    </script>
</nav>
