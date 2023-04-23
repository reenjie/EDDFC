<?php
require_once('../config.php');
require_once '../vendor/autoload.php';
use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class Master extends DBConnection
{
	private $settings;
	public function __construct()
	{
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function capture_err()
	{
		if (!$this->conn->error)
			return false;
		else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_message()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if (!empty($data))
					$data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (empty($id)) {
			$sql = "INSERT INTO `message_list` set {$data} ";
		} else {
			$sql = "UPDATE `message_list` set {$data} where id = '{$id}' ";
		}

		$save = $this->conn->query($sql);
		if ($save) {
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if (empty($id))
				$resp['msg'] = "Your message has successfully sent.";
			else
				$resp['msg'] = "Message details has been updated successfully.";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		if ($resp['status'] == 'success' && !empty($id))
			$this->settings->set_flashdata('success', $resp['msg']);
		if ($resp['status'] == 'success' && empty($id))
			$this->settings->set_flashdata('pop_msg', $resp['msg']);
		return json_encode($resp);
	}
	function delete_message()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `message_list` where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Message has been deleted successfully.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_category()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if (!empty($data))
					$data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (empty($id)) {
			$sql = "INSERT INTO `category_list` set {$data} ";
		} else {
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' and delete_flag = 0 " . ($id > 0 ? " and id != '{$id}'" : ""));
		if ($check->num_rows > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Category name already exists.";
		} else {
			$save = $this->conn->query($sql);
			if ($save) {
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if (empty($id))
					$resp['msg'] = "Category has successfully added.";
				else
					$resp['msg'] = "Category details has been updated successfully.";
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
		}
		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function delete_category()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `category_list` set delete_flag=1 where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Category has been deleted successfully.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_service()
	{
		$_POST['category_ids'] = implode(',', $_POST['category_ids']);
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if (!empty($data))
					$data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if (empty($id)) {
			$sql = "INSERT INTO `service_list` set {$data} ";
		} else {
			$sql = "UPDATE `service_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `service_list` where `name` ='{$name}' and category_ids = '{$category_ids}' and delete_flag = 0 " . ($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Service already exists.";
		} else {
			$save = $this->conn->query($sql);
			if ($save) {
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if (empty($id))
					$resp['msg'] = "Service has successfully added.";
				else
					$resp['msg'] = "Service has been updated successfully.";
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
			if ($resp['status'] == 'success')
				$this->settings->set_flashdata('success', $resp['msg']);
		}
		return json_encode($resp);
	}
	function delete_service()
	{
		extract($_POST);
		$del = $this->conn->query("UPDATE `service_list` set delete_flag = 1 where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Service has been deleted successfully.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_appointment()
	{

		if (!$_POST['sched_id']) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Please Select Schedule";
		}

		$schedid = $_POST['sched_id'];
		$email   = $_POST['email'];
		$vcheck = $this->conn->query("SELECT * FROM `appointment_list` where sched_id = '{$schedid}' and email = '{$email}' ")->num_rows;
		if ($vcheck >= 1) {
			$resp['status'] = 'failed';
			$resp['msg'] = "The appointment schedule already exist.";
		}

		if (empty($_POST['id'])) {
			$prefix = "EEDFC-" . date("Ym");
			$code = sprintf("%'.04d", 1);
			while (true) {
				$check = $this->conn->query("SELECT * FROM `appointment_list` where code = '{$prefix}{$code}' ")->num_rows;
				if ($check <= 0) {
					$_POST['code'] = $prefix . $code;
					break;
				} else {
					$code = sprintf("%'.04d", ceil($code) + 1);
				}
			}
		}
		$_POST['service_ids'] = implode(",", $_POST['service_id']);
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
				if (!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if (!empty($data))
					$data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}



		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/'.time().'-'.$_FILES['img']['name'];
			$dir_path = base_app . $fname;
			$upload = $_FILES['img']['tmp_name'];
			$uploaded_img = move_uploaded_file($upload, $dir_path);
			if ($uploaded_img) {
				$resp['msg'] = "Image uploaded successfully.";
			} else {
				$resp['msg'] = "Image failed to upload due to unknown reason.";
			}
		}


		if (empty($id)) {
			$sql = "INSERT INTO `appointment_list` set {$data} ,`img` = '{$fname}' ";
		} else {
			$sql = "UPDATE `appointment_list` set {$data} ,`img` = '{$fname}' where id = '{$id}' ";
		}
		$slot_taken = $this->conn->query("SELECT * FROM `appointment_list` where date(schedule) = '{$schedule}' and `status` in (0,1)")->num_rows;
		if ($slot_taken >= $this->settings->info('max_appointment')) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Sorry, the Appointment Schedule is already full.";
		} else {
			$save = $this->conn->query($sql);
			if ($save) {
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['code'] = $code;
				$resp['status'] = 'success';
				if (empty($id))
					$resp['msg'] = "New Appointment Details has successfully added.</b>.";
				else
					$resp['msg'] = "Appointment Details has been updated successfully.";
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
		}

		if ($resp['status'] == 'success')
			$this->settings->set_flashdata('success', $resp['msg']);
		return json_encode($resp);
	}
	function delete_appointment()
	{
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `appointment_list` where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Appointment Details has been deleted successfully.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_appointment_status()
	{
		extract($_POST);
		$htmlcode = '';
		$subject = '';
		$doa = '';
		$time = '';
		$sched = $this->conn->query("SELECT * FROM `scheduler` where id ={$schedule} ");
		foreach($sched as $row){
			$doa = $row['schedule'];
			$time = date('h:i a',strtotime($row['timestart'])).' - '.date('h:i a',strtotime($row['timeend']));
		}	
		if ($status == 1 || $status == 2){
			if ($status == 1){
				$htmlcode = '
				<h4>
					Your appointment at EDDFC ( Early disease detection for chickens ) has been  <span style="color:green">Approved</span>.  
				</h4>

				<br/>

				Appointment Schedule :
				<br/>
				Date : '.date('F j,Y',strtotime($doa)).'
				<br/>

				Time : '.$time.'
                   
				';
				$subject = "APPOINTMENT APPROVED";
			}
			
			if($status == 2){
				$htmlcode = '
				<h4>
					Your appointment at EDDFC ( Early disease detection for chickens ) has been <span style="color:red">Cancelled</span>. 
				</h4>
				';
				$subject = "APPOINTMENT CANCELLED";
			}
$mail = new PHPMailer(true);
$mail->isSMTP(); //comment this
//$mail->Host =localhost;  // uncomment
$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
$mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
$mail->Port = 465; // TLS only
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
$mail->SMTPAuth = true;
$mail->AuthType = 'XOAUTH2';
$provider = new Google([
    'clientId'      => '633894388009-dq823nvhl97u0okjg7nascsdj451atdo.apps.googleusercontent.com',
    'clientSecret'  => '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'
]);
$mail->setOAuth(
    new OAuth(
        [
            'provider'          => $provider,
            'clientId'          => '633894388009-dq823nvhl97u0okjg7nascsdj451atdo.apps.googleusercontent.com',
            'clientSecret'      => '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',
            'refreshToken'      => '1//0e35DqS4PoQcQCgYIARAAGA4SNwF-L9IrNMkS7-eOy0BfmD7vJGfEokDDLgKRbJemH82uz6P9_k6EbfhBVvFi4YW0-KcB85_hKew',
            'userName'          => 'capstone0223@gmail.com',
        ]
    )
);


    $mail->setFrom('capstone0223@gmail.com','EDDFC');
    $mail->addAddress($email, $name);
    $mail->Subject = $subject;
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->msgHTML('
	<!DOCTYPE html>
           <html lang="en">
           
           <head>
               <meta charset="UTF-8">
               <meta name="viewport" content="width=device-width, initial-scale=1.0">
               <meta http-equiv="X-UA-Compatible" content="ie=edge">
               <title></title>
           </head>
           
           <body >
           
           
                   <h4>
                   Dear User,
                   <br/>
	
					'.$htmlcode.'
			
				   <br/>	<br/>
                   
                   EDDFC Team
                   
                   
                   
                   
                   
                   
                   
                   </h4>
           
           
                       
                      
                
                
                   <h5>
                    
                      EDDFC | All rights Reserved &middot; 2022
           
                   </h5>
                   <p><br><br><br></p>
           
           </body>
           
           </html>
	');

    $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
    // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    if (!$mail->send())
    {
        echo "Error" . $mail->ErrorInfo;
    }
    else
    {
        
        
    }


		}
		
		$del = $this->conn->query("UPDATE `appointment_list` set `status` = '{$status}' where id = '{$id}'");
		if ($del) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Appointment Request status has successfully updated.");
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_appointment':
		echo $Master->save_appointment();
		break;
	case 'delete_appointment':
		echo $Master->delete_appointment();
		break;
	case 'update_appointment_status':
		echo $Master->update_appointment_status();
		break;
	case 'save_message':
		echo $Master->save_message();
		break;
	case 'delete_message':
		echo $Master->delete_message();
		break;
	case 'save_category':
		echo $Master->save_category();
		break;
	case 'delete_category':
		echo $Master->delete_category();
		break;
	case 'save_service':
		echo $Master->save_service();
		break;
	case 'delete_service':
		echo $Master->delete_service();
		break;
	default:
		// echo $sysset->index();
		break;
}
