@if(count($data) > 0)
    <table class="table table-report -mt-2">
        <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="whitespace-no-wrap">No RM</th>
                <th class="whitespace-no-wrap">Nama</th>
                <th class="whitespace-no-wrap">OPSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr class="intro-x">
                    <td class="font-medium whitespace-no-wrap">{{ $item->no_rekmed }}</td>
                    <td class="font-medium whitespace-no-wrap">{{ $item->nama }}</td>
                    <td class="font-medium whitespace-no-wrap">
                        <div>
                            <a id="pilihPasienSps" class="flex items-center text-theme-1 mr-3" href="{{ route('rekammedis-spesialistik', ['id' => $item->id_vitalsign]) }}">
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Pilih
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    No results
@endif
