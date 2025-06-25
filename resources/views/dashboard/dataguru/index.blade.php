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
                        <label for="add_button" class="btn btn-primary btn-sm text-white dark:text-gray-800 normal-case bg-purple-600 hover:bg-opacity-70 dark:bg-purple-300 dark:hover:bg-opacity-90">
                            <i class="ri-add-fill"></i>
                            Tambah Guru
                        </label>
                    </div>
                </div>
                <div class="overflow-x-auto w-full p-3">
                <table id="tabel_data"  class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover" style="width:100%">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">NIP</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-3">{{ $item->nama }}</td>
                                <td class="px-4 py-3">{{ $item->nip }}</td>
                                <td class="px-4 py-3">{{ $item->keterangan }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <label for="edit_button" class="btn btn-xs btn-warning text-white" onclick="edit_button('{{ $item->id }}')">
                                        <i class="ri-edit-2-line"></i>
                                    </label>
                                    <button onclick="delete_button('{{ $item->id }}', '{{ $item->nama }}')" class="btn btn-xs btn-error text-white">
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

        {{-- Tambah Data --}}
        <input type="checkbox" id="add_button" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box">
                <form action="{{ route('dataguru.simpan') }}" method="post">
                    @csrf
                    <h3 class="font-bold text-lg">Tambah Guru</h3>

                    <div class="form-control w-full max-w-xs">
                        <label class="label">
                            <span class="label-text">Nama</span>
                        </label>
                        <input type="text" name="nama" class="input input-bordered text-gray-800" value="{{ old('nama') }}" required />
                        @error('nama')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="form-control w-full max-w-xs mt-4">
                        <label class="label">
                            <span class="label-text">NIP</span>
                        </label>
                        <input type="number" name="nip" class="input input-bordered text-gray-800" value="{{ old('nip') }}" required />
                        @error('nip')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="form-control w-full max-w-xs mt-4">
                        <label class="label">
                            <span class="label-text">Keterangan</span>
                        </label>
                        <input type="text" name="keterangan" class="input input-bordered text-gray-800" value="{{ old('keterangan') }}" />
                        @error('keterangan')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <div class="modal-action mt-6">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <label for="add_button" class="btn">Batal</label>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" for="add_button">Close</label>
        </div>

        {{-- Edit Data --}}
        <input type="checkbox" id="edit_button" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box" id="edit_form">
                <form action="{{ route('dataguru.perbarui') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" />
                    <h3 class="font-bold text-lg">Ubah Data Guru: <span id="edit_nama"></span></h3>

                    <div class="form-control w-full max-w-xs mt-4">
                        <label class="label">
                            <span class="label-text">Nama</span>
                        </label>
                        <input type="text" name="nama" class="input input-bordered text-gray-800" required />
                    </div>

                    <div class="form-control w-full max-w-xs mt-4">
                        <label class="label">
                            <span class="label-text">NIP</span>
                        </label>
                        <input type="number" name="nip" class="input input-bordered text-gray-800" required />
                    </div>

                    <div class="form-control w-full max-w-xs mt-4">
                        <label class="label">
                            <span class="label-text">Keterangan</span>
                        </label>
                        <input type="text" name="keterangan" class="input input-bordered text-gray-800" />
                    </div>

                    <div class="modal-action mt-6">
                        <button type="submit" class="btn btn-success">Perbarui</button>
                        <label for="edit_button" class="btn">Batal</label>
                    </div>
                </form>
            </div>
            <label class="modal-backdrop" for="edit_button">Close</label>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#tabel_data').DataTable({ responsive: true, order: [], scrollX: true,})
            .columns.adjust().responsive.recalc();
    });

    function edit_button(id) {
        $.get("{{ route('dataguru.ubah') }}", { id: id }, function (data) {
            $("input[name='id']").val(data.id);
            $("input[name='nama']").val(data.nama);
            $("input[name='nip']").val(data.nip);
            $("input[name='keterangan']").val(data.keterangan);
            $("#edit_nama").text(data.nama);
        });
    }

    function delete_button(id, nama) {
        Swal.fire({
            title: 'Hapus data?',
            html: `<p><b>${nama}</b> akan dihapus permanen!</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6419E6',
            cancelButtonColor: '#F87272',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('dataguru.hapus') }}", {
                    _token: "{{ csrf_token() }}",
                    id: id
                }, function () {
                    Swal.fire('Dihapus!', 'Data berhasil dihapus.', 'success')
                        .then(() => location.reload());
                }).fail(() => {
                    Swal.fire('Error', 'Gagal menghapus data.', 'error');
                });
            }
        });
    }

    @if (session('berhasil'))
        Swal.fire('Berhasil', '{{ session('berhasil') }}', 'success');
    @endif

    @if ($errors->any())
        Swal.fire('Gagal', '{{ implode(', ', $errors->all()) }}', 'error');
    @endif
</script>
@endsection
