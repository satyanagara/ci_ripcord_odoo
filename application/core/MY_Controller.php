<?php

/**
 * Created by :
 *
 * User: AndrewMalachel
 * Date: 2/21/14
 * Time: 3:25 PM
 * Proj: RentalFleets
 */
class MY_Controller extends CI_Controller
{

    var $username;
    var $userrole;
    var $email;
    var $is_login = FALSE;
    var $is_admin = FALSE;
    var $is_supplier = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('auth_model');
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set('Asia/Jakarta');
//                if($this->uri->ruri_string()=='/auth/login'):
//                    $this->check_login(); 
//                elseif($this->uri->ruri_string()=='/auth/login_admin'):
//                    $this->check_admin_login();  
//                else:
//                    $this->session->sess_destroy();  
//                endif;
//                
//		
// 		  echo $this->uri->ruri_string();  die();
    }

    //by arifins.nurul@gmail.com
    public function sendNotification($registrationIDs, $message)
    {
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => $message,
        );
        // Ganti Google API disini
        define("GOOGLE_API_KEY", "");
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function do_output($view, $data = array())
    {
        $this->load->view('general/header');
        $this->load->view($view, $data);
        $this->load->view('general/footer');
    }

    public function check_login()
    {
        $data = $this->session->all_userdata();
        if ($data['is_user_login']):
            $this->is_login = TRUE;
        else:
            $this->is_login = FALSE;
        endif;
    }

    public function check_admin_login()
    {
        $data = $this->session->all_userdata();
        if ($data['is_admin']):
            $this->is_admin = TRUE;
        else:
            $this->is_admin = FALSE;
        endif;
    }

}

class Member_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set('Asia/Jakarta');
        $data = $this->session->all_userdata();
        if (isset($data['is_user_login'])):
            $this->is_login = TRUE;
        else:
            $this->is_login = FALSE;
        endif;
        if ($this->is_login < 1) {
            // show_error('You are forbidden here!', 401);
            redirect('', 'refresh');
        }
    }

}

class Admin_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set('Asia/Jakarta');
        $data = $this->session->all_userdata();
        if (isset($data['is_admin'])):
            $this->is_admin = TRUE;
        else:
            $this->is_admin = FALSE;
        endif;
        if ($this->is_admin < 1) {
            // show_error('You are forbidden here!', 401);
            redirect('admin', 'refresh');
        }
    }

}

class Supplier_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $data = $this->session->all_userdata();
        if (isset($data['is_supplier'])):
            $this->is_admin = TRUE;
        else:
            $this->is_admin = FALSE;
        endif;
        if ($this->is_admin < 1) {
            // show_error('You are forbidden here!', 401);
            redirect('suppliers', 'refresh');
        }
    }

}

class Vendor_Api_Access_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('access_token_model');
    }

    public function check_access()
    {
        // 0 allow ,1 bad request, 2 invalid access token
        $access_token = $this->input->get('access_token');

        if (empty($access_token)) {
            return array(
                "status" => 1,
                "data" => null,
            );

        } else {
            $checkAuth = $this->access_token_model->get_valid_token_vendor($access_token);

            if (empty($checkAuth)) {
                return array(
                    "status" => 0,
                    "data" => null,
                );
            }

            return array(
                "status" => 2,
                "data" => $checkAuth->vendor_id,
            );
        }
    }

    public function nexmo_sms_send($to, $message)
    {
        // **********************************Text Message*************************************
        $from = 'KlikTukang';
        $to = $to;
        $message = array(
            'text' => $message,
        );
        $response = $this->nexmo->send_message($from, $to, $message);
    }

    public function loadNotifyVerify($data)
    {
        $stringHtml = '
<!doctype html>
<html xml:lang="en-gb" lang="en-gb" >
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
  <div style="max-width:600px;display:block;border-collapse:collapse;margin:0 auto;padding:15px;border-color:#e7e7e7;border-style:solid;border-width:1px 1px 0">
<table bgcolor="#fff" style="font-family:Helvetica, NeueHelvetica,Helvetica,Arial,sans-serif;max-width:100%;border-collapse:collapse;border-spacing:0;width:100%;
background-color:transparent;margin:0;padding:0">
<tbody><tr style="margin:0;padding:0">
<td style="margin:0;padding:0">
</td></tr></tbody>
<div class="logo" style="text-align: center;">
                  <img src="http://webdev.kliktukang.com/assets_old/img/logo.png"/>
</div>
</table></div>
<div style="max-width:600px;display:block;border-collapse:collapse;margin:0 auto;padding:30px 15px;border:1px solid #e7e7e7">
<table bgcolor="transparent" style="font-family:Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif;max-width:100%;border-collapse:collapse;border-spacing:0;width:100%;background-color:transparent;margin:0;padding:0"><tbody><tr style="margin:0;padding:0"><td style="margin:0;padding:0"><h4 style="font-family:HelveticaNeue-Light,Helvetica Neue Light,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;line-height:1.1;color:#000;font-weight:500;font-size:23px;margin:0 0 20px;padding:0">
Mengaktifkan Akun Anda</h4><p style="font-weight:normal;font-size:14px;line-height:1.6;margin:0 0 20px;padding:0">' . ucfirst($data->vendorname) . '</p>
<p style="font-weight:normal;font-size:14px;line-height:1.6;margin:0 0 20px;padding:0">
Terima kasih sudah mendaftar untuk bergabung dengan <span>KlikTukang&trade;</span>, Untuk menyelesaikan proses pendaftaran, mohon lakukan konfirmasi pendaftaran melalui tombol di bawah ini:</p>
<div align="center" style="text-align:center;margin:20px;padding:0">
<a href="' . base_url() . 'api/3.0/vendor/profile/verificationEmail/' . ucfirst($data->id) . '" style="color:#fff; font-family:Helvetica, sans-serif;font-size: 13px;
                background-color:  #fe4c02;padding: 10px;text-decoration: none;">Verify Email</a>
</div></p>
<p style="font-weight:normal;font-size:14px;line-height:1.6;border-top-style:solid;border-top-color:#d0d0d0;border-top-width:3px;margin:40px 0 0;padding:10px 0 0"><small style="color:#999;margin:0;padding:0">Email ini dibuat secara otomatis. Mohon tidak mengirimkan balasan ke email ini.</small></p></td></tr></tbody></table></div>
<div style="max-width:600px;display:block;border-collapse:collapse;margin:0 auto;padding:20px 15px;border-color:#e7e7e7;border-style:solid;border-width:0 1px 1px"><div style="padding:15px 12px;border-width:2px;border-style:dashed;border-color:#f5dadc;background-color:#fbab75"><p style="font-size:12px;line-height:18px;font-weight:400;padding:0px;margin:0px">
Hati-hati terhadap pihak yang mengaku dari KlikTukang&trade;, membagikan voucher Disount, atau meminta data pribadi. KlikTukang&trade; tidak pernah meminta password dan data pribadi melalui email, pesan pribadi, maupun channel lainnya. Untuk semua email dengan link dari KlikTukang&trade;, pastikan alamat URL di browser sudah di alamat <a target="_blank" style="color:#fe4c02" href="http://kliktukan.com">KlikTukang.com</a> bukan alamat lainnya.</p></div></div>
<div style="max-width:600px;display:block;border-collapse:collapse;background-color:#f7f7f7;margin:0 auto;padding:20px 15px;border-color:#e7e7e7;border-style:solid;border-width:0 1px 1px"><table width="100%" bgcolor="transparent" style="max-width:100%;border-collapse:collapse;border-spacing:0;width:100%;background-color:transparent;margin:0;padding:0"><tbody style="margin:0;padding:0"><tr style="margin:0;padding:0"><td valign="top" style="margin:0;padding:0 10px 0 0;width:75%"><span style="font-size:12px;margin-bottom:6px;display:inline-block">Download Aplikasi KlikTukang&trade;</span>
<div style="text-align:left">
<a target="_blank" href="" style="color:#008000"></a>
<img alt="Download Android App" style="border:0;min-height:auto;max-width:120px;outline:0" src="g-ios-app.png" class="CToWUd">
</a></div></td><td valign="top" style="margin:0;padding:0"><span style="font-size:12px;margin-bottom:6px;display:inline-block">Ikuti Kami</span><div style="text-align:left">
<a target="_blank" style="color:#008000;display:inline-block" href="https://www.facebook.com/KlikTukang-854474941304186/"><img style="border:0;min-height:auto;max-width:100%;outline:0" alt="Facebook" src="' . base_url() . 'assets_old/img/facebook.png" class="CToWUd"></a>
<a target="_blank" style="color:#008000;display:inline-block" href="https://twitter.com/kliktukang"><img style="border:0;min-height:auto;max-width:100%;outline:0" alt="Twitter" src="' . base_url() . 'assets_old/img/twitter.png" class="CToWUd"></a>
<a target="_blank" style="color:#008000;display:inline-block" href="https://www.instagram.com/kliktukang/"><img style="border:0;min-height:auto;max-width:100%;outline:0" alt="Instagram" src="' . base_url() . 'assets_old/img/instagram.png" class="CToWUd"></a></div></td></tr></tbody></table></div>
</td></p>
</td>
</div></td></tr></tbody></table></div>
</body>
</html>';
        return $stringHtml;
    }

}

// END OF MY_Controller.php File
