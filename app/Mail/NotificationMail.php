<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The notification object instance.
     *
     * @var Notification
     */
    public $notification;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@bluemega.fr')
                    ->view('mails.notification')
                    ->text('mails.notification_plain')
                    ->with(
                      [
                            'testVarOne' => '1',
                            'testVarTwo' => '2',
                      ])
                      ->attach(public_path('/images').'/notification.jpg', [
                              'as' => 'notification.jpg',
                              'mime' => 'image/jpeg',
                      ]);
    }
}