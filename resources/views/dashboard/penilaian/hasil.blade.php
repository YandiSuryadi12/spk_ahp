@extends('dashboard.layouts.app')

@section('container')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-3xl font-bold text-center text-gray-700 dark:text-white">
        {{ $judul }}
    </h2>
</div>

<div>
    <section class="mt-6">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
                <div class="px-6 py-8 text-center">
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
                        Hasil Penilaian
                    </h3>

                    @if (count($rows) > 0)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            🎉 Guru dengan peringkat tertinggi adalah
                            <span class="font-bold text-purple-600 dark:text-purple-400">
                                {{ $rows[0]['nama_pengguna'] }}
                            </span>
                            dengan total nilai
                            <span class="font-semibold">{{ number_format($rows[0]['total_nilai'], 2) }}</span>
                        </p>
                    @endif
                </div>

                <div class="overflow-x-auto p-6">
                    <table id="tabel_data" class="w-full text-sm text-left text-gray-600 dark:text-gray-200 table-auto">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Total Nilai</th>
                                <th class="px-4 py-3">Ranking</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($rows as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                    <td class="px-4 py-3 font-medium">{{ $row['nama_pengguna'] }}</td>
                                    <td class="px-4 py-3">{{ number_format($row['total_nilai'], 2) }}</td>
                                    <td class="px-4 py-3 font-semibold text-center">
                                        <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-700 dark:text-white">
                                            #{{ $row['ranking'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
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
            html: `{!! implode('<br>', $errors->all()) !!}`,
            icon: 'error',
            confirmButtonColor: '#6419E6',
            confirmButtonText: 'OK',
        });
    @endif
</script>
@endsection
