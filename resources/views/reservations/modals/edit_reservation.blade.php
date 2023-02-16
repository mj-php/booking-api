<div class="modal fade" id="editReservationModal" tabindex="-1" role="dialog" aria-labelledby="editReservationLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content mw-300">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title">Edit Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editReservationForm" autocomplete="off">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="reservation_number">Vacancies Number:</label>
                                <input type="number" class="form-control" name="reservation_vacancies" id="edit_vacancies">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="reservation_date">Reservation Date:</label>
                                <input class="form-control" name="reservation_date" id="edit_reservation_date">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="editReservation" type="button" class="btn btn-secondary">Save changes</button>
            </div>
        </div>
    </div>
</div>

