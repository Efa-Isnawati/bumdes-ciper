<!DOCTYPE html>
<html>
<head>
    <title>Laporan Customer Review Bumdes</title>
    <style>
        /* Gaya tampilan untuk cetak ulasan */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            font-size: 24px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Laporan Customer Review Bumdes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->product->name }}</td>
                    <td>{{ $review->user->name }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>{{ $review->comment }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
