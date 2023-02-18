import './bootstrap';
import $ from "jquery";
import dataTable from "datatables.net-bs5";
import 'jquery-ui/ui/widgets/datepicker.js';
import Swal from 'sweetalert2/dist/sweetalert2.js';

window.$ = window.jQuery = $;

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

global.countDays = function countDays(startDateContainerId, endDateContainerId) {
    let startDate = $("#" + startDateContainerId).val();
    let endDate = $("#" + endDateContainerId).val();
    let startDateObject = 0;
    let endDateObject = 0;

    if (startDate) {
        startDateObject = new Date(startDate);
    }

    let days = 0;

    if (endDate) {
        endDateObject = new Date(endDate);
        days = (endDateObject - startDateObject) / (1000 * 60 * 60 * 24);
    }

    return Math.round(days) + 1;
}

global.datepickerOptions = {
    dateFormat: 'yy-mm-dd',
    showOtherMonths: true,
    todayHighlight: true,
    minDate: 0,
    onSelect: function (dateText, element) {
        let action = element.id.split('_')[0];

        if (element.id !== action + '_end_date') {
            $("#" + action + "_end_date").datepicker("option", "minDate", dateText);
        }

        let days = countDays(action + "_start_date", action + "_end_date");

        $("#" + action + "_days").val(days);
    }
}
