<div>
    @php
        $labour = $labour[0];
        $labourWorks = $labour['labour_works'];
    @endphp
    <h3>{{ $labour['name'] }}'s History</h3>
    <div class="ms-auto text-secondary">
        Search:
        <div class="ms-2 d-inline-block">
            <input type="text" wire:model.live="search" class="form-control form-control-sm"
                aria-label="Search invoice">
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#No</th>
                <th>Product</th>
                <th>Pieces</th>
                <th>Payment</th>
                <th>Description</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($labourWorks as $key => $work)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $work['p_id'] }}</td>
                <td>{{ $work['pieces'] }}</td>
                <td>{{ $work['payment'] }}</td>
                <td>{{ $work['description'] }}</td>
                <td>{{ \Carbon\Carbon::parse($work['created_at'])->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    
</div>
