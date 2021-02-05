<?php

namespace App\Repositories;

use App\Repositories\PaymentRepository;
use App\Models\Profile;

class ProfileRepository
{
    public function __construct(Profile $profile, PaymentRepository $paymentRepository)
    {
        $this->payment = $paymentRepository;
        $this->profile = $profile;
    }

    public function limitAvailableCalculation($dateCarbon){
        $limits = [];
        $limitUsed = $this->payment->valueOpenPayments($dateCarbon, $this->profile->payment_day);
        $limitAvailable = $this->profile->total_limit - $limitUsed;

        if (!empty($limitUsed) && !empty($limitAvailable)) {
            $limits =  ['limitAvailable' => $limitAvailable, 'limitUsed' => $limitUsed];
        }
        return $limits;
    }

    public function getProfile($dateCarbon){
        $limits = $this->limitAvailableCalculation($dateCarbon);

        return [
                'limits' => $limits, 'profile' => $this->profile
               ];
    }
}

