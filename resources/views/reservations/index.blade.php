@extends('app')
@section('title', 'Reservations')
@section('content')
    <div class="row mb-2">
        <div class="col-lg-6 col-md-12 d-flex align-items-end flex-column p-0">
            <div class="float-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary fs-8" data-bs-toggle="modal"
                        data-bs-target="#addReservationModal">
                        <i class="fa fa-plus mx-2"></i>Add Reservation
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <table id="reservations" class="table stripe table-fixed table-striped row-border table-bordered w-100">
            </table>
        </div>
    </div>
    @include('reservations.modals.add_reservation')
    @include('reservations.modals.edit_reservation')
@endsection
@push('scripts')
    <script type="module">
        let reservationsTable = $('#reservations').DataTable({
            ajax: "{{ route('reservations.list') }}",
            dom: 'Brtip',
            processing: true,
            "iDisplayLength": 25,
            columns: [
                {data: 'element.name', name: 'element_name', class: 'dt-center', title: 'Local'},
                {data: 'vacancies', name: 'vacancies', class: 'dt-center', title: 'Vacancies'},
                {data: 'start_date', name: 'start_date', class: 'dt-center', title: 'Start Date'},
                {data: 'end_date', name: 'end_date', class: 'dt-center', title: 'End Date'},
                {data: 'created_at', name: 'created_at', class: 'dt-center', title: 'Created At'},
                {data: 'updated_at', name: 'updated_at', class: 'dt-center', title: 'Updated At'},
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

                $(row).find('td:nth-child(7)').html(actionsHtml);
            }
        });

        $(document).on('shown.bs.modal', '#addReservationModal', function () {
            $("#add_reservation_date").daterangepicker({
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }
            );
        });

        $(document).on('click', '#addReservation', function () {
            let form = $("#addReservationForm");
            let url = form.prop('action');
            let method = form.attr('method');
            let formData = convertFormToJSON(form);

            $.ajax({
                type: "POST",
                dataType: 'JSON',
                url: url,
                method: method,
                data: formData,
                success: function (data, textStatus, jqXHR) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        backdrop: false
                    }).then(function () {
                        reservationsTable.ajax.reload();
                        $("#addReservationModal").modal('hide');
                        $('#addReservationForm').trigger("reset");
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        timer: 3000,
                        backdrop: false
                    });
                }
            });
        });

        $(document).on('click', '#editReservation', function () {
            let form = $("#editReservationForm");
            let url = form.prop('action');
            let method = form.attr('method');
            let formData = convertFormToJSON(form);

            $.ajax({
                type: "POST",
                dataType: 'JSON',
                url: url,
                method: method,
                data: formData,
                success: function (data, textStatus, jqXHR) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        backdrop: false
                    }).then(function () {
                        reservationsTable.ajax.reload();
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        timer: 3000,
                        backdrop: false
                    });
                }
            });
        });

        $(document).on('click', '.edit-reservation', function (event) {
            let row = $(this).parent();
            let data = reservationsTable.row(row).data();

            $('#editReservationForm input[name="encrypted_reservation_id"]').val(encrypted_reservation_id);
            $('#editReservationForm input[name="reservation_number"]').val(data.reservation_number);
            $('#editReservationForm input[name="reservation_date"]').val(data.reservation_date);
            $('#editReservationForm select[name="stocktake_id"]').val(data.encrypted_stocktake_id);

        });
    </script>
@endpush
