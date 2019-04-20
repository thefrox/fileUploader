<?php
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
 
class NotificationMailController extends Controller
{
    public function send()
    {
        $objNotification = new \stdClass();
        $objNotification->notification_one = 'Notification One Value';
        $objNotification->notification_two = 'Notification Two Value';
        $objNotification->sender = 'SenderUserName';
        $objNotification->receiver = 'ReceiverUserName';
 
        Mail::to("receiver@example.com")->send(new NotificationMail($objNotification));
    }
}