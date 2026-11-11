<?php

namespace App\Livewire\Taxeprofile;

use App\Models\TaxProfile;
use App\Services\TaxProfileService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.client')]
class EditTaxProfile extends Component
{
    #[Locked]
    public int $profileId;

    public array $form = [];
    public array $scores = [];
    public string $tier = '';

    /**
     * Sections of the form. Keys match the structure saved by the creation wizard
     * ($form in App\Livewire\TaxProfile), so existing profiles load without migration.
     *
     * number fields: [label, unit]  (unit: '$', '%', 'mi', 'sq ft' or null)
     * checkbox fields: label
     */
    protected function sections(): array
    {
        return [
            'income' => [
                'label' => 'Income',
                'type'  => 'number',
                'fields' => [
                    'w2_income'        => ['Total W2 income', '$'],
                    'freelance_income' => ['1099 / Freelance income', '$'],
                    'business_revenue' => ['Business revenue', '$'],
                    'business_profit'  => ['Business net profit', '$'],
                    'rental_income'    => ['Rental income', '$'],
                    'dividends'        => ['Dividend income', '$'],
                    'capital_gains'    => ['Capital gains', '$'],
                    'crypto_income'    => ['Crypto income', '$'],
                    'foreign_income'   => ['Foreign income', '$'],
                ],
            ],
            'business' => [
                'label' => 'Business',
                'type'  => 'checkbox',
                'fields' => [
                    'separate_bank'       => 'Separate business bank account',
                    'separate_credit'     => 'Separate business credit card',
                    'accounting_software' => 'Using accounting software',
                    'payroll_system'      => 'Payroll system active',
                    'contractors_paid'    => 'Contractors paid',
                    'inventory_business'  => 'Inventory-based business',
                    'business_vehicle'    => 'Business vehicle use',
                    'accountable_plan'    => 'Accountable plan in place',
                    'home_office_doc'     => 'Home office documentation',
                    'monthly_bookkeeping' => 'Monthly bookkeeping',
                ],
            ],
            'expenses' => [
                'label' => 'Expenses',
                'type'  => 'number',
                'fields' => [
                    'vehicle_mileage'       => ['Vehicle mileage', 'mi'],
                    'vehicle_costs'         => ['Fuel & repairs', '$'],
                    'home_office_sqft'      => ['Home office size', 'sq ft'],
                    'utilities'             => ['Utilities', '$'],
                    'phone'                 => ['Phone (business share)', '%'],
                    'travel'                => ['Travel', '$'],
                    'meals'                 => ['Meals', '$'],
                    'professional_services' => ['Professional services', '$'],
                    'software'              => ['Software subscriptions', '$'],
                    'equipment'             => ['Equipment', '$'],
                    'health_insurance'      => ['Health insurance', '$'],
                    'sep_ira'               => ['SEP IRA', '$'],
                    'solo401k_employee'     => ['Solo 401k (employee)', '$'],
                    'solo401k_employer'     => ['Solo 401k (employer)', '$'],
                ],
            ],
            'assets' => [
                'label' => 'Assets',
                'type'  => 'number',
                'fields' => [
                    'primary_home_value'  => ['Primary residence value', '$'],
                    'mortgage_balance'    => ['Mortgage balance', '$'],
                    'retirement_accounts' => ['Retirement account value', '$'],
                    'brokerage_accounts'  => ['Brokerage account value', '$'],
                    'business_value'      => ['Business valuation', '$'],
                    'business_loans'      => ['Outstanding business loans', '$'],
                ],
            ],
            'life_events' => [
                'label' => 'Life events',
                'type'  => 'checkbox',
                'fields' => [
                    'bought_home'      => 'Bought or sold a home',
                    'married_divorced' => 'Married or divorced',
                    'had_child'        => 'Had a child',
                    'started_business' => 'Started a business',
                    'sold_business'    => 'Sold a business',
                    'moved_state'      => 'Moved to another state',
                    'inherited_assets' => 'Inherited assets',
                    'large_medical'    => 'Large medical expenses',
                    'large_charity'    => 'Large charitable donations',
                ],
            ],
            'risk' => [
                'label' => 'Compliance',
                'type'  => 'checkbox',
                'fields' => [
                    'irs_notice'        => 'IRS notice received',
                    'prior_audit'       => 'Prior audit',
                    'late_filing'       => 'Late filings',
                    'foreign_account'   => 'Foreign bank accounts',
                    'crypto_unreported' => 'Unreported crypto',
                    'unfiled_years'     => 'Unfiled tax years',
                ],
            ],
            'goals' => [
                'label' => 'Goals',
                'type'  => 'checkbox',
                'fields' => [
                    'reduce_liability'    => 'Reduce tax liability',
                    'optimize_structure'  => 'Optimize entity structure',
                    'audit_defense'       => 'Prepare for an audit',
                    'retirement_strategy' => 'Retirement strategy',
                    'exit_planning'       => 'Exit planning',
                    'generational_wealth' => 'Generational wealth',
                ],
            ],
        ];
    }

    protected function personalKeys(): array
    {
        return ['full_name', 'dob', 'filing_status', 'state', 'dependents'];
    }

    protected function rules(): array
    {
        $rules = [];

        foreach ($this->sections() as $section => $def) {
            $rules["form.$section.*"] = $def['type'] === 'checkbox'
                ? ['boolean']
                : ['nullable', 'numeric', 'min:0', 'max:9999999999'];
        }

        return $rules + [
            'form.personal.full_name'     => ['required', 'string', 'min:3', 'max:120'],
            'form.personal.dob'           => ['nullable', 'date', 'before:today'],
            'form.personal.filing_status' => ['required', 'string', 'max:60'],
            'form.personal.state'         => ['nullable', 'string', 'max:80'],
            'form.personal.dependents'    => ['nullable', 'integer', 'min:0', 'max:30'],
        ];
    }

    protected function validationAttributes(): array
    {
        $attributes = [];

        foreach ($this->sections() as $section => $def) {
            foreach ($def['fields'] as $key => $field) {
                $attributes["form.$section.$key"] = strtolower(is_array($field) ? $field[0] : $field);
            }
        }

        return $attributes + [
            'form.personal.full_name'     => 'full name',
            'form.personal.dob'           => 'date of birth',
            'form.personal.filing_status' => 'filing status',
            'form.personal.state'         => 'state',
            'form.personal.dependents'    => 'number of dependents',
        ];
    }

    #[Computed]
    public function profile(): TaxProfile
    {
        // Scoped to the owner on every request: another user's id gives a 404.
        return TaxProfile::where('user_id', auth()->id())->findOrFail($this->profileId);
    }

    public function mount(TaxProfile $profile): void
    {
        abort_unless($profile->user_id === auth()->id(), 403);

        $this->profileId = $profile->id;
        $this->tier      = (string) ($profile->recommended_tier ?? '');
        $this->scores    = collect($profile->scores)->toArray();

        $saved = collect($profile->data)->toArray();
        $form  = $saved;

        foreach ($this->sections() as $section => $def) {
            foreach (array_keys($def['fields']) as $key) {
                $value = $saved[$section][$key] ?? null;
                $form[$section][$key] = $def['type'] === 'checkbox' ? (bool) $value : $value;
            }
        }

        foreach ($this->personalKeys() as $key) {
            $form['personal'][$key] = $saved['personal'][$key] ?? ($key === 'dependents' ? null : '');
        }

        $this->form = $form;
    }

    /**
     * Same scoring logic as the creation wizard, so both screens give the same result.
     */
    protected function calculateScores(): void
    {
        $optimization = 0;
        $risk         = 0;
        $complexity   = 0;

        if (!empty($this->form['income']['self_employed']) &&
            empty($this->form['expenses']['retirement_contribution'])) {
            $optimization += 15;
        }

        if (!empty($this->form['risk']['foreign_account'])) {
            $risk += 20;
        }

        if (!empty($this->form['income']['rental_income'])) {
            $complexity += 15;
        }

        $this->scores = [
            'optimization' => min($optimization, 100),
            'risk'         => min($risk, 100),
            'complexity'   => min($complexity, 100),
        ];
    }

    protected function determineTier(): string
    {
        if ($this->scores['complexity'] > 60) {
            return 'Elite';
        }

        if ($this->scores['optimization'] > 40) {
            return 'Growth';
        }

        return 'Foundation';
    }

    /** Empty strings become null, numeric strings become numbers, checkboxes become booleans. */
    protected function normalize(): void
    {
        foreach ($this->sections() as $section => $def) {
            foreach (array_keys($def['fields']) as $key) {
                $value = $this->form[$section][$key] ?? null;

                $this->form[$section][$key] = $def['type'] === 'checkbox'
                    ? (bool) $value
                    : (($value === '' || $value === null) ? null : $value + 0);
            }
        }

        $dependents = $this->form['personal']['dependents'] ?? null;
        $this->form['personal']['dependents'] = ($dependents === '' || $dependents === null) ? null : (int) $dependents;
        $this->form['personal']['full_name']  = trim((string) $this->form['personal']['full_name']);
    }

    public function save(TaxProfileService $service): void
    {
        $this->resetErrorBag('save_error');
        $this->validate();
        $this->normalize();
        $this->calculateScores();

        $tier = $this->determineTier();

        try {
            $updated = $service->update($this->profile, [
                'data'             => $this->form,
                'scores'           => $this->scores,
                'recommended_tier' => $tier,
            ]);

            if (! $updated) {
                $this->addError('save_error', 'Nothing was saved. Please try again.');
                return;
            }

            $this->tier = $tier;
            session()->flash('message', 'Your tax profile has been updated.');
        } catch (\Throwable $e) {
            report($e);
            $this->addError('save_error', 'We could not save your changes. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.taxeprofile.edit-tax-profile', [
            'sections' => $this->sections(),
        ]);
    }
}
