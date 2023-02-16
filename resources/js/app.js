import './bootstrap';
import $ from "jquery";
import dataTable from "datatables.net-bs5";
import dateRangePicker from "daterangepicker";
import Swal from 'sweetalert2/dist/sweetalert2.js';

window.$ = window.jQuery = $;
window.daterangepicker = dateRangePicker;

window.DataTable = dataTable;

global.Swal = Swal;
global.convertFormToJSON = function convertFormToJSON(form) {
    return $(form)
        .serializeArray()
        .reduce(function (json, {name, value}) {
            json[name] = value;
            return json;
        }, {});
}
