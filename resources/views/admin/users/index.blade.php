@extends('layouts.admin')

@section('title', 'Data User - BANSOS KMEANS')

@section('content')
    <div
        style="background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:32px 40px; max-width:900px; margin:0 auto;">
        <h1 style="font-size:2rem; color:#888fa6; font-weight:400; margin-bottom:18px;">Data User</h1>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
                <a href="{{ route('admin.users.create') }}"
                    style="background:#22c55e; color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:1rem; cursor:pointer; text-decoration:none;">Tambah
                    Data</a>
                <div>
                    <label for="search" style="color:#888fa6; font-size:1rem; margin-right:8px;">Search:</label>
                    <input type="text" id="search" name="search"
                        style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;"
                        value="{{ request('search') }}" oninput="searchUsers(this.value)">
                </div>
            </div>
        <div style="display:flex; align-items:center; margin-bottom:18px;">
            <label for="entries" style="color:#888fa6; font-size:1rem; margin-right:8px;">Show</label>
            <select id="entries" name="entries"
                style="padding:7px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:1rem;"
                onchange="this.form.submit()">
                <option value="10" {{ request('entries', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span style="color:#888fa6; font-size:1rem; margin-left:8px;">entries</span>
        </div>

        <table style="width:100%; border-collapse:collapse; margin-bottom:18px; text-align:center;">
            <thead>
                <tr style="background:#f4f6fa; color:#888fa6;">
                    <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Nomor</th>
                    <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Username</th>
                    <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Role</th>
                    <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Password</th>
                    <th style="padding:10px; border-bottom:1px solid #e5e7eb;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td style="padding:10px; border-bottom:1px solid #e5e7eb;">{{ $users->firstItem() + $index }}</td>
                        <td style="padding:10px; border-bottom:1px solid #e5e7eb;">{{ $user->username }}</td>
                        <td style="padding:10px; border-bottom:1px solid #e5e7eb;">{{ strtoupper($user->role) }}</td>
                        <td style="padding:10px; border-bottom:1px solid #e5e7eb; word-break:break-word;">
                        {{ substr($user->password, 0, floor(strlen($user->password)/2)) }}<br>
                        {{ substr($user->password, floor(strlen($user->password)/2)) }}
                        </td>
                        <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                style="background:#2563eb; color:#fff; border:none; border-radius:4px; padding:6px 14px; margin-right:6px; cursor:pointer; text-decoration:none;">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background:#ef4444; color:#fff; border:none; border-radius:4px; padding:6px 14px; cursor:pointer;"
                                    onclick="return confirm('Are you sure you want to delete this user?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:10px; text-align:center; color:#888fa6;">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div style="color:#888fa6; font-size:0.95rem;">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
            </div>
            <div>
                {{ $users->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </div>
    <script>
        function searchUsers(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('search', value);
            window.location.href = url.toString();
        }
    </script>
@endsection