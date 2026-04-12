<div class="min-h-screen bg-gray-100">

    <!-- Navbar -->
    <div class="bg-white shadow p-4 flex justify-between">
        <h1 class="text-xl font-bold text-blue-600">
            Sistem Prediksi Kerusakan Motor
        </h1>

        <div>
            @auth
                <span class="mr-3">Halo, {{ auth()->user()->name }}</span>

                <a href="/prediksi" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Mulai Prediksi
                </a>

                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button class="ml-2 bg-red-500 text-white px-4 py-2 rounded">
                        Logout
                    </button>
                </form>
            @else
                <a href="/login" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Login
                </a>

                <a href="/register" class="ml-2 bg-green-500 text-white px-4 py-2 rounded">
                    Register
                </a>
            @endauth
        </div>
    </div>

    <!-- Hero Section -->
    <div class="text-center mt-20">
        <h2 class="text-3xl font-bold mb-4">
            Sistem Prediksi Kerusakan Mesin Sepeda Motor
        </h2>

        <p class="text-gray-600 mb-6">
            Gunakan sistem ini untuk membantu mendiagnosa kerusakan mesin berdasarkan gejala yang dialami.
        </p>

        @auth
            <a href="/prediksi" class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                Mulai Prediksi Sekarang
            </a>
        @else
            <a href="/login" class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                Login untuk Mulai
            </a>
        @endauth
    </div>

</div>