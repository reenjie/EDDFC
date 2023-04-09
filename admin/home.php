<style>
    #cover-img {
        object-fit: cover;
        object-position: center center;
        width: 100%;
        height: 100%;
    }

    .fc-event-title-container {
        text-align: center;
       
    }

    .fc-event-title.fc-sticky {
        font-size: 14px;
        
    }
    .fc-h-event .fc-event-title-container {
        /* background: linear-gradient(45deg, black, transparent); */
        background-color: #2C3333;
    
    }

    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
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

<hr class="border-info">
<div id="top" class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow" style="background-color: brown;">
            <span class="info-box-icon bg-gradient-info elevation-1"><i class="fas fa-th-list"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Services</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `service_list` ")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-primary elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Pending Request</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 0 ")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-success elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Confirmed Request</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 1 ")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-danger elevation-1"><i class="fas fa-calendar-day"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Cancelled Request</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `appointment_list` where `status` = 2 ")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<hr>
<div style="width:550px; height: 570px; margin: 0 auto;  display: block; background-color: #4efb48; color:black;"
    class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header rounded-0">
        <h4 class="card-title" style="color: black; font-style:italic">Time Availability Management</h4>
    </div>
    <div class="card-body"style="background-color: #ffffff;">
    <h6 class="text-danger" style="font-size:13px;font-weight:normal"><i class="fas fa-info-circle"></i> Below are numbers of Time Schedules to manage.</h6>
        <div style="width:500px; height: 520px;" id="appointmentCalendar"></div>
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
            selectable: false,
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
             //   console.log(info.event.startStr)
                uni_modal("Set Time Validity", "manage_schedule.php?schedule=" + info.event.startStr, "mid-large" )
            },
            validRange: {
                start: moment(date).format("YYYY-MM-DD"),
            },
            eventDidMount: function (info) {
              
                if (!!appointment[info.event.startStr]) {
                   var available = parseInt(info.event.title) + parseInt(appointment[info.event.startStr]);
                   $(info.el).find('.fc-event-title.fc-sticky').text(available);
                }
              
               end_loader()
            },
            editable: false
        });

        calendar.render();
    })
</script>
<br>
<div style="background-color: #835555; color: wheat;" class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Appointments</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-hover table-striped table-bordered">
                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="20%">
                        <col width="25%">
                        <col width="20%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Code</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * from `appointment_list` order by unix_timestamp(`date_created`) desc ");
                        while ($row = $qry->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $i++; ?>
                                </td>
                                <td class="">
                                    <?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?>
                                </td>
                                <td>
                                    <?php echo ($row['code']) ?>
                                </td>
                                <td class="">
                                    <p class="truncate-1">
                                        <?php echo ucwords($row['owner_name']) ?>
                                    </p>
                                </td>
                                <td class="text-center">
                                    <?php
                                    switch ($row['status']) {
                                        case 0:
                                            echo '<span class="rounded-pill badge badge-primary">Pending</span>';
                                            break;
                                        case 1:
                                            echo '<span class="rounded-pill badge badge-success">Confirmed</span>';
                                            break;
                                        case 3:
                                            echo '<span class="rounded-pill badge badge-danger">Cancelled</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                                <td align="center">
                                    <button type="button"
                                        class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item"
                                            href="./?page=appointments/view_details&id=<?php echo $row['id'] ?>"
                                            data-id=""><span class="fa fa-window-restore text-gray"></span> View</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)"
                                            data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span>
                                            Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this appointment permanently?", "delete_appointment", [$(this).attr('data-id')])
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
    })
    function delete_appointment($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_appointment",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
<br>
<div style="background-color: rgb(159 179 193 / 64%); color: black;" class="card card-outline card-info rounded-0">
    <div class="card-header">
        <h3 class="card-title">List of Inquiries</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="20%">
                        <col width="30%">
                        <col width="15%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Inquirer</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT * from `message_list`  order by status asc, unix_timestamp(date_created) desc ");
                        while ($row = $qry->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <?php echo ucwords($row['fullname']) ?>
                                </td>
                                <td>
                                    <?php echo ($row['email']) ?>
                                </td>
                                <td class="truncate-1">
                                    <?php echo ($row['message']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 1): ?>
                                        <span class="badge badge-pill badge-success">Read</span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-primary">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td align="center">
                                    <button type="button"
                                        class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item view_details" href="javascript:void(0)"
                                            data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span>
                                            View</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)"
                                            data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span>
                                            Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this Inquiry permanently?", "delete_message", [$(this).attr('data-id')])
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.view_details').click(function () {
            uni_modal('Inquiry Details', "inquiries/view_details.php?id=" + $(this).attr('data-id'), 'mid-large')
        })
        $('.table').dataTable();
        $('#uni_modal').on('hide.bs.modal', function () {
            location.reload()
        })

        $('#submit').addClass('d-none');
    })
    function delete_message($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_message",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
    function verify_user($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Users.php?f=verify_inquiries",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occured.", 'error');
                    end_loader();
                }
            }
        })
    }
  
</script>
<br>
<a href="#top"><i class="fas fa-arrow-up"></i> Go to top</a>