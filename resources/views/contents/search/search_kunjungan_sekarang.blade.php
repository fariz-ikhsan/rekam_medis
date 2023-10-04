    @if(count($data) > 0)
        <div id="pdfBaseLoc"></div>
        <table class="table table-report -mt-2">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="whitespace-no-wrap">No RM</th>
                    <th class="whitespace-no-wrap">Nama</th>
                    <th class="whitespace-no-wrap">Diagnosa Utama</th>
                    <th class="whitespace-no-wrap">Komplikasi</th>
                    <th class="whitespace-no-wrap">OPSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr class="intro-x">
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->no_rekmed }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->nama_psn }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->diagnosa_utama }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            {{ $item->komplikasi }}
                        </td>
                        <td class="font-medium whitespace-no-wrap">
                            <div>
                                <a href="{{ route('cetakpdf', ['idsps' => $item->id_spesialistik]) }}" target="_blank" class="flex items-center text-theme-1 mr-3">
                                    <i data-feather="printer" class="w-4 h-4 mr-1"></i> Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include('components.pagination')
    @else
        No results
    @endif
