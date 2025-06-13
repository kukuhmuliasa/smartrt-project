{{-- resources/views/admin/iuran/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Catatan Iuran Warga') }}
            </h2>
            <a href="{{ route('admin.iuran.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 transition-colors">
                {{ __('+ Catat Iuran Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi dipisahkan dari konten utama untuk tata letak yang lebih bersih --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Daftar Kartu Iuran (Pengganti Tabel) --}}
            <div class="space-y-4">
                @forelse ($iurans as $iuran)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex flex-col md:flex-row md:justify-between md:items-center">
                            
                            {{-- Kolom Kiri: Informasi Utama --}}
                            <div class="flex-grow">
                                <div class="flex items-center mb-2 md:mb-0">
                                    <div class="font-bold text-lg text-gray-900 dark:text-gray-100 mr-4">
                                        {{ $iuran->warga->name ?? 'Warga Dihapus' }}
                                    </div>
                                    @if($iuran->status_pembayaran == 'lunas')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Lunas</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Belum Lunas</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span>{{ $iuran->jenis_iuran }}</span> &bull;
                                    <span>Periode: {{ \Carbon\Carbon::create()->month($iuran->bulan)->format('F') }} {{ $iuran->tahun }}</span>
                                </div>
                            </div>
                            
                            {{-- Kolom Tengah: Jumlah & Tanggal --}}
                            <div class="flex-shrink-0 mt-4 md:mt-0 md:mx-6 text-left md:text-center">
                                <div class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                    Rp {{ number_format($iuran->jumlah, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-500">
                                    Tgl Bayar: {{ $iuran->tanggal_bayar ? $iuran->tanggal_bayar->format('d M Y') : '-' }}
                                </div>
                            </div>

                            {{-- Kolom Kanan: Aksi --}}
                            <div class="flex-shrink-0 mt-4 md:mt-0 flex items-center">
                                <a href="{{ route('admin.iuran.edit', $iuran->id) }}" class="px-3 py-1 text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Edit</a>
                                <div class="border-l h-6 mx-2 dark:border-gray-600"></div>
                                <form action="{{ route('admin.iuran.destroy', $iuran->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan iuran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Hapus</button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <p class="text-gray-500 dark:text-gray-400">Belum ada data iuran tercatat.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi, diletakkan di bawah daftar kartu --}}
            <div class="mt-6">
                {{ $iurans->links() }}
            </div>

        </div>
    </div>
</x-app-layout>