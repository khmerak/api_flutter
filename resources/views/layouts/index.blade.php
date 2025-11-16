<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md h-screen p-5">
            <h2 class="text-2xl font-bold mb-6">Admin Panel</h2>

            <ul class="space-y-3">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gray-200' : '' }} block px-4 py-2 rounded hover:bg-gray-200">Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('product') }}" class="{{ request()->routeIs('product') ? 'bg-gray-200' : '' }} block px-4 py-2 rounded hover:bg-gray-200">Products</a>
                </li>
                <li>
                    <a href="{{ route('category') }}" class="{{ request()->routeIs('category') ? 'bg-gray-200' : '' }} block px-4 py-2 rounded hover:bg-gray-200">Category</a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 rounded hover:bg-gray-200">Users</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-3xl font-semibold mb-4">Dashboard</h1>

            <!-- Stats Widgets -->
            @yield('main')
        </main>
    </div>

</body>
</html>

