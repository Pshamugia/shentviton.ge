<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    @vite([
        'resources/css/app.css'
        ])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar styling */
        .admin-sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 60px;
        }
        .admin-sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }
        .admin-sidebar a:hover {
            background-color: #495057;
        }
        .admin-content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Admin Sidebar -->
    <div class="admin-sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <a href="{{ route('admin.products.index') }}"><i class="fas fa-box"></i> Manage Products</a>
        <a href="{{ route('admin.cliparts.index') }}"><i class="fas fa-images"></i> Manage Cliparts</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>

    </div>

    <!-- Main Content Area -->
    <div class="admin-content">
        @yield('content')
    </div>

</body>
</htm
