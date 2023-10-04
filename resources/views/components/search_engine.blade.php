<script>
    $(document).ready(function(){
        $('#cariInput').on('keyup',function(){
            var query= $(this).val(); 
            $.ajax({
                url: routeInitial,
                type:"GET",
                data:{'search':query},
                success:function(data){ 
                    $('#search_list').html(data);
                }
            });
        });
    });
</script>