<?php

namespace App\Livewire\Taxeprofile;

use App\Services\TaxProfileService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Nnjeim\World\World;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;

#[Layout('layouts.client')]
class TaxProfile extends Component
{
   
    #[Validate('required|min:3')] 
    public string $filing_status = 'single';
    #[Validate('required|min:0')]
    public float $annual_income = 0;
    #[Validate('required|min:0')]
    public int $depends = 0;
    #[Validate('required|min:0')] 
    public float $business_income = 0;
    #[Validate('required|min:0')] 
    public float $other_income = 0;
    public bool $crypto_activity = false;

    public $countries = [];
    public $states = [];
    public $cities = [];

    public $selectedCountry = null;
    public $selectedState = null;
    public $selectedCity = null;

    public $country;
    public $state;



    public $step = 1;
    public $totalSteps = 8;

    public $form = [
        'income' => [],
        'business' => [],
        'expenses' => [],
        'assets' => [],
        'life_events' => [],
        'risk' => [],
        'goals' => [],
        'personal' => [],
    ];

    public $scores = [];


    
    

    public function mount()
    {

        $this->countries = World::countries()->data;
    }


        public function updatedSelectedCountry($value)
    {

        $this->states = [];
        $this->cities = [];
        $this->selectedState = null;
        $this->selectedCity = null;

        if ($value) {
            $this->states = World::states([
                'filters' => ['country_id' => $value]
            ])->data;

            }
            $action = World::countries([
                'filters' => [
                    'id' => $value]
                ])->data;

            if ($action) {
                $this->country = $action->first();
                }
    }

    public function updatedSelectedState($value)
    {
        $this->cities = [];
        $this->selectedCity = null;

        if ($value) {
            $this->cities = World::cities([
                'filters' => ['state_id' => $value]
            ])->data;

        }

            $action = World::states([
                'filters' => ['id'=> $value]
            ])->data;

             if ($action) {
                $this->state = $action->first();
                }
    }


    public function calculateScores()
    {
        $optimization = 0;
        $risk = 0;
        $complexity = 0;

        // Example scoring logic
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
            'risk' => min($risk, 100),
            'complexity' => min($complexity, 100),
        ];
    }

    public function determineTier()
    {
        if ($this->scores['complexity'] > 60) {
            return 'Elite';
        }

        if ($this->scores['optimization'] > 40) {
            return 'Growth';
        }

        return 'Foundation';
    }

    public function save(TaxProfileService $service)
    {
       
        $this->calculateScores();

        $tier = $this->determineTier();


        $fullData = [
            'user_id' => auth()->id(),
            'data' => $this->form,
            'scores' => $this->scores,
            'recommended_tier' => $tier,
        ];


        try {
            
            $service->register($fullData);

            session()->flash('message', 'profile saved successfuly.');
            
            return redirect()->to('/dashboard'); 

        } catch (\Exception $e) {
            
            $this->addError('save_error', 'an error occurade while saving');
        }

        
    }


    public function nextStep()
         {
            if ($this->step < $this->totalSteps) {
                $this->step++;
            }
        }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->resetErrorBag(); 
        }
    }

    public function render()
    { 

        return view('livewire.taxeprofile.tax-profile');
    }


}
