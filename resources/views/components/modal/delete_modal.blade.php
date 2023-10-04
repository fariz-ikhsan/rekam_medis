<div class="modal" id="deletemodal">
    <div class="modal__content">
        <div class="p-5 text-center"> <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
            <div class="text-3xl mt-5">Are you sure?</div>
            <div class="text-gray-600 mt-2">Do you really want to delete these records? This process cannot be undone.</div>
        </div>
        <form id='deleteForm' action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="px-5 pb-8 text-center"> 
                <button type="button" data-dismiss="modal" class="button w-24 border text-gray-700 dark:border-dark-5 dark:text-gray-300 mr-1">Batalkan</button> 
                <button type="submit" class="button w-24 bg-theme-6 text-white">Delete</button> 
            </div>
        </form>
    </div>
</div>