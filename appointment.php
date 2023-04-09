<style>
    .fc-event-title-container {
        text-align: center;
    }

    .fc-event-title.fc-sticky {
        font-size: 14px;
    }
    .fc-h-event .fc-event-title-container {
        background: linear-gradient(45deg, black, transparent);
    }
</style>
<?php
$appointments = $conn->query("SELECT * FROM `scheduler`  ");
$appoinment_arr = [];
while ($row = $appointments->fetch_assoc()) {
    if (!isset($appoinment_arr[$row['schedule']]))
        $appoinment_arr[$row['schedule']] = 0;
    $appoinment_arr[$row['schedule']] += 1;
}
?>
<div style="width:550px; height: 650px; margin: 0 auto;  display: block;" class="content py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-outline card-primary rounded-0 shadow" style="color: black; background-color: #4efb48;">
                <div class="card-header rounded-0">
                    <h4 class="card-title" style="color: black; font-style:italic">Appointment Availablity</h4>
                </div>
                <div class="card-body" style="background-color: #ffffff;">
                <h6 class="text-danger" style="font-size:13px;font-weight:normal"><i class="fas fa-info-circle"></i> Please take a moment to review the available dates and corresponding time slots, and select the one that suits your schedule best. </h6>
                    <div style="width:500px; height: 400px;" id="appointmentCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var calendar;
    var appointment = $.parseJSON('<?= json_encode($appoinment_arr) ?>') || {};
    start_loader();
    $(function () {
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()
        var Calendar = FullCalendar.Calendar;

        calendar = new Calendar(document.getElementById('appointmentCalendar'), {
            headerToolbar: {
                left: false,
                center: 'title',
            },
            selectable: true,
            themeSystem: 'bootstrap',
            //Random default events
            events: [
                {
                    daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // these recurrent events move separately
                    title: 0,
                    allDay: true,
                }
            ],
            eventClick: function (info) {
                console.log(info.el)
                if ($(info.el).find('.fc-event-title.fc-sticky').text() > 0)
                    uni_modal("Set an Appointment", "add_appointment.php?schedule=" + info.event.startStr, "mid-large")
            },
            validRange: {
                start: moment(date).format("YYYY-MM-DD"),
            },
            eventDidMount: function (info) {
                // console.log(appointment)
                if (!!appointment[info.event.startStr]) {
                    var available = parseInt(info.event.title) + parseInt(appointment[info.event.startStr]);
                    $(info.el).find('.fc-event-title.fc-sticky').text(available)
                }
                end_loader()
            },
            editable: false
        });

        calendar.render();
    })
</script>