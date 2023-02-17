<div class="modal fade" id="add_reservation_modal" tabindex="-1" role="dialog" aria-labelledby="add_reservationLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content mw-300">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title">Add Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('reservations.store') }}" id="add_reservation_form" autocomplete="off">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="element">Place:</label>
                                @include('partials.elements_dropdown',['dropdownId' => 'element_id'])
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="reservation_date_range">Reservation Date:</label>
                                <input class="form-control" name="reservation_date_range" id="add_reservation_date_range">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="reservation_number">Vacancies:</label>
                                <input type="number" class="form-control" name="vacancies" id="add_vacancies">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="days">Reservation Days:</label>
                                <input type="number" class="form-control" name="days" id="add_days">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="add_reservation" type="button" class="btn btn-secondary">Save changes</button>
            </div>
        </div>
    </div>
</div>
