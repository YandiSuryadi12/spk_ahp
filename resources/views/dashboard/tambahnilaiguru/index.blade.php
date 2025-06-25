@extends('dashboard.layouts.app')

@section('container')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ $judul }}
        </h2>
    </div>

    <div>
        <section class="mt-10">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex justify-end items-center d p-4">
                        <div class="flex space-x-3">
                            <div class="flex space-x-3 items-center">
                                <label for="add_button" class="btn btn-primary btn-sm text-white dark:text-gray-800 normal-case bg-purple-600 hover:bg-opacity-70 hover:border-opacity-70 dark:bg-purple-300 dark:hover:bg-opacity-90">
                                    <i class="ri-add-fill"></i>
                                    Tambah {{ $judul }}
                                </label>
                               
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto p-3">
                        <table id="tabel_data" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Nama</th>
                                    @foreach ($kriteriaList as $namaKriteria)
                                        <th>{{ $namaKriteria }}</th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center">Aksi</th> {{-- Kolom Delete --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td>{{ $row['nama_pengguna'] }}</td>
                                        @foreach ($kriteriaList as $namaKriteria)
                                            <td>{{ $row[$namaKriteria] ?? '-' }}</td>
                                        @endforeach
                                        <td class="text-center">
                                            <button
                                                onclick="delete_button('{{ $row['guru_id'] }}', '{{ $row['nama_pengguna'] }}')">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            

            {{-- Form Tambah Data --}}
            <input type="checkbox" id="add_button" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <form action="{{ route('tambahnilaiguru.simpan') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-control w-full max-w-xs">
                                <label class="label">
                                    <span class="label-text">Nama Guru</span>
                                </label>
                                <select name="guru_id" class="select select-bordered text-gray-800 w-full max-w-xs" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($calon as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="label">
                                    @error('guru_id')
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

                            @foreach ($kriteria as $item)
                            <div class="form-control w-full max-w-xs mt-4">
                                <label class="label">
                                    <span class="label-text">Nilai untuk Kriteria: {{ $item->nama }}</span>
                                </label>

                                <input 
                                    type="number" 
                                    name="nilai_kriteria[{{ $item->id }}]" 
                                    step="0.01" 
                                    min="0" 
                                    max="100"
                                    class="input input-bordered w-full max-w-xs text-gray-800"
                                    placeholder="Contoh: 85.50"
                                    value="{{ old('nilai_kriteria.' . $item->id) }}" 
                                    required
                                />
                                <input 
                                    type="hidden" 
                                    name="kriteria_id[]" 
                                    value="{{ $item->id }}"
                                />
                                <input 
                                    type="hidden" 
                                    name="bobot_kriteria[{{ $item->id }}]" 
                                    value="{{ $item->prioritas }}"
                                />
                                <label class="label">
                                    @error("nilai_kriteria.{$item->id}")
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        @endforeach



                            {{-- Tombol Submit --}}
                            <div class="modal-action mt-6">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>

                    </div>
                    <label class="modal-backdrop" for="add_button">Close</label>
                </div>

            {{-- Form Ubah Data --}}
            <input type="checkbox" id="edit_button" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box" id="edit_form">
                    <form action="{{ route('tambahnilaiguru.perbarui') }}" method="post" enctype="multipart/form-data">
                        <h3 class="font-bold text-lg">Ubah {{ $judul }}: <span class="text-greenPrimary" id="title_form"><span class="loading loading-dots loading-md"></span></span></h3>
                            @csrf
                            <input type="text" name="id" hidden />
                            <div class="form-control w-full max-w-xs">
                                <label class="label">
                                    <span class="label-text">Nama</span>
                                    <span class="label-text-alt" id="loading_edit1"></span>
                                </label>
                                <input type="text" name="nama" placeholder="Type here" class="input input-bordered w-full text-gray-800" required />
                                <label class="label">
                                    @error('nama')
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        <div class="modal-action">
                            <button type="submit" class="btn btn-success">Perbarui</button>
                            <label for="edit_button" class="btn">Batal</label>
                        </div>
                    </form>
                </div>
                <label class="modal-backdrop" for="edit_button">Close</label>
            </div>
        </section>
    </div>

    <!-- HASIL NYA -->
    <div>
        <section class="mt-10">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="overflow-x-auto p-3">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 text-center py-6">
                            Hasil Penilaian
                        </h3>
                        <table id="tabel_data" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover" style="width:100%; padding-top: 1em; padding-bottom: 1em;">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Nama</th>
                                    <th scope="col" class="px-4 py-3">Total Nilai</th>
                                    <th scope="col" class="px-4 py-3">Rangking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td>{{ $row['nama_pengguna'] }}</td>
                                        <td>{{ $row['total_nilai'] }}</td>
                                        <td>{{ $row['ranking'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            

            {{-- Form Tambah Data --}}
            <input type="checkbox" id="add_button" class="modal-toggle" />
                <div class="modal">
                    <div class="modal-box">
                        <form action="{{ route('tambahnilaiguru.simpan') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-control w-full max-w-xs">
                                <label class="label">
                                    <span class="label-text">Nama Guru</span>
                                </label>
                                <select name="guru_id" class="select select-bordered text-gray-800 w-full max-w-xs" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($calon as $guru)
                                        <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="label">
                                    @error('guru_id')
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

                            @foreach ($kriteria as $item)
                            <div class="form-control w-full max-w-xs mt-4">
                                <label class="label">
                                    <span class="label-text">Nilai untuk Kriteria: {{ $item->nama }}</span>
                                </label>

                                <input 
                                    type="number" 
                                    name="nilai_kriteria[{{ $item->id }}]" 
                                    step="0.01" 
                                    min="0" 
                                    max="100"
                                    class="input input-bordered w-full max-w-xs text-gray-800"
                                    placeholder="Contoh: 85.50"
                                    value="{{ old('nilai_kriteria.' . $item->id) }}" 
                                    required
                                />
                                <input 
                                    type="hidden" 
                                    name="kriteria_id[]" 
                                    value="{{ $item->id }}"
                                />
                                <input 
                                    type="hidden" 
                                    name="bobot_kriteria[{{ $item->id }}]" 
                                    value="{{ $item->prioritas }}"
                                />
                                <label class="label">
                                    @error("nilai_kriteria.{$item->id}")
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        @endforeach



                            {{-- Tombol Submit --}}
                            <div class="modal-action mt-6">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </form>

                    </div>
                    <label class="modal-backdrop" for="add_button">Close</label>
                </div>
        </section>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#tabel_data').DataTable({
                responsive: true,
                order: [],
            })
            .columns.adjust()
            .responsive.recalc();
        });

        @if (session()->has('berhasil'))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session('berhasil') }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has('gagal'))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session('gagal') }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Gagal',
                text: @foreach ($errors->all() as $error) '{{ $error }}' @endforeach,
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            })
        @endif

        function edit_button(id) {
            // Loading effect start
            let loading = `<span class="loading loading-dots loading-md text-purple-600"></span>`;
            $("#title_form").html(loading);
            $("#loading_edit1").html(loading);

            $.ajax({
                type: "get",
                url: "{{ route('tambahnilaiguru.ubah') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function (data) {
                    // console.log(data);
                    let items = [];
                    $.each(data, function(key, val) {
                        items.push(val);
                    });

                    $("#title_form").html(`${items[1]}`);
                    $("input[name='id']").val(items[0]);
                    $("input[name='nama']").val(items[1]);

                    // Loading effect end
                    loading = "";
                    $("#loading_edit1").html(loading);
                }
            });
        }

        function delete_button(id, nama) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html:
                    "<p>Data tidak dapat dipulihkan kembali!</p>" +
                    "<div class='divider'></div>" +
                    "<b>Data: " + nama + "</b>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Hapus Data!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route('tambahnilaiguru.hapus') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Data berhasil dihapus!',
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function (response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data gagal dihapus!',
                            })
                        }
                    });
                }
            })
        }
    </script>
@endsection
