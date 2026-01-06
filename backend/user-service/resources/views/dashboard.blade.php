<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Dashboard</h2>

<h3>Logged-in User</h3>
<p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Role:</strong> {{ $user->role }}</p>

<hr>

<h3>All Users</h3>

@if($users->isEmpty())
    <p>No users found.</p>
@else
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>GUID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $u)
            <tr>
                <td>{{ $u->user_id }}</td>
                <td>{{ $u->guid }}</td>
                <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone }}</td>
                <td>{{ $u->role }}</td>
                <td>{{ $u->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>
