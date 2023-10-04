
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th id="photo" class="whitespace-no-wrap">Photo</th>
                <th class="whitespace-no-wrap">ID Dokter</th>
                <th class="whitespace-no-wrap">Nama</th>
                <th class="whitespace-no-wrap">No Telp</th>
                <th class="whitespace-no-wrap">Poli</th>
                <th class="text-center whitespace-no-wrap">OPSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr class="intro-x">
                    <td id="tdPhoto" class="font-medium whitespace-no-wrap">
                        <div class="w-10 h-10 flex-none image-fit">
                            <img id="tbImage_{{ $index }}" src="{{ $item->photo }}" class="tooltip rounded-full">
                        </div>
                    </td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->id_dokter }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->nama }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->no_telp }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->nama_poli }}</td>
                    <td>
                        <div class="flex justify-center items-center">
                            <a class="flex items-center text-theme-1 mr-3" onclick="editBtnOlick({{ $index }})" href="javascript:;" data-toggle="modal" data-target="#editmodal"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                            <a class="flex items-center text-theme-6" onclick="deleteBtnOnclick({{ $index }})" href="javascript:;" data-toggle="modal" data-target="#deletemodal"> <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('components.pagination')

