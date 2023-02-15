@extends('app')
@section('title', 'Reservations')
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <table id="reservations">
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="module">
        $('#reservations').DataTable({
            ajax: "{{ route('reservations.list') }}",
            dom: 'Brtip',
            processing: true,
            "iDisplayLength": 25,
            columns: [
                {data: 'product_name', name: 'product_name', class: 'dt-left'},
                {data: 'department.department_name', name: 'department_name', class: 'dt-center'},
                {data: 'product_cost', name: 'product_cost', class: 'dt-center'},
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    class: 'dt-center'
                },
            ],
            createdRow: function (row, data, dataIndex) {
                let rowId = data.encrypted_id;
                let deleteRowRoute = '{{ route('reservations.destroy', ":id") }}';

                $(row).attr('data-encrypted_id', rowId);

                deleteRowRoute = deleteRowRoute.replace(':id', rowId);

                let actionsHtml = `<i class="fa fa-pen fa-1x cursor-pointer edit-product"></i>
                        <form class="d-inline remove-product" action="` + deleteRowRoute + `" method="POST">
                        @csrf
                @method('DELETE')
                <button type="submit" class="bg-transparent border-0"><i class="fa fa-trash fa-1x cursor-pointer"></i></button>
                </form>`;

                $(row).find('td:nth-child(4)').html(actionsHtml);
            }
        });
    </script>
@endpush
