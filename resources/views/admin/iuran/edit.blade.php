{{-- resources/views/admin/iuran/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Catatan Iuran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.iuran.update', $iuranWarga->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Method spoofing untuk request UPDATE --}}

                        <div class="mt-4">
                            <x-input-label for="user_id" :value="__('Warga')" />
                            <select id="user_id" name="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Warga --</option>
                                @foreach ($wargas as $warga)
                                    <option value="{{ $warga->id }}" {{ old('user_id', $iuranWarga->user_id) == $warga->id ? 'selected' : '' }}>
                                        {{ $warga->name }} (NIK: {{ $warga->nik }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jenis_iuran" :value="__('Jenis Iuran')" />
                            <select id="jenis_iuran" name="jenis_iuran" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Jenis Iuran --</option>
                                @foreach ($jenisIuranOptions as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_iuran', $iuranWarga->jenis_iuran) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('jenis_iuran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bulan" :value="__('Bulan Iuran')" />
                            <select id="bulan" name="bulan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Bulan --</option>
                                @foreach ($bulanOptions as $key => $namaBulan)
                                    <option value="{{ $key }}" {{ old('bulan', $iuranWarga->bulan) == $key ? 'selected' : '' }}>{{ $namaBulan }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bulan')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tahun" :value="__('Tahun Iuran')" />
                            <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', $iuranWarga->tahun)" required min="2000" max="{{ $tahunSekarang + 5 }}" />
                            <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jumlah" :value="__('Jumlah (Rp)')" />
                            <x-text-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah', $iuranWarga->jumlah)" required min="0" step="1000" />
                            <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_bayar" :value="__('Tanggal Bayar')" />
                            <x-text-input id="tanggal_bayar" class="block mt-1 w-full" type="date" name="tanggal_bayar" :value="old('tanggal_bayar', $iuranWarga->tanggal_bayar ? $iuranWarga->tanggal_bayar->format('Y-m-d') : '')" required />
                            <x-input-error :messages="$errors->get('tanggal_bayar')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status_pembayaran" :value="__('Status Pembayaran')" />
                            <select id="status_pembayaran" name="status_pembayaran" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                @foreach ($statusPembayaranOptions as $key => $status)
                                    <option value="{{ $key }}" {{ old('status_pembayaran', $iuranWarga->status_pembayaran) == $key ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status_pembayaran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bukti_pembayaran" :value="__('Ganti Bukti Pembayaran (Opsional - Gambar maks 2MB)')" />
                            @if($iuranWarga->bukti_pembayaran)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $iuranWarga->bukti_pembayaran) }}" alt="Bukti Pembayaran Lama" class="max-h-40 rounded">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Bukti pembayaran saat ini. Upload file baru untuk mengganti.</p>
                                </div>
                            @endif
                            <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" accept="image/*">
                            <x-input-error :messages="$errors->get('bukti_pembayaran')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="keterangan" :value="__('Keterangan (Opsional)')" />
                            <textarea id="keterangan" name="keterangan" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('keterangan', $iuranWarga->keterangan) }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <x-secondary-button onclick="window.location='{{ route('admin.iuran.index') }}'" class="me-3">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update Catatan Iuran') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>