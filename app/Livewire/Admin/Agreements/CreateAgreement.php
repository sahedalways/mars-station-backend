<?php

namespace App\Livewire\Admin\Agreements;

use App\Enums\AgreementPaymentType;
use App\Services\AgreementService;
use App\Support\Money;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class CreateAgreement extends Component
{
    use WithFileUploads;

    public string $title = '';

    public string $client_name = '';

    public string $client_email = '';

    public ?string $client_mobile = null;

    public string $client_dial_code = '+44';

    public string $content = '';

    public ?string $validity_date = null;

    #[Validate(['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,png,jpg,jpeg,heic,heif,webp'])]
    public $attachment = null;

    public string $payment_type = 'none';

    // Full payment
    public string $full_title = '';

    public string $full_amount = '';

    // Milestone payment
    public array $milestones = [];

    // Subscription payment
    public string $subscription_title = '';

    public string $subscription_amount = '';

    public string $subscription_frequency = 'monthly';

    public function mount(): void
    {
        $this->addMilestone();
    }

    public function addMilestone(): void
    {
        $this->milestones[] = [
            'title' => '',
            'description' => '',
            'amount' => '',
        ];
    }

    public function removeMilestone(int $index): void
    {
        unset($this->milestones[$index]);
        $this->milestones = array_values($this->milestones);
    }

    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_mobile' => ['nullable', 'string', 'max:30'],
            'content' => ['required', 'string'],
            'validity_date' => ['nullable', 'date', 'after_or_equal:today'],
            'payment_type' => ['required', 'in:'.implode(',', AgreementPaymentType::values())],
        ];

        if ($this->payment_type === AgreementPaymentType::Full->value) {
            $rules['full_title'] = ['required', 'string', 'max:255'];
            $rules['full_amount'] = ['required', 'numeric', 'gt:0', 'max:99999999'];
        }

        if ($this->payment_type === AgreementPaymentType::Milestone->value) {
            $rules['milestones'] = ['required', 'array', 'min:1'];
            $rules['milestones.*.title'] = ['required', 'string', 'max:255'];
            $rules['milestones.*.description'] = ['nullable', 'string'];
            $rules['milestones.*.amount'] = ['required', 'numeric', 'gt:0', 'max:99999999'];
        }

        if ($this->payment_type === AgreementPaymentType::Subscription->value) {
            $rules['subscription_title'] = ['required', 'string', 'max:255'];
            $rules['subscription_amount'] = ['required', 'numeric', 'gt:0', 'max:99999999'];
            $rules['subscription_frequency'] = ['required', 'in:monthly,yearly'];
        }

        return $rules;
    }

    public function save(AgreementService $service): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_mobile' => $this->client_mobile,
            'content' => $this->content,
            'validity_date' => $this->validity_date,
            'payment_type' => $this->payment_type,
        ];

        if ($this->payment_type === AgreementPaymentType::Full->value) {
            $data['full_title'] = $this->full_title;
            $data['full_amount_pence'] = Money::toPence($this->full_amount);
        }

        if ($this->payment_type === AgreementPaymentType::Milestone->value) {
            $data['milestones'] = collect($this->milestones)->map(function ($milestone) {
                return [
                    'title' => $milestone['title'],
                    'description' => $milestone['description'] ?? null,
                    'amount_pence' => Money::toPence($milestone['amount']),
                ];
            })->all();
        }

        if ($this->payment_type === AgreementPaymentType::Subscription->value) {
            $data['subscription_title'] = $this->subscription_title;
            $data['subscription_amount_pence'] = Money::toPence($this->subscription_amount);
            $data['subscription_frequency'] = $this->subscription_frequency;
        }

        $attachment = null;
        if ($this->attachment) {
            $path = $this->attachment->store('agreements/attachments', 'local');
            $attachment = [
                'path' => $path,
                'original_name' => $this->attachment->getClientOriginalName(),
                'mime_type' => $this->attachment->getMimeType(),
                'size_bytes' => $this->attachment->getSize(),
            ];
        }

        $agreement = $service->create($data, auth('admin')->user(), $attachment);

        $this->dispatch('toast', message: "Agreement {$agreement->agreement_number} created and sent to the client.", type: 'success');

        $redirectUrl = route('admin.agreements.show', $agreement);

        $this->redirect($redirectUrl, navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.agreements.create-agreement')
            ->title('Create Agreement');
    }
}
