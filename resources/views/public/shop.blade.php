<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Payment - {{ $shop->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white shadow-xl rounded-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">{{ $shop->name }}</h1>
            <p class="text-gray-500 text-sm">Unique ID: {{ $shop->unique_id }}</p>
        </div>

        <div class="space-y-4 border-t border-b py-6 mb-8">
            <div class="flex justify-between">
                <span class="text-gray-600">Location:</span>
                <span class="font-medium">{{ $shop->lga }}, {{ $shop->ward }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Occupant:</span>
                <span class="font-medium">{{ $shop->occupant->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between text-lg font-bold">
                <span class="text-gray-800">Outstanding:</span>
                <span class="text-red-600">₦{{ number_format($outstandingAmount, 2) }}</span>
            </div>
        </div>

        <button class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-indigo-700 transition duration-300">
            Pay Now via Paystack
        </button>
        
        <p class="mt-6 text-center text-xs text-gray-400">
            Powered by Government Revenue System
        </p>
    </div>
</body>
</html>
