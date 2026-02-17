<!DOCTYPE html>
<x-layout title="Logs">

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Logs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
        }

        .page-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 30px;
        }

        .card h1 {
            margin-bottom: 25px;
            font-size: 1.8rem;
            font-weight: 600;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        thead {
            background: linear-gradient(135deg, #4CAF50, #2e7d32);
            color: white;
        }

        th, td {
            padding: 12px 10px;
            text-align: left;
        }

        th {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s ease;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tbody tr:hover {
            background-color: #eef7f0;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-created { background: #e8f5e9; color: #2e7d32; }
        .badge-updated { background: #e3f2fd; color: #1565c0; }
        .badge-deleted { background: #ffebee; color: #c62828; }
        .badge-default { background: #f3f4f6; color: #374151; }

        .changes-box {
            max-width: 400px;        /* limit horizontal width */
            overflow-x: auto;        /* add horizontal scroll if needed */
            white-space: pre-wrap;   /* wrap lines when possible */
            word-wrap: break-word;
            background: #f8fafc;
            padding: 8px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .empty-row {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

<div style="overflow-x:auto;">
    <div class="card">
        <h1>Activity Logs</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Subject</th>
                    <th>Changes</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->causer ? $log->causer->name : 'System' }}</td>

                        <td>
                            @php
                                $action = strtolower($log->description);
                            @endphp

                            <span class="badge
                                {{ $action === 'created' ? 'badge-created' :
                                   ($action === 'updated' ? 'badge-updated' :
                                   ($action === 'deleted' ? 'badge-deleted' : 'badge-default')) }}">
                                {{ ucfirst($log->description) }}
                            </span>
                        </td>

                        <td>{{ class_basename($log->subject_type) }}</td>

                        <td>
                            @php
                                $attributes = $log->properties['attributes'] ?? [];
                            @endphp

                            <strong>Title:</strong> {{ $attributes['titlos'] ?? '—' }} <br>
                            <strong>Author:</strong> {{ $attributes['syggrafeas'] ?? '—' }} <br>
                            <strong>Publisher:</strong> {{ $attributes['ekdoths'] ?? '—' }} <br>
                            <strong>Pages:</strong> {{ $attributes['selides'] ?? '—' }} <br>
                            <strong>Year:</strong> {{ $attributes['etosEkdoshs'] ?? '—' }}
                        </td>

                        <td class="timestamp">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-row">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

</body>
</html>
</x-layout>