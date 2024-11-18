<?php
defined('ROOTPATH') or exit('Access denied');

class Products{
    use controller;
    public function index(){
        $user = new User();
        $user->setLimit(10);
        $user->setOffset(0);
        $userlist = $user->findAll();
        show($_SESSION);
        show("<img src=".  get_img($_SESSION['user']->image_url) . ' alt="Profile" class="header-profile-picture">');
        echo get_img($_SESSION['user']->image_url);
        $ggg = $user->getTotalCountWhere([],[],"wen");
        $count = $user->where([],[],"wen");
        show($ggg);
        show($count);	
        // sendMail('wvedmund@gmail.com','hello', 'this is a test');
        // $status = sendMail('wvedmund@gmail.com','subject:hello', "
        //                 <div style='font-family: Arial, sans-serif; color: #333; padding: 20px;'>
        //                     <h1 style='color: #4CAF50;'>Password Reset Request</h1>
        //                     <p>Hello,</p>
        //                     <p>We received a request to reset your password. Please use the following code to reset your password:</p>
        //                     <h3 style='color: #4CAF50;'>sdcbjbs</h3>
        //                     <p>If you did not request this, please ignore this email.</p>
        //                     <br>
        //                     <p>Best regards,<br>PrimeCare Support Team</p>
        //                 </div>
        //             ");		
        // echo "this is the products controller";
        // show($status);
    }
}

