<?php
require_once('../config.php');

if (isset($_GET['addtime'])) {

  $Selected = $_GET['addtime'];

  $data = $conn->query("INSERT INTO `scheduler`( `timestart`, `timeend`, `schedule`, `slots`) VALUES (null,null,'{$Selected}',20)");
}

if (isset($_GET['fetchdata'])) {
  $selected = $_GET['fetchdata'];


  $data = $conn->query("SELECT * FROM `scheduler` where schedule = '{$selected}' ");


  foreach ($data as $key => $row) {
?>
    <tr>
      <th scope="row"><?php echo $key + 1 ?></th>
      <td><input type="time" class="form-control updateOnchange" data-id="<?php echo $row['id'] ?>" data-entity="timestart" id="ts<?php echo $row['id'] ?>" value="<?php echo $row['timestart'] ?>" />
        <div class="invalid-feedback">
          Reasons :
          <br>
          <ul>
            <li>No time selected </li>
            <li>Within the time range of other schedules </li>

          </ul>
        </div>
      </td>
      <td><input type="time" class="form-control updateOnchange" data-id="<?php echo $row['id'] ?>" data-entity="timeend" id="te<?php echo $row['id'] ?>" value="<?php echo $row['timeend'] ?>" />
        <div class="invalid-feedback">
          Reasons :
          <br>
          <ul>
            <li>Greater than the time start</li>
            <li>Within the time range of other schedules </li>
            <li>Equals to time Start</li>

          </ul>
        </div>
      </td>
      <td><input type="number" class="form-control updateOnchange" data-id="<?php echo $row['id'] ?>" data-entity="slots" data-slots="1" value="<?php echo $row['slots'] ?>" /></td>
      <td>
        <?php
        $occ =  $conn->query("SELECT * FROM `appointment_list` where sched_id = '{$row['id']}' and status = 1 ")->num_rows;
        echo $occ;
        ?>

      </td>
      <td>
        <?php
        $vacant = $row['slots'] - $occ;
        echo  $vacant ?>

      </td>
      <td>
        <?php
        if ($vacant >= 1) {
          echo '<span class="badge bg-success">Vacant</span>';
        } else {
          echo '<span class="badge bg-danger">Full</span>';
        }

        ?>
      </td>
      <td><button class="btn btn-light text-danger btn-sm btndelete" <?php
                                                                      if ($occ >= 1) {
                                                                        echo "disabled";
                                                                      } else {
                                                                      }

                                                                      ?> data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-can"></i></button></td>
    </tr>
  <?php
  }

  ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    function fetchdata(date) {
      start_loader();
      $.ajax({
        url: '../classes/Adds_on.php',
        method: 'GET',
        data: {
          fetchdata: date
        },
        success: function(response) {
          end_loader()
          $('#tabledata').html(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Handle the error
          console.error(textStatus + ': ' + errorThrown);
        }
      });
    }
    //
    $('.updateOnchange').change(function() {
      var id = $(this).data('id');
      var entity = $(this).data('entity');
      var val = $(this).val();
      var slots = $(this).data('slots');

      if (entity == 'timestart') {
        $('#te' + id).val('');
      }


      $.ajax({
        url: '../classes/Adds_on.php',
        method: 'GET',
        data: {
          update: 1,
          id: id,
          entity: entity,
          value: val
        },
        success: function(response) {
          console.log(response);
          if (slots) {
            fetchdata("<?php echo $selected ?>")
          }

          if (response == 'failedte1') {
            $('#te' + id).addClass('is-invalid');
          }

          if (response == 'failedte2') {
            $('#te' + id).addClass('is-invalid');
          }

          if (response == 'failedts') {
            $('#ts' + id).addClass('is-invalid');
            $('#te' + id).val('');
          }


          if (response == 'success') {
            fetchdata("<?php echo $selected ?>")
            $('#te' + id).removeClass('is-invalid');
            $('#ts' + id).removeClass('is-invalid');
          }

        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Handle the error
          console.error(textStatus + ': ' + errorThrown);
        }
      });
    });

    $('.btndelete').click(function() {
      var id = $(this).data('id');

      swal({
          title: "Are you sure?",
          text: "Once deleted, you wont be able to recover it.",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {

            $.ajax({
              url: '../classes/Adds_on.php',
              method: 'GET',
              data: {
                delete: 1,
                id: id
              },
              success: function(response) {

                fetchdata("<?php echo $selected ?>")

              },
              error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error
                console.error(textStatus + ': ' + errorThrown);
              }
            });

          }
        });


    })
  </script>
<?php

}

if (isset($_GET['update'])) {
  $id = $_GET['id'];
  $entity = $_GET['entity'];
  $value = $_GET['value'];
  $datenow = date('Y-m-d');
  $current = $conn->query("SELECT * FROM `scheduler` WHERE id = '{$id}' ");
  foreach ($current as $row) {
    $timestart = $row['timestart'];
    $sched = $row['schedule'];
  }
  if ($entity == 'timestart') {
    $conn->query("UPDATE `scheduler` SET `timeend`= null WHERE id = '{$id}' ");
    $validate = $conn->query("SELECT * FROM `scheduler` WHERE  schedule = '{$sched}' and '{$value}' BETWEEN timestart and timeend ");
    if ($validate->num_rows >= 1) {
      echo "failedts";
      return false;
    }

    echo "success";
    return $conn->query("UPDATE `scheduler` SET `{$entity}`= '{$value}' WHERE id = '{$id}' ");
  }

  if ($entity == 'timeend') {


    $time1 = strtotime($timestart);
    $time2 = strtotime($value);
    if ($time1 == null) {
      echo "failedts";
      return false;
    }

    if ($time1 < $time2) {
      $validate = $conn->query("
        SELECT *
        FROM `scheduler`
        WHERE id != {$id}
        AND schedule = '{$sched}'
        AND (timestart BETWEEN time('{$timestart}') AND time('{$value}')
             OR timeend BETWEEN time('{$timestart}') AND time('{$value}'))
    ");
      if ($validate->num_rows >= 1) {
        echo "failedte1";
        return false;
      }
      echo "success";
      return $conn->query("UPDATE `scheduler` SET `{$entity}`= '{$value}' WHERE id = '{$id}' ");
    } else {
      echo "failedte2";
      return false;
    }
  }


  echo "success";
  return $conn->query("UPDATE `scheduler` SET `{$entity}`= '{$value}' WHERE id = '{$id}' ");
  //return add 


}

if (isset($_GET['delete'])) {
  $id = $_GET['id'];


  $conn->query("DELETE FROM `scheduler` WHERE id = '{$id}' ");
}
