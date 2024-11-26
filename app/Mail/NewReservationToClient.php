<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReservationToClient extends Mailable
{
    use Queueable, SerializesModels;

    public $transactionKey;
    public $name;
    public $pickUpDate;
    public $contactNumber;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $transactionKey
     * @param string $name
     * @param string $pickUpDate
     * @param string $contactNumber
     * @param string $email
     */
    public function __construct($transactionKey, $name, $pickUpDate, $contactNumber, $email)
    {
        $this->transactionKey = $transactionKey;
        $this->name = $name;
        $this->pickUpDate = $pickUpDate;
        $this->contactNumber = $contactNumber;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Reservation Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_reservation_to_client', // Ensure this view exists
            with: [
                'transactionKey' => $this->transactionKey,
                'name' => $this->name,
                'pickUpDate' => $this->pickUpDate,
                'contactNumber' => $this->contactNumber,
                'email' => $this->email,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
