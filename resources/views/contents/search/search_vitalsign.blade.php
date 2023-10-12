@if(count($data) > 0)
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="whitespace-no-wrap" style="display:none;">id_pendaftaran</th>
                <th class="whitespace-no-wrap">No RM</th>
                <th class="whitespace-no-wrap">Nama</th>
                <th class="whitespace-no-wrap">Dokter</th>
                <th class="whitespace-no-wrap">Ruangan</th>
                <th class="text-center whitespace-no-wrap">OPSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr class="intro-x">
                    <td id="idpendaftaran" style="display:none;">
                        {{ $item->no_rekmed }}
                    </td>
                    <td class="font-medium whitespace-no-wrap">
                        {{ $item->no_rekmed }}
                    </td>
                    <td class="font-medium whitespace-no-wrap">
                        {{ $item->nama }}
                    </td>
                    <td class="font-medium whitespace-no-wrap">
                        @foreach ($namadokter as $noRekmed => $doctors)
                            @if($noRekmed == $item->no_rekmed)
                                @foreach ($doctors as $doctor)
                                    {{ $doctor }}
                                    @if (!$loop->last)
                                        {{ ', ' }}
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                    <td class="font-medium whitespace-no-wrap">
                        @foreach ($ruangan as $noRekmed => $ruangann)
                            @if($noRekmed == $item->no_rekmed)
                                @foreach ($ruangann as $lokasi)
                                    {{ $lokasi }}
                                    @if (!$loop->last)
                                        {{ ', ' }}
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <div class="flex justify-center items-center">
                            <a class="flex items-center text-theme-1 mr-3" onclick="selectBtnOnclick({{ $index }}, 'suster')" href="javascript:;" data-toggle="modal" data-target="#selectmodal">
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Pilih
                            </a>
                        </div>
                        <script>
                            $("#executeSelectBtnOnclick").on("click", function () {
                                var berat_badan = $("input[name='berat_badan']").val();
                                var tekanan_darah = $("input[name='tekanan_darah']").val();

                                $("#selectContentBase input:gt(1)").each(function () {
                                    if ($(this).val() === "") {
                                        // Fill it with a default value
                                        $(this).val("-");
                                    }
                                });
                                if (berat_badan === "" || tekanan_darah === "") {
                                    if (berat_badan === "") {
                                        alert("Berat badan wajib diisi!");
                                    } else if (tekanan_darah === "") {
                                        alert("Tekanan darah wajib diisi!");
                                    }
                                } else {
                                    $("#selectForm").submit();
                                }
                            });
                        </script>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('components.pagination')
@else
    No results
@endif
