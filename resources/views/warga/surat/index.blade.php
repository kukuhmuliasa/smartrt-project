{{-- resources/views/warga/surat/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daftar Pengajuan Surat Saya') }}
            </h2>
            <a href="{{ route('warga.surat.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800">
                {{ __('+ Ajukan Surat Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                     @if(session('error'))
                        <div class="mb-4 p-3 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Diajukan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Surat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keperluan</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($pengajuanSurats as $surat)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $surat->jenis_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($surat->status_pengajuan == 'diajukan') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif
                                                @if($surat->status_pengajuan == 'diproses') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif
                                                @if($surat->status_pengajuan == 'disetujui') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif
                                                @if($surat->status_pengajuan == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif
                                                @if($surat->status_pengajuan == 'ditolak') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $surat->status_pengajuan)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate" title="{{ $surat->keperluan }}">
                                            {{ Str::limit($surat->keperluan, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Komentar yang sudah ada bisa dibiarkan atau dihapus --}}
                                            {{-- KONDISI UNTUK MELIHAT SURAT JADI --}}
                                            @if(($surat->status_pengajuan == 'selesai' || $surat->status_pengajuan == 'disetujui') && $surat->file_surat_jadi)
                                                <a href="{{ asset('storage/' . $surat->file_surat_jadi) }}" 
                                                target="_blank" 
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-600 font-semibold">
                                                Lihat Surat Jadi
                                                </a>
                                            @endif
                                            
                                            {{-- Anda bisa menambahkan link 'Lihat Detail' jika ada halaman detail untuk warga --}}
                                            {{-- <a href="{{ route('warga.surat.show', $surat->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 ml-4">Detail</a> --}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- <a href="{{ route('warga.surat.show', $surat->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Lihat Detail</a> --}}
                                            {{-- Warga mungkin tidak bisa edit/hapus pengajuan yang sudah dikirim, tergantung aturan --}}
                                            @if($surat->status_pengajuan == 'diajukan')
                                                {{-- Tambah tombol batal jika diperlukan dan diizinkan --}}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">Anda belum pernah mengajukan surat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pengajuanSurats->links() }} {{-- Untuk Paginasi --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>