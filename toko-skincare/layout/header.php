<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>All Day Skincare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #6c63ff;
            --primary-soft: #ede9fe;
            --bg: #f1f4f9;
            --text-muted: #6b7280;
        }

        body {
            background: var(--bg);
            color: #111827;
        }

        .sidebar {
            width: 240px;
            min-height: 100vh;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid #e5e7eb;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem .9rem;
            border-radius: .8rem;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            transition: .2s ease;
        }

        .sidebar-link:hover {
            background: #f3f4f6;
            color: var(--primary);
            transform: translateX(3px);
        }

        .sidebar-link.active {
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 700;
        }

        .card {
            border-radius: 1rem;
            transition: .2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
        }

        .table thead th {
            color: var(--primary);
            font-weight: 700;
            background: #eef2ff;
        }

        .product-img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: .8rem;
            background: #f3f4f6;
        }

        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                height: auto;
                position: relative;
            }
        }
    </style>
</head>

<body>
