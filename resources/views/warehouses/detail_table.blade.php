
    @if($warehouseData->isNotEmpty())
        
        @foreach($warehouseData as $key => $work)
            <tr id="warehouse_item_{{ $work->id }}">
                <td>{{ $key + 1 }}</td>
                <td>{{ $work->item_name ?? 'N/A' }}</td>
                <td>{{ $work['pieces'] }}</td>
                <td>{{ $work['unit_id'] }}</td>
                <td>{{ $work['measurements'] }}</td>
                <td>{{ \Carbon\Carbon::parse($work['created_at'])->format('d M Y') }}</td>
                <td>
                    <x-button.edit class="btn-icon" route="{{ route('w_edit', $work['id']) }}" />
                    
                    <button type="button" data-route="{{route('w-destroy',$work['id'])}}" class="btn-icon delete-w-detail btn btn-primary btn btn-outline-danger btn-icon" data-id="{{ $work['id'] }}">
                        <x-icon.trash/>
                    </button>
                </td>
            </tr>
        @endforeach
    @else
            <tr>
                <td colspan="7">Record not found</td>a
            </tr>
    @endif
