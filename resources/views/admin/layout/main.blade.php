<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Camping Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Camping Admin</a>
        <div class="d-flex">
            @auth
                <span class="navbar-text me-3">
                    {{ auth()->user()->nama ?? auth()->user()->username }}
                </span>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 mb-4">
            @include('admin.layout.sidebar')
        </aside>
        <main class="col-md-9 col-lg-10">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


