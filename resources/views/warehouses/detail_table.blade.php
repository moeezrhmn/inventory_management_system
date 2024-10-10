
    @if($warehouseData->isNotEmpty())
        
        @foreach($warehouseData as $key => $work)
            <tr id="warehouse_item_{{ $work->id }}">
                <td>{{ \Carbon\Carbon::parse($work['created_at'])->format('d M Y') }}</td>
                <td>{{ $work->item_name ?? 'N/A' }}</td>
                <td>{{ $work->getTotalQuantityAttribute() }}</td>
                <td>{{ $work->unit->name }}</td>
                <td>{{ $work['measurements'] }}</td>
                <td class="d-flex " style="gap: 2px;" >
                    <x-button.show class="btn-icon" route="{{ route('warehouse.transactions.view', $work['id'] ) }}" />
                    <x-button.edit class="btn-icon" route="{{ route('w_edit', $work['id']) }}" />

                    <button type="button" data-route="" class="btn-icon delete-w-detail btn btn-primary btn btn-outline-danger btn-icon" data-id="{{ $work['id'] }}">
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
