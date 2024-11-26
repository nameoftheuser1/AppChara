<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationConfirmationToUser extends Mailable
{
    use Queueable, SerializesModels;

    public $transactionKey;
    public $pickUpDate;

    /**
     * Create a new message instance.
     *
     * @param string $transactionKey
     * @param string $pickUpDate
     */
    public function __construct($transactionKey, $pickUpDate)
    {
        $this->transactionKey = $transactionKey;
        $this->pickUpDate = $pickUpDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Confirmation To User',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation_confirmation_user',
            with: [
                'transactionKey' => $this->transactionKey,
                'pickUpDate' => $this->pickUpDate,
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
