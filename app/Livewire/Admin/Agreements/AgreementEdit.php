<?php

namespace App\Livewire\Admin\Agreements;

use App\Enums\AgreementPaymentType;
use App\Models\Agreement;
use App\Services\AgreementService;
use App\Support\Money;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class AgreementEdit extends Component
{
    use WithFileUploads;

    public Agreement $agreement;

    public string $title = '';

    public string $client_name = '';

    public string $client_email = '';

    public ?string $client_mobile = null;

    public string $client_mobile_dial = '+44';

    public string $client_dial_code = '+44';

    public string $content = '';

    public ?string $validity_date = null;

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

    public function mount(Agreement $agreement): void
    {
        $version = $agreement->currentVersion ?? $agreement->versions()->latest('id')->first();

        abort_unless($version, 404);

        $this->agreement = $agreement;
        $this->title = $version->title;
        $this->client_name = $version->client_name;
        $this->client_email = $version->client_email;
        $this->client_mobile = $version->client_mobile;

        if ($this->client_mobile && str_starts_with($this->client_mobile, '+')) {
            preg_match('/^\+\d{1,4}/', $this->client_mobile, $m);
            if ($m) {
                $this->client_mobile_dial = $m[0];
                $this->client_mobile = substr($this->client_mobile, strlen($m[0]));
            }
        }

        $this->content = $version->content;
        $this->validity_date = $version->validity_date?->format('Y-m-d');
        $this->payment_type = $agreement->payment_type->value;

        $config = $version->payment_config ?? [];

        if ($agreement->payment_type === AgreementPaymentType::Full) {
            $this->full_title = $config['title'] ?? $agreement->title;
            $this->full_amount = Money::fromPence($config['amount_pence'] ?? 0);
        }

        if ($agreement->payment_type === AgreementPaymentType::Milestone) {
            $this->milestones = collect($config['milestones'] ?? [])->map(fn($m) => [
                'title' => $m['title'],
                'description' => $m['description'] ?? '',
                'amount' => Money::fromPence($m['amount_pence'] ?? 0),
            ])->values()->all();

            if (empty($this->milestones)) {
                $this->addMilestone();
            }
        }

        if ($agreement->payment_type === AgreementPaymentType::Subscription) {
            $this->subscription_title = $config['title'] ?? $agreement->title;
            $this->subscription_amount = Money::fromPence($config['amount_pence'] ?? 0);
            $this->subscription_frequency = $config['frequency'] ?? 'monthly';
        }
    }

    public function addMilestone(): void
    {
        $this->milestones[] = ['title' => '', 'description' => '', 'amount' => ''];
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
            'validity_date' => ['nullable', 'date'],
        ];

        if ($this->payment_type === AgreementPaymentType::Full->value) {
            $rules['full_title'] = ['required', 'string', 'max:255'];
            $rules['full_amount'] = ['required', 'numeric', 'gt:0', 'max:99999999'];
        }

        if ($this->payment_type === AgreementPaymentType::Milestone->value) {
            $rules['milestones'] = ['required', 'array', 'min:1'];
            $rules['milestones.*.title'] = ['required', 'string', 'max:255'];
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

        $data = $this->buildData();

        $version = $service->createNewVersion($this->agreement, $data, auth('admin')->user());

        $this->dispatch('toast', message: "New version V{$version->version} created.", type: 'success');

        $redirectUrl = route('admin.agreements.show', $this->agreement);

        $this->redirect($redirectUrl);
    }

    private function buildData(): array
    {
        $data = [
            'title' => $this->title,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_mobile' => $this->client_mobile ? $this->client_dial_code . $this->client_mobile : null,
            'content' => $this->content,
            'validity_date' => $this->validity_date,
            'payment_type' => $this->payment_type,
        ];

        if ($this->payment_type === AgreementPaymentType::Full->value) {
            $data['full_title'] = $this->full_title;
            $data['full_amount_pence'] = Money::toPence($this->full_amount);
        }

        if ($this->payment_type === AgreementPaymentType::Milestone->value) {
            $data['milestones'] = collect($this->milestones)->map(fn($m) => [
                'title' => $m['title'],
                'description' => $m['description'] ?? null,
                'amount_pence' => Money::toPence($m['amount']),
            ])->all();
        }

        if ($this->payment_type === AgreementPaymentType::Subscription->value) {
            $data['subscription_title'] = $this->subscription_title;
            $data['subscription_amount_pence'] = Money::toPence($this->subscription_amount);
            $data['subscription_frequency'] = $this->subscription_frequency;
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.admin.agreements.agreement-edit', [
            'isSigned' => $this->agreement->hasSignature(),
        ])->title('Edit ' . $this->agreement->agreement_number);
    }
}
