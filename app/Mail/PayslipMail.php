<?php

namespace App\Mail;

use App\Models\PayrollDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipMail extends Mailable
{
    use Queueable, SerializesModels;

    public PayrollDetail $payrollDetail;

    /**
     * Create a new message instance.
     */
    public function __construct(PayrollDetail $payrollDetail)
    {
        $this->payrollDetail = $payrollDetail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Slip Gaji Periode ' . \Carbon\Carbon::parse($this->payrollDetail->payroll->tanggal_mulai)->translatedFormat('d M Y') . ' - ' . \Carbon\Carbon::parse($this->payrollDetail->payroll->tanggal_selesai)->translatedFormat('d M Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payslip_body',
            with: [
                'detail' => $this->payrollDetail,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.payslip', ['detail' => $this->payrollDetail]);
        
        $filename = 'Slip_Gaji_' . str_replace(' ', '_', $this->payrollDetail->employee->nama) . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
