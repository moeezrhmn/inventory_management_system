
    @if($labourWorks->isNotEmpty())
        @foreach($labourWorks as $key => $work)
            <tr id="labourWorkRow-{{ $work->id }}">
                <td>{{ $key + 1 }}</td>
                <td>{{ $work->product->name ?? 'N/A' }}</td>
                <td>{{ $work['pieces'] }}</td>
                <td>{{ $work['payment'] }}</td>
                <td>{{ $work['description'] }}</td>
                <td>{{ \Carbon\Carbon::parse($work['created_at'])->format('d M Y') }}</td>
                <td>
                    <x-button.edit class="btn-icon" route="{{ route('labourwork.edit', $work['id']) }}" />
                    
                    <button type="button" class="btn-icon delete-labourwork btn btn-primary btn btn-outline-danger btn-icon" data-id="{{ $work['id'] }}">
                        <x-icon.trash/>
                    </button>
                </td>
            </tr>
        @endforeach
    @else
            <tr>
                <td colspan="7">Record not found</td>
            </tr>
    @endif
