@extends('app')
@section('title', 'Reservations')
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <table id="reservations" class="table stripe table-fixed table-striped row-border table-bordered w-100">
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
                {data: 'user_id', name: 'user_id', class: 'dt-left',title: 'User Id'},
                {data: 'start_date', name: 'start_date', class: 'dt-center',title: 'Start Date'},
                {data: 'end_date', name: 'end_date', class: 'dt-center',title: 'End Date'},
                {data: 'created_at', name: 'created_at', class: 'dt-center',title: 'Created At'},
                {data: 'updated_at', name: 'updated_at', class: 'dt-center',title: 'Updated At'},
                {
                    data: null,
                    name: 'action',
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    class: 'dt-center'
                },
            ],
            createdRow: function (row, data, dataIndex) {
                console.log(data);
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

                $(row).find('td:nth-child(6)').html(actionsHtml);
            }
        });
    </script>
@endpush
