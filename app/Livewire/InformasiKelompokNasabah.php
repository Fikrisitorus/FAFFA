<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JenisSampah;

class InformasiKelompokNasabah extends Component
{
    public $activeStep = 1;
    public $maxSteps = 5;
    public $showFaq = false;
    public $selectedWasteType = null;
    public $estimatorWeight = 0;
    public $estimatedPrice = 0;
    public $activeJenisSampah = null;
    
    // FAQ Data
    public $faqItems = [
        [
            'question' => 'Berapa minimal berat sampah yang bisa dijemput?',
            'answer' => 'Minimal 5kg untuk sekali penjemputan agar efisien bagi pengepul.',
            'isOpen' => false
        ],
        [
            'question' => 'Apakah sampah harus dicuci sebelum disetor?',
            'answer' => 'Ya, sampah harus bersih dan kering untuk menjaga kualitas dan harga jual yang optimal.',
            'isOpen' => false
        ],
        [
            'question' => 'Bagaimana cara mengetahui harga sampah terkini?',
            'answer' => 'Harga sampah akan otomatis terkalkulasi saat pengepul melakukan verifikasi di tempat.',
            'isOpen' => false
        ],
        [
            'question' => 'Apakah bisa request jadwal penjemputan khusus?',
            'answer' => 'Ya, sistem kami fleksibel. Anda bisa request jadwal sesuai kebutuhan melalui dashboard.',
            'isOpen' => false
        ],
    ];

    public function mount()
    {
        // Initialize with first step active
        $this->activeStep = 1;
    }

    public function setActiveStep($step)
    {
        $this->activeStep = $step;
        $this->dispatch('step-changed', ['step' => $step]);
    }

    public function toggleFaq($index)
    {
        $this->faqItems[$index]['isOpen'] = !$this->faqItems[$index]['isOpen'];
    }

    public function selectWasteType($type)
    {
        $this->selectedWasteType = $type;
        $this->activeJenisSampah = JenisSampah::where('nama', 'like', '%' . $type . '%')->first();
        $this->calculateEstimate();
    }

    public function updatedEstimatorWeight()
    {
        $this->calculateEstimate();
    }

    public function calculateEstimate()
    {
        if ($this->activeJenisSampah && $this->estimatorWeight > 0) {
            $harga = (float)($this->activeJenisSampah->harga ?? 0);
            $this->estimatedPrice = $this->estimatorWeight * $harga;
        } else {
            $this->estimatedPrice = 0;
        }
    }

    public function resetEstimator()
    {
        $this->selectedWasteType = null;
        $this->estimatorWeight = 0;
        $this->estimatedPrice = 0;
        $this->activeJenisSampah = null;
    }

    public function render()
    {
        $jenisSampah = JenisSampah::all();
        
        return view('livewire.informasi-kelompok-nasabah', [
            'jenisSampah' => $jenisSampah
        ]);
    }
}