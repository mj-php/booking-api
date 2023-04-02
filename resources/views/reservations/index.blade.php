@extends('app')
@section('title', 'Reservations')
@section('content')
    <div class="row mb-2">
        <div class="col-lg-9 col-md-12 d-flex align-items-end flex-column p-0">
            <div class="float-end">
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary fs-8" data-bs-toggle="modal"
                        data-bs-target="#add_reservation_modal">
                        <i class="fa fa-plus mx-2"></i>Add Reservation
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-md-12">
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
                {data: 'days', name: 'days', class: 'dt-center', title: 'Days'},
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
                let rowId = data.id;
                let deleteRowRoute = '{{ route('reservations.destroy', ":id") }}';

                $(row).attr('data-reservation_id', rowId);

                deleteRowRoute = deleteRowRoute.replace(':id', rowId);

                let actionsHtml = `<i class="fa fa-pen fa-1x cursor-pointer edit-reservation"></i>
                        <form class="d-inline remove-reservation" action="` + deleteRowRoute + `" method="POST">
                        @csrf
                @method('DELETE')
                <button type="submit" class="bg-transparent border-0"><i class="fa fa-trash fa-1x cursor-pointer"></i></button>
                </form>`;

                $(row).find('td:last-child').html(actionsHtml);
            }
        });

        $(document).on('shown.bs.modal', '#add_reservation_modal', function () {
            $("#add_end_date").datepicker(datepickerOptions);
            $("#add_start_date").datepicker(datepickerOptions);
        });

        $(document).on('shown.bs.modal', '#edit_reservation_modal', function () {
            $("#edit_start_date").datepicker(datepickerOptions);
            $("#edit_end_date").datepicker(datepickerOptions);
        });

        $('#edit_reservation_date_range').on('apply.daterangepicker', function (ev, picker) {
            let startDate = picker.startDate.format('YYYY-MM-DD');
            let endDate = picker.endDate.format('YYYY-MM-DD');
            let startDateObject = new Date(startDate);
            let endDateObject = new Date(endDate);

            let days = (endDateObject - startDateObject) / (1000 * 60 * 60 * 24);
            days = Math.round(days) + 1;

            $("#edit_days").val(days);
        });

        $(document).on('click', '#add_reservation, #edit_reservation', function () {
            let buttonId = this.id;
            let form = $("#" + buttonId + '_form');
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

                        if (buttonId === 'add_reservation') {
                            $("#add_reservation_modal").modal('hide');
                            $('#add_reservation_form').trigger("reset");
                        }
                    });
                },
                error: function (data) {
                    let html = `<div>`;

                    if (data.responseJSON.errors.data && data.responseJSON.errors.data[0]) {
                        $.each(data.responseJSON.errors.data[0], function (i, el) {
                            html += `date: ` + el.date +
                                `, available: <span style="color:green;font-size:bold">` + el.available +
                                `</span>, requested: <span style="color:red;font-size:bold">` + el.requested + "</span><br>";
                        });
                    } else {
                        html = data.responseJSON.message;
                    }

                    html += `</div>`;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: html,
                        backdrop: false
                    });
                }
            });
        });

        $(document).on('click', '.edit-reservation', function (event) {
            let row = $(this).parent();
            let data = reservationsTable.row(row).data();
            let reservationId = data.id;

            $('#edit_reservation_form input[name="reservation_id"]').val(reservationId);
            $('#edit_reservation_form input[name="vacancies"]').val(data.vacancies);
            $('#edit_reservation_form input[name="start_date"]').val(data.start_date);
            $('#edit_reservation_form input[name="end_date"]').val(data.end_date);
            $('#edit_reservation_form input[name="days"]').val(data.days);

            let reservationUpdateRoute = '{{ route('reservations.update', ":id") }}';

            reservationUpdateRoute = reservationUpdateRoute.replace(':id', reservationId);

            $("#edit_reservation_form").prop('action', reservationUpdateRoute);
            $("#edit_reservation_form").attr('method', 'put');
            $("#edit_reservation_modal").modal('show');

        });

        $(document).on('submit', '.remove-reservation', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You would not be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $(this);
                    let url = form.prop('action');
                    let formData = $(form).serialize();

                    $.ajax({
                        type: "POST",
                        dataType: 'JSON',
                        url: url,
                        data: formData,
                        success: function (data, textStatus, jqXHR) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                timer: 2000,
                                backdrop: false
                            }).then(function () {
                                reservationsTable.ajax.reload(null, false);
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
                }
            });
        });
    </script>
@endpush
