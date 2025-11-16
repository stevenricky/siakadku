<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-lg text-center">
        <div class="text-blue-500 text-6xl mb-4">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-4">404 - Halaman Tidak Ditemukan</h1>
        
        <p class="text-gray-600 mb-6">
            Halaman yang Anda cari tidak ditemukan. Mungkin telah dipindahkan atau dihapus.
        </p>
        
        <div class="space-y-3">
            <a href="{{ url('/') }}" 
               class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <i class="fas fa-home mr-2"></i>Kembali ke Beranda
            </a>
            
            <a href="javascript:history.back()" 
               class="inline-block px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium ml-2">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</body>
</html>