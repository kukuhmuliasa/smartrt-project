{{-- resources/views/admin/surat/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pengajuan Surat: ') }} {{ $pengajuanSurat->jenis_surat }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Pemohon & Pengajuan</h3>
                    <div class="space-y-3">
                        <p><strong>Tanggal Diajukan:</strong> {{ $pengajuanSurat->tanggal_pengajuan ? $pengajuanSurat->tanggal_pengajuan->format('d M Y, H:i') : '-' }}</p>
                        <p><strong>Nama Pemohon:</strong> {{ $pengajuanSurat->pemohon->name ?? 'N/A' }}</p>
                        <p><strong>NIK Pemohon:</strong> {{ $pengajuanSurat->pemohon->nik ?? 'N/A' }}</p>
                        <p><strong>Jenis Surat:</strong> {{ $pengajuanSurat->jenis_surat }}</p>
                        <p><strong>Keperluan:</strong></p>
                        <p class="pl-4 whitespace-pre-wrap">{{ $pengajuanSurat->keperluan }}</p>
                        <p><strong>Status Saat Ini:</strong>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($pengajuanSurat->status_pengajuan == 'diajukan') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 @endif
                                @if($pengajuanSurat->status_pengajuan == 'diproses') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @endif
                                @if($pengajuanSurat->status_pengajuan == 'disetujui') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif
                                @if($pengajuanSurat->status_pengajuan == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif
                                @if($pengajuanSurat->status_pengajuan == 'ditolak') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $pengajuanSurat->status_pengajuan)) }}
                            </span>
                        </p>

                        @if($pengajuanSurat->file_pendukung_pemohon)
                            <p><strong>File Pendukung dari Pemohon:</strong>
                                <a href="{{ asset('storage/' . $pengajuanSurat->file_pendukung_pemohon) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline ml-2">
                                    Lihat/Unduh File
                                </a>
                            </p>
                        @else
                            <p><strong>File Pendukung dari Pemohon:</strong> Tidak ada.</p>
                        @endif

                        <hr class="my-4 dark:border-gray-700">

                        <p><strong>Catatan Admin Sebelumnya:</strong></p>
                        <p class="pl-4 whitespace-pre-wrap">{{ $pengajuanSurat->catatan_admin ?? 'Tidak ada catatan.' }}</p>

                        @if($pengajuanSurat->file_surat_jadi)
                            <p><strong>File Surat Jadi (dari Admin):</strong>
                                <a href="{{ asset('storage/' . $pengajuanSurat->file_surat_jadi) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline ml-2">
                                    Lihat/Unduh Surat Jadi
                                </a>
                            </p>
                        @else
                            <p><strong>File Surat Jadi (dari Admin):</strong> Belum diunggah.</p>
                        @endif

                        @if($pengajuanSurat->diprosesOleh)
                            <p><strong>Terakhir Diproses Oleh:</strong> {{ $pengajuanSurat->diprosesOleh->name }}
                                @if($pengajuanSurat->tanggal_selesai)
                                    pada {{ $pengajuanSurat->tanggal_selesai->format('d M Y, H:i') }}
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="md:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Proses Pengajuan Surat</h3>
                    <form method="POST" action="{{ route('admin.surat.update', $pengajuanSurat->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="status_pengajuan" :value="__('Ubah Status Pengajuan')" />
                            <select id="status_pengajuan" name="status_pengajuan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('status_pengajuan', $pengajuanSurat->status_pengajuan) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status_pengajuan')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="catatan_admin" :value="__('Catatan Admin (untuk warga)')" />
                            <textarea id="catatan_admin" name="catatan_admin" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('catatan_admin', $pengajuanSurat->catatan_admin) }}</textarea>
                            <x-input-error :messages="$errors->get('catatan_admin')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="file_surat_jadi" :value="__('Unggah Surat Jadi (PDF/Gambar, maks 2MB)')" />
                            <input id="file_surat_jadi" name="file_surat_jadi" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" accept=".pdf,.jpg,.jpeg,.png">
                            <x-input-error :messages="$errors->get('file_surat_jadi')" class="mt-2" />
                            @if($pengajuanSurat->file_surat_jadi)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">File saat ini: <a href="{{ asset('storage/' . $pengajuanSurat->file_surat_jadi) }}" target="_blank" class="hover:underline">{{ Str::afterLast($pengajuanSurat->file_surat_jadi, '/') }}</a>. Unggah file baru untuk mengganti.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Update Proses Surat') }}
                            </x-primary-button>
                        </div>
                    </form>
                     <div class="mt-4 text-sm">
                        <a href="{{ route('admin.surat.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                            &larr; Kembali ke Daftar Pengajuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>