@forelse($pageObj as $p)
<tr>
    <td>{{ $p->ari8mosEisagoghs }}</td>
    <td>{{ $p->hmeromhnia_eis ?? '-' }}</td>
    <td>{{ $p->syggrafeas ?? '-' }}</td>
    <td>{{ $p->koha ?? '-' }}</td>
    <td>{{ $p->titlos ?? '-' }}</td>
    <td>{{ $p->ekdoths ?? '-' }}</td>
    <td>{{ $p->ekdosh ?? '-' }}</td>
    <td>{{ $p->etosEkdoshs ?? '-' }}</td>
    <td>{{ $p->toposEkdoshs ?? '-' }}</td>
    <td>{{ $p->sxhma ?? '-' }}</td>
    <td>{{ $p->selides ?? '-' }}</td>
    <td>{{ $p->tomos ?? '-' }}</td>
    <td>{{ $p->troposPromPar ?? '-' }}</td>
    <td>{{ $p->ISBN ?? '-' }}</td>
    <td class="actions">
        <a href="{{ route('people.edit', $p->ari8mosEisagoghs) }}" class="btn btn-edit">✏️ Edit</a>
        @if(auth()->user()?->is_admin)
            <button onclick="confirmDelete({{ $p->ari8mosEisagoghs }}, '{{ addslashes($p->titlos ?? 'this record') }}')" class="btn btn-delete">🗑️ Delete</button>
        @endif
    </td>
</tr>
@empty
<tr><td colspan="15" style="text-align:center;">No records found</td></tr>
@endforelse
