<!-- resources/views/dokter.blade.php -->


@foreach ($data as $index => $item)
    <div onclick="selectDokter({{ $index }}, '{{ $item->id_jdwdokter }}')" class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2" style="width: 50%; height: 50%;">
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
    var idjdwdokter = "";
    function selectDokter(index, idjadwaldokter) {
        $("#caridokterRujukan > div > div").each(function() {
            $(this).css("background-color", "");
        });

        $("#caridokterRujukan > div").eq(index).find("> div").css("background-color", "cornsilk");
        idjdwdokter = idjadwaldokter;
        alert(idjdwdokter)
    }

    


</script>
