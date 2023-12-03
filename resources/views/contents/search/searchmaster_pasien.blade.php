<table class="table table-report -mt-2">
    <thead>
        <tr>
            <th class="whitespace-no-wrap">No RM</th>
            <th class="whitespace-no-wrap">Nama</th>
            <th class="whitespace-no-wrap">Tgl Lahir</th>
            <th class="whitespace-no-wrap">Jenis Kelamin</th>
            <th class="whitespace-no-wrap">No Telp</th>
            <th class="whitespace-no-wrap">Alamat</th>
            <th class="whitespace-no-wrap">Pekerjaan</th>
            <th class="text-center whitespace-no-wrap">OPSI</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
            <tr class="intro-x">
                <td id="masalahrm"class="font-medium whitespace-no-wrap">{{ $item->no_rekmed }}</td>
                <td class="font-medium whitespace-no-wrap">{{ $item->nama }}</td>
                <td class="font-medium whitespace-no-wrap">{{ \Carbon\Carbon::parse($item->tgl_lahir)->format("j M, Y") }}</td>
                <td class="font-medium whitespace-no-wrap">{{ $item->jenis_kelamin }}</td>
                <td class="font-medium whitespace-no-wrap">{{ $item->no_telp }}</td>
                <td class="font-medium whitespace-no-wrap">{{ $item->alamat }}</td>
                <td class="font-medium whitespace-no-wrap">{{ $item->pekerjaan }}</td>
                <td>
                    <div class="flex justify-center items-center">
                        @can('access-admin')
                            <a class="flex items-center text-theme-1 mr-3" onclick="editBtnOlick({{ $index }})" href="javascript:;" data-toggle="modal" data-target="#editmodal">
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                        @endcan
                        @can('access-pendaftaran')
                            @php
                                $results = DB::table('pendaftaran')
                                    ->where('no_rekmed', $item->no_rekmed)
                                    ->whereDate('tgl_daftar', now()->format('Y-m-d'))
                                    ->where('status', 'Belum Periksa')
                                    ->get();
                            @endphp
                            @if(count($results) > 0)
                                <button class="button w-24 mr-1 mb-2 bg-theme-6 text-white">Pasien sudah terdaftar!</button>
                            @else
                                <a class="flex items-center text-theme-1 mr-3" onclick="selectBtnOnclick({{ $index }},'pendaftaran')" href="javascript:;" data-toggle="modal" data-target="#selectmodal">
                                    <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Pilih
                                </a>
                            @endif
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        <script>
            if($('table.table-report tbody tr').length < 1){
                $('table.table-report tbody').append('<tr class="intro-x" id="trPasienKosong" style="display: none;"><td class="font-medium whitespace-no-wrap">999</td><td class="font-medium whitespace-no-wrap">fariz</td>'+
                '<td class="font-medium whitespace-no-wrap">4 Oct, 2023</td><td class="font-medium whitespace-no-wrap">laki-laki</td>'+
                ' <td class="font-medium whitespace-no-wrap">443</td><td class="font-medium whitespace-no-wrap">adada</td>'+
                '<td class="font-medium whitespace-no-wrap">saas</td><td></td></tr>')
                }else if($('table.table-report tbody tr').length > 0){
                    if($("#trPasienKosong")){
                        $("#trPasienKosong").remove()
                    }
                }
        </script>
    </tbody>
</table>

@include('components.pagination')