{{-- resources/views/admin/iuran/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Catat Iuran Warga Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.iuran.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="user_id" :value="__('Warga')" />
                            <select id="user_id" name="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Warga --</option>
                                @foreach ($wargas as $warga)
                                    <option value="{{ $warga->id }}" {{ old('user_id') == $warga->id ? 'selected' : '' }}>
                                        {{ $warga->name }} (NIK: {{ $warga->nik }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jenis_iuran" :value="__('Jenis Iuran')" />
                            {{-- Menggunakan select untuk contoh, bisa juga input text biasa --}}
                            <select id="jenis_iuran" name="jenis_iuran" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Jenis Iuran --</option>
                                @foreach ($jenisIuranOptions as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_iuran') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                            {{-- Jika ingin input text:
                            <x-text-input id="jenis_iuran" class="block mt-1 w-full" type="text" name="jenis_iuran" :value="old('jenis_iuran')" required />
                            --}}
                            <x-input-error :messages="$errors->get('jenis_iuran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bulan" :value="__('Bulan Iuran')" />
                            <select id="bulan" name="bulan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Bulan --</option>
                                @foreach ($bulanOptions as $key => $namaBulan)
                                    <option value="{{ $key }}" {{ old('bulan', date('n')) == $key ? 'selected' : '' }}>{{ $namaBulan }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bulan')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tahun" :value="__('Tahun Iuran')" />
                            <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', $tahunSekarang)" required min="2000" max="{{ $tahunSekarang + 5 }}" />
                            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jumlah" :value="__('Jumlah (Rp)')" />
                            <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah')" required min="0" step="1000" />
                            <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_bayar" :value="__('Tanggal Bayar')" />
                            <x-text-input id="tanggal_bayar" class="block mt-1 w-full" type="date" name="tanggal_bayar" :value="old('tanggal_bayar', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('tanggal_bayar')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status_pembayaran" :value="__('Status Pembayaran')" />
                            <select id="status_pembayaran" name="status_pembayaran" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach ($statusPembayaranOptions as $key => $status)
                                    <option value="{{ $key }}" {{ old('status_pembayaran', 'lunas') == $key ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status_pembayaran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bukti_pembayaran" :value="__('Bukti Pembayaran (Opsional - Gambar maks 2MB)')" />
                            <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" accept="image/*">
                            <x-input-error :messages="$errors->get('bukti_pembayaran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('keterangan') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button onclick="window.location='{{ route('admin.iuran.index') }}'" class="me-3">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Simpan Catatan Iuran') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>