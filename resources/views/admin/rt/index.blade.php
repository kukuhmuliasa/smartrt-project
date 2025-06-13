<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('RT yang Anda Kelola') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Ubah background dari putih ke #E0F2F1 -->
            <div class="bg-[#E0F2F1] overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($rts->isNotEmpty())
                        @foreach($rts as $rt)
                            <!-- Jika ingin kotak RT juga punya latar belakang tertentu, tambahkan bg-* di sini -->
                            <div class="mb-4 p-4 border rounded bg-white">
                                <h3 class="text-lg font-semibold">{{ $rt->nama_rt }}</h3>
                                <p>{{ $rt->alamat_rt }}</p>
                                <p class="text-sm text-gray-600">Terdaftar pada: {{ $rt->created_at->format('d M Y H:i') }}</p>
                                {{-- Tambahkan link edit/detail nanti --}}
                            </div>
                        @endforeach
                    @else
                        <p>Anda belum mendaftarkan RT. <a href="{{ route('admin.rt.create') }}" class="text-indigo-600 hover:text-indigo-900">Daftarkan sekarang</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>