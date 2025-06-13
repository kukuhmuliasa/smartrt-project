<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftarkan RT Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('info'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded">
                            {{ session('info') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.rt.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="nama_rt" :value="__('Nama RT (Contoh: RT 01/RW 05 Kelurahan Sukamaju)')" />
                            <x-text-input id="nama_rt" class="block mt-1 w-full" type="text" name="nama_rt" :value="old('nama_rt')" required autofocus />
                            <x-input-error :messages="$errors->get('nama_rt')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="alamat_rt" :value="__('Alamat Sekretariat RT')" />
                            <textarea id="alamat_rt" name="alamat_rt" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('alamat_rt') }}</textarea>
                            <x-input-error :messages="$errors->get('alamat_rt')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Daftarkan RT') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>