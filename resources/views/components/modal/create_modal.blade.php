
<div class="modal" id="createmodal">
    <div class="modal__content">
        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto">Create Form</h2> 
            <div class="dropdown sm:hidden"> <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"> <i data-feather="more-horizontal" class="w-5 h-5 text-gray-700 dark:text-gray-600"></i> </a></div>
        </div>
        <form id="createForm" action="" method="POST" enctype="multipart/form-data">
            <div class="p-5 grid grid-cols-12 gap-4 row-gap-3"></div>
            @csrf

            <div class="px-5 py-3 text-right border-t border-gray-200 dark:border-dark-5">
                <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1">Batalkan</button> 
                <button id="simpanCreateFormBtn" type="button" class="button w-20 bg-theme-1 text-white">Simpan</button>
            </div>
        </form>  
    </div>
</div>
