{{-- resources/views/warga/surat/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajukan Surat Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('warga.surat.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="jenis_surat" :value="__('Jenis Surat yang Diajukan')" />
                            <select id="jenis_surat" name="jenis_surat" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                @foreach ($jenisSuratOptions as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_surat') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                            {{-- Jika jenis_surat adalah 'Lainnya', mungkin tampilkan input text tambahan --}}
                            <x-input-error :messages="$errors->get('jenis_surat')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="keperluan" :value="__('Keperluan Mengajukan Surat')" />
                            <textarea id="keperluan" name="keperluan" rows="5" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('keperluan') }}</textarea>
                            <x-input-error :messages="$errors->get('keperluan')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="file_pendukung_pemohon" :value="__('Unggah File Pendukung (Opsional - PDF/Gambar, maks 2MB)')" />
                            <input id="file_pendukung_pemohon" name="file_pendukung_pemohon" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" accept=".pdf,.jpg,.jpeg,.png">
                            <x-input-error :messages="$errors->get('file_pendukung_pemohon')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('warga.surat.index') }}'" class="me-3">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Kirim Pengajuan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>