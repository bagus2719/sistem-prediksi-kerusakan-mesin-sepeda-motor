<div class="max-w-7xl mx-auto">

    <!-- Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Riwayat Prediksi
        </h1>
        <p class="text-gray-500">
            Daftar hasil prediksi yang pernah dilakukan
        </p>
    </div>

    <!-- Table Card -->
    <div class="bg-white p-6 rounded-xl shadow">

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left text-gray-500">

                <!-- Header -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Gejala Dipilih</th>
                        <th class="px-6 py-3">Hasil Prediksi</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody>

                    <!-- Dummy Data (sementara) -->
                    @for ($i = 1; $i <= 5; $i++)
                        <tr class="bg-white border-b hover:bg-gray-50">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $i }}
                            </td>

                            <td class="px-6 py-4">
                                2026-04-11
                            </td>

                            <td class="px-6 py-4">
                                Sulit Hidup, Asap Tebal
                            </td>

                            <td class="px-6 py-4">
                                Piston Aus
                            </td>

                            <td class="px-6 py-4 text-center space-x-2">

                                <!-- Detail -->
                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                    Detail
                                </button>

                                <!-- Hapus -->
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs">
                                    Hapus
                                </button>

                            </td>

                        </tr>
                    @endfor

                </tbody>

            </table>

        </div>

    </div>

</div>