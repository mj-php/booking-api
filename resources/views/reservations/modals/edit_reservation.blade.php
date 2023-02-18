<div class="modal fade" id="edit_reservation_modal" tabindex="-1" role="dialog" aria-labelledby="edit_reservationLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content mw-300">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title">Edit Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="edit_reservation_form" autocomplete="off">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="element">Place:</label>
                                @include('partials.elements_dropdown',['dropdownId' => 'element_id'])
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input class="form-control" name="start_date" id="edit_start_date">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input class="form-control" name="end_date" id="edit_end_date">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="reservation_number">Vacancies:</label>
                                <input type="number" class="form-control" name="vacancies" id="edit_vacancies">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="days">Reservation Days:</label>
                                <input type="number" class="form-control" name="days" id="edit_days">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="edit_reservation" type="button" class="btn btn-secondary">Save changes</button>
            </div>
        </div>
    </div>
</div>

