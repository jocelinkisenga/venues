<?php

namespace App\Livewire\Taxeprofile;

use App\Models\Lead;
use App\Support\ScreenerProfile;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.guest')]
class TaxScreener extends Component
{
    // #[Locked] : ces propriétés ne peuvent pas être modifiées depuis le navigateur
    #[Locked] public int $step = 1;
    #[Locked] public int $totalSteps = 4;
    #[Locked] public bool $completed = false;
    #[Locked] public array $recommendation = [];

    public string $income_source = '';
    public string $annual_income = '';
    public string $tracking_status = '';
    public string $first_name = '';
    public string $email = '';
    public string $tax_concern = '';

    protected function rulesFor(int $step): array
    {
        return match ($step) {
            1 => ['income_source' => ['required', Rule::in(array_keys(ScreenerProfile::INCOME_SOURCES))]],
            2 => ['annual_income' => ['required', Rule::in(array_keys(ScreenerProfile::INCOME_BRACKETS))]],
            3 => ['tracking_status' => ['required', Rule::in(array_keys(ScreenerProfile::TRACKING))]],
            4 => [
                'first_name'  => ['required', 'string', 'min:2', 'max:50'],
                'email'       => ['required', 'email:rfc', 'max:255'],
                'tax_concern' => ['nullable', 'string', 'max:500'],
            ],
            default => [],
        };
    }

    protected function messages(): array
    {
        return [
            'income_source.required'   => 'Please choose your main source of income.',
            'income_source.in'         => 'Please choose one of the options.',
            'annual_income.required'   => 'Please choose an income range.',
            'annual_income.in'         => 'Please choose one of the options.',
            'tracking_status.required' => 'Please choose an option.',
            'tracking_status.in'       => 'Please choose one of the options.',
            'first_name.required'      => 'Your first name is required.',
            'email.required'           => 'A valid email address is required.',
            'email.email'              => 'Please enter a valid email address.',
        ];
    }

    public function nextStep(): void
    {
        $this->validate($this->rulesFor($this->step));

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
            $this->resetErrorBag();
        }
    }

    public function submit(): void
    {
        // On revalide TOUTES les étapes : impossible de sauter une étape ou d'envoyer une valeur inventée
        $this->validate(array_merge(
            $this->rulesFor(1),
            $this->rulesFor(2),
            $this->rulesFor(3),
            $this->rulesFor(4),
        ));

        $this->recommendation = $this->recommend();

        // Même email = on met à jour le profil au lieu de bloquer la personne qui refait le screener
        $lead = Lead::updateOrCreate(
            ['email' => mb_strtolower(trim($this->email))],
            [
                'first_name'            => trim(strip_tags($this->first_name)),
                'primary_income_source' => $this->income_source,
                'income_bracket'        => $this->annual_income,
                'tracking_status'       => $this->tracking_status,
                'tax_concern'           => trim(strip_tags($this->tax_concern)),
                'recommended_tier'      => $this->recommendation['tier'],
            ]
        );

        // Le chat retrouve le profil grâce à cet identifiant de session
        session(['lead_id' => $lead->id]);

        $this->completed = true;
    }

    protected function recommend(): array
    {
        $selfEmployed = in_array($this->income_source, ['gig', 'freelance'], true);

        if ($this->income_source === 'expat' || $this->annual_income === '100k+') {
            $tier = 'premium';
            $message = 'Your situation has extra moving parts, so a one-on-one session with our Enrolled Agent is the best fit.';
        } elseif ($selfEmployed && $this->tracking_status !== 'yes') {
            $tier = 'plus';
            $message = 'Many gig and freelance workers miss deductions when expenses aren’t tracked. Plus gives you the lessons, templates and assistant to fix that.';
        } else {
            $tier = 'basic';
            $message = 'You’re off to a good start. Our lessons and templates cover what you need right now.';
        }

        [$name, $price] = ScreenerProfile::PLANS[$tier];

        return compact('tier', 'name', 'price', 'message');
    }

    public function render()
    {
        return view('livewire.taxeprofile.tax-screener');
    }
}
