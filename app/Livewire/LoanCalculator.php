<?php

namespace App\Livewire;

use Livewire\Component;

class LoanCalculator extends Component
{
    public float $propertyPrice = 5000000; // 50 Lakhs default
    public float $downPayment = 1000000;  // 10 Lakhs default
    public float $interestRate = 8.5;     // 8.5% default
    public int $loanTerm = 20;            // 20 years default

    public float $monthlyEmi = 0;
    public float $totalInterest = 0;
    public float $totalPayment = 0;

    public function mount(?float $price = null)
    {
        if ($price) {
            $this->propertyPrice = $price;
            $this->downPayment = $price * 0.20; // 20% down payment by default
        }
        $this->calculate();
    }

    public function calculate()
    {
        $loanAmount = $this->propertyPrice - $this->downPayment;
        if ($loanAmount <= 0) {
            $this->monthlyEmi = 0;
            $this->totalInterest = 0;
            $this->totalPayment = 0;
            return;
        }

        $monthlyRate = ($this->interestRate / 12) / 100;
        $totalMonths = $this->loanTerm * 12;

        if ($monthlyRate == 0) {
            $this->monthlyEmi = $loanAmount / $totalMonths;
        } else {
            $this->monthlyEmi = ($loanAmount * $monthlyRate * pow(1 + $monthlyRate, $totalMonths)) / 
                                (pow(1 + $monthlyRate, $totalMonths) - 1);
        }

        $this->totalPayment = $this->monthlyEmi * $totalMonths;
        $this->totalInterest = $this->totalPayment - $loanAmount;
    }

    public function updated()
    {
        $this->calculate();
    }

    public function render()
    {
        return view('livewire.loan-calculator');
    }
}
