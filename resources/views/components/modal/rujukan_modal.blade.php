<div class="modal" id="large-modal-size-preview">
    <div class="modal__content modal__content--lg p-10 text-center">
        <div class="intro-y box py-10 sm:py-20 mt-5"></div>
        
        <div id="caridokterRujukan" style="display: flex; justify-content: space-between;" class="intro-y box py-10 sm:py-20 mt-5"></div>
    </div>
    
</div>
<script>
    performSearch("");
    function performSearch(query) {
            $.ajax({
                url: window.location.href+"/rujukan",
                type: "GET",
                data: { 'searchdata': query},
                success: function(data) {  
                    $('#caridokterRujukan').html(data);
                }
            });
        }
</script>