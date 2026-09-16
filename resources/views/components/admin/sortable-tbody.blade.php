@props(['resource', 'items'])
<tbody
    x-data="sortableList({
        reorderUrl: '{{ route('admin.reorder', $resource) }}',
        csrfToken: '{{ csrf_token() }}'
    })"
    data-slot="table-body"
>
    {{ $slot }}
</tbody>