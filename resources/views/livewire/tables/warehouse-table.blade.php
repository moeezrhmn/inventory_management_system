<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Warehouses') }}
            </h3>
        </div>

        <div class="card-actions">
            <x-action.create route="{{ route('warehouses.create') }}" />
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                entries
            </div>
            <div class="ms-auto text-secondary">
                Search:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                        aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
                <tr>
                    <th class="align-middle text-center w-1">
                        {{ __('#No') }}
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('name')" href="#" role="button">
                            {{ __('Name') }}
                            @include('inclues._sort-icon', ['field' => 'name'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('location')" href="#" role="button">
                            {{ __('Location') }}
                            
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('description')" href="#" role="button">
                            {{ __('description') }}
                            @include('inclues._sort-icon', ['field' => 'description'])
                        </a>
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warehouses as $warehouse)
                <tr id="warehouse_row_{{ $warehouse->id }}">
                    <td class="align-middle text-center" style="width: 10%">
                        {{ $loop->index+1 }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $warehouse->name }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $warehouse->location }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $warehouse->description }}
                    </td>
                    <td class="align-middle text-center" style="width: 15%">
                        <x-button.show class="btn-icon" route="{{ route('warehouses.show', $warehouse) }}" />
                        <x-button.edit class="btn-icon" route="{{ route('warehouses.edit', $warehouse) }}" />
                        <button type="button" class="btn-icon delete-warehouse btn btn-primary btn-outline-danger btn-icon" 
                            data-route="{{ route('warehouses.destroy', $warehouse['id']) }}" 
                            data-id="{{ $warehouse['id'] }}">
                            <x-icon.trash />
                        </button>

                    </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="align-middle text-center" colspan="8">
                        No results found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $warehouses->firstItem() }}</span> to <span>{{ $warehouses->lastItem() }}</span> of
            <span>{{ $warehouses->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $warehouses->links() }}
        </ul>
    </div>
</div>