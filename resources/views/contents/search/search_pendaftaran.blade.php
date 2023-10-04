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

        function selectDokter(index, idjadwaldokter) {
            $("#selectContentBase > div > div").each(function () {
                $(this).css("background-color", "");
            });
            $("#selectContentBase > div").eq(index).find("> div").css("background-color", "cornsilk");
            jadwalIdDokter = idjadwaldokter;
            isSelect = true;
            $("#idjdwdokter_pasienbaru").val(idjadwaldokter);
        }

        $("#executeSelectBtnOnclick").on("click", function () {
            var $div = $("div").find(".rounded-md.flex.items-center.px-5.py-4.mb-2.bg-theme-6.text-white");
            let isValid = true;

            if (isSelect) {
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
