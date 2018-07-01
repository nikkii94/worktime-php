import 'bootstrap';
import fontawesome from '@fortawesome/fontawesome-free';
import datetimepicker from'bootstrap4-datetimepicker';
import dt from 'datatables.net-bs4';
import 'datatables.net-responsive-bs4';

import '../css/style.scss';

(function(window, $){

    'use strict';

    const CALCULATE_ERROR_HTML = $('#calculatedTime');
    const COLORING_SWITCH_HTML = $('#enableColoring');
    const ERROR_INFO_HTML = $('#showErrorMessage');

    const ONE_DAY_IN_MINUTES = 24 * 60;

    const ICONS = {
        time:       'far fa-clock',
        date:       'fas fa-calendar-alt',
        up:         'fas fa-chevron-up',
        down:       'fas fa-chevron-down',
        previous:   'fas fa-chevron-left',
        next:       'fas fa-chevron-right',
        today:      'fas fa-camera',
        clear:      'far fa-trash-alt',
        close:      'fas fa-times'
    };

    const TIME_PICKER_OBJECT = {
        format: 'HH:mm',
        locale: 'hu',
        stepping: 1,
        useCurrent: false,
        icons: ICONS
    };

    const WORK_TIME_ADD_URL = "/worktime/add";
    const WORK_TIME_DELETE_URL = "/worktime/delete";
    const WORK_TIME_LIST_URL = "/worktime/list/json";
    const WORK_TIME_LIST_DATA_SOURCE = "data";

    const ACTION_BUTTONS_HTML = `
        <a data-action="editEmployeeData" class="btn btn-primary btn-sm" href="#">Szerkesztés</a>
        <a data-action="deleteEmployeeData" class="btn btn-danger btn-sm" href="#">Törlés</a>
    `;

    const DATA_TABLE_HTML = $('#workTimeAjax');
    const DATA_TABLE = DATA_TABLE_HTML.DataTable({
        "columnDefs": [ {
            "targets": '_all',
            "createdCell": function (td, cellData, rowData, row, col) {
                let keys = Object.keys(rowData);
                $(td).attr( 'data-input', keys[col] );
                $(td).attr( 'data-value', rowData[keys[col]] );
            }
        } ],
        "ajax": {
            "url" : WORK_TIME_LIST_URL,
            "dataSrc" : WORK_TIME_LIST_DATA_SOURCE
        },
        "columns": [
            { "data": "date", "orderable": true },
            { "data": "name", "orderable": true },
            { "data": "start_time", "orderable": false },
            { "data": "end_time", "orderable": false },
            { "data": "total_work_time", "orderable": false },
            { "data": "sunday_bonus", "orderable": false },
            { "data": null, "orderable": false, "defaultContent": ACTION_BUTTONS_HTML }
        ],
        "createdRow": function( row, data, dataIndex ) {
            $(row).attr( 'data-id', data['id'] );
        }
    });

    const FORM_HTML = $('#workTimeForm');

    const workTimeFormSubmit = (e) => {

        e.preventDefault();
        let form = $(e.currentTarget)[0];
        let formData = $(e.currentTarget).serializeArray();

        if (form.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');
            return false;
        }


        $.ajax({
            url: WORK_TIME_ADD_URL,
            method: 'POST',
            data: formData
        }).done((response) => {

            response = JSON.parse(response);
            if ( response.hasOwnProperty('type') && response.type === 'success' ){
                form.reset();
                form.classList.remove('was-validated');

                let employee = formData.find( (obj) => { return obj.name === 'employee'; });
                let date = formData.find( (obj) => { return obj.name === 'date'; });

                DATA_TABLE.ajax.reload( () => {
                    colorUpdatedRow(employee, date);
                }, true );
            }

            showAjaxResult(response.type, response.message);

        }).fail((error) => {
            console.log(error);
        });

    };

    const showAjaxResult = (type, message) => {

        ERROR_INFO_HTML.text(message);

        let $class = ( type === 'success' ) ? 'alert-success' : 'alert-danger';

        ERROR_INFO_HTML.addClass($class);
        ERROR_INFO_HTML.fadeIn();

        setTimeout( () => {
            ERROR_INFO_HTML.removeClass($class).fadeOut();
            ERROR_INFO_HTML.text('');
        }, 15000 )


    };
    
    const colorUpdatedRow = (employee, date ) => {


        let tr = DATA_TABLE_HTML.find('tr:has(td[data-value="'+employee.value+'"]):has(td[data-value="'+date.value+'"])');

        $(tr).addClass('bg-success text-white');

        setTimeout(() => {
            $(tr).removeClass('bg-success text-white');
        }, 2000);
        
    }; 

    const calculateTime = () => {

        CALCULATE_ERROR_HTML.fadeOut();

        let start_time = $('#start_time').val();
        let end_time = $('#end_time').val();

        if ( start_time === '' || end_time === '' ){
            return;
        }

        let timeRegex = /^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/;

        if( timeRegex.test(start_time) === false || timeRegex.test(end_time) === false ){
            CALCULATE_ERROR_HTML.removeClass('alert-info').addClass('alert-danger')
                .text('Nem megfelelő idő formátum!');
            CALCULATE_ERROR_HTML.fadeIn();
                return;
        }

        let $dateValue = $('#date').val();
        let date =  ($dateValue !== '' && $dateValue !== undefined) ? new Date($dateValue) : new Date();

        /*if ( ! date ){
            CALCULATE_ERROR_HTML.removeClass('alert-info').addClass('alert-danger')
                .text('Először a dátumot kell megadni!');
            CALCULATE_ERROR_HTML.fadeIn();
            return;
        }*/

        let startTime = start_time.split(':');
        let endTime = end_time.split(':');

        let start = new Date(date);
            start.setHours( startTime[0] );
            start.setMinutes(startTime[1] );

        let end = new Date(date);
        end.setHours( endTime[0] );
        end.setMinutes( endTime[1] );

        let timePassed = new Date(end - start);

        if ( timePassed.getUTCMinutes() > ONE_DAY_IN_MINUTES  ){
            CALCULATE_ERROR_HTML.removeClass('alert-info').addClass('alert-danger')
                .text('24 óránál nem dolgozhat többet!');
            CALCULATE_ERROR_HTML.fadeIn();
            return;
        }

        let finalValue = '';
        finalValue += timePassed.getUTCHours() < 10 ? '0' + timePassed.getUTCHours() : timePassed.getUTCHours();
        finalValue += ':';
        finalValue += timePassed.getUTCMinutes() < 10 ? '0' + timePassed.getUTCMinutes() : timePassed.getUTCMinutes();


        if (checkForSunday(date)){
            $('#total_work_time').val(finalValue);
            $('#sunday_bonus').val(finalValue);

        }else{
            $('#total_work_time').val(finalValue);
            $('#sunday_bonus').val('00:00');
        }

        CALCULATE_ERROR_HTML.fadeOut();

    };

    const checkForSunday = (date) => {

        date = new Date(date);
        return  date instanceof Date === true && date.getDay() === 0;
    };

    const loadDataToForm = (event) => {

        event.preventDefault();
        let dataArray = $(event.target).closest('tr').find('td').toArray();
            dataArray.pop(); // remove actions

        FORM_HTML.removeClass('was-validated');

        dataArray.forEach( (element) => {
            let data = $(element).data();
            FORM_HTML.find($('#'+data['input']).val(data['value']));
        })
    };

    const deleteData = (event) => {

        event.preventDefault();

        let $row = $(event.target).closest('tr');
        let rowID = $row.data('id');
        let employeeID = $row.find('td[data-input="employee_id"]').data('value');
        let date = $row.find('td[data-input="date"]').data('value');

        $.ajax({
            url: WORK_TIME_DELETE_URL,
            method: 'POST',
            data: {
                id: rowID,
                employeeID: employeeID,
                date: date
            }
        }).done((response) => {
            response = JSON.parse(response);
            if ( response.hasOwnProperty('type') === true && response.type === 'success' ){
                $($row).fadeOut(2000).remove();
            }
            showAjaxResult(response.type, response.message);
        }).fail((error) => {
            console.log(error);
        });

    };

    const colorTrackingTable = () => {

        let switchValue = COLORING_SWITCH_HTML.prop('checked');
        let records = $('#trackingTable').find('th[data-class],td[data-class]');
        if( switchValue === true ){
            records.each( (index, record) => {
                $(record).addClass($(record).data('class'));
            });
        }else{
            records.each( (index, record) => {
                $(record).removeClass($(record).data('class'));
            });
        }

    };

    $('#date').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'hu',
        icons: ICONS
    });
    $('#start_time').datetimepicker(TIME_PICKER_OBJECT);
    $('#end_time').datetimepicker(TIME_PICKER_OBJECT);

    FORM_HTML.on('submit', workTimeFormSubmit);

    $(document).on('click', '[data-action="editEmployeeData"]', loadDataToForm);
    
    $(document).on('click', '[data-action="deleteEmployeeData"]', deleteData);

    $(COLORING_SWITCH_HTML).on('change', colorTrackingTable);

    $(document).on('dp.change', (e) => {
       if ( e.target.id === 'start_time' || e.target.id === 'end_time' ) {
           calculateTime();
       }
    });

})(window, jQuery);
