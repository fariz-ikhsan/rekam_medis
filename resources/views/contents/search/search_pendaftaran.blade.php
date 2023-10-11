<!-- resources/views/dokter.blade.php -->


    @foreach ($data as $index => $item)
        <div onclick="selectDokter({{ $index }}, '{{ $item->id_jdwdokter }}')" class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
            <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
                <div id="dktImageDivPdf">
                    <img id="tbImage_{{ $index }}" src="{{$item->photo}}" class="rounded-full">
                </div>
                <div class="block font-medium mt-4 text-center truncate">{{ $item->nama }}</div>
                <div class="text-gray-600 text-xs text-center">{{ $item->poli }}</div>
                <div class="text-gray-600 text-xs text-center">{{ $item->lokasi }}</div>
            </div>
        </div>
    @endforeach

    <script>
        var isSelect = false;
        var jadwalIdDokter = "";
        var arrayPilihDokter = [];
        var arrayListJdwDokter = [];

        function selectDokter(index, idjadwaldokter) {
            // $("#selectContentBase > div > div").each(function () {
            //     $(this).css("background-color", "");
            // });
            let indeksPilihDokter = arrayPilihDokter.indexOf(index);
            let indeksListJdwDokter = arrayListJdwDokter.indexOf(idjadwaldokter);

            if (indeksListJdwDokter !== -1) {
                arrayListJdwDokter.splice(indeksListJdwDokter, 1);
            } else {
                arrayListJdwDokter.push(idjadwaldokter);
            }

            if (indeksPilihDokter !== -1) {
                arrayPilihDokter.splice(indeksPilihDokter, 1);
                $("#selectContentBase > div").eq(index).find("> div").css("background-color", "");
            } else {
                arrayPilihDokter.push(index);
                $("#selectContentBase > div").eq(index).find("> div").css("background-color", "cornsilk");
            }
            
            if(arrayPilihDokter.length > 0){
                isSelect = true;   
            }
        }

        $('#divExecuteSelect button:eq(1)').click(function() {
            var $div = $("div").find(".rounded-md.flex.items-center.px-5.py-4.mb-2.bg-theme-6.text-white");
         
            if (isSelect) {
                jadwalIdDokter = arrayListJdwDokter.join(";");
                $("#idjdwdokter_pasienbaru").val(jadwalIdDokter);
                
                $div.css("display", "none");
                $("#selectForm").append(
                    $("<input>")
                        .attr({
                            "type": "hidden",
                            "name": "id_jdwdokter",
                            "value": jadwalIdDokter,
                        })
                );

                $("#selectForm").submit();
            } else {
                event.preventDefault();
                $div.css("display", "");
            }
        });


    </script>
