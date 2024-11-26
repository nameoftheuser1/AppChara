<?php

// app/Notifications/ReservationStatusUpdated.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationStatusUpdated extends Notification
{
    use Queueable;

    protected $reservation;

    // Constructor accepts the reservation instance
    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    // The via method determines how the notification will be sent
    public function via($notifiable)
    {
        return ['mail']; // Send via email
    }

    // The toMail method builds the email content
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Reservation Status has been Updated')
            ->line("Dear {$this->reservation->name},")
            ->line("Your reservation status has been updated to: {$this->reservation->status}.")
            ->line('Thank you for choosing our service!')
            ->action('View Reservation', url('/check-status'));
    }

    // You can also define the toArray method if you're using database notifications
    public function toArray($notifiable)
    {
        return [
            'reservation_id' => $this->reservation->id,
            'status' => $this->reservation->status,
        ];
    }
}
