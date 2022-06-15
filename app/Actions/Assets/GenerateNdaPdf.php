<?php

namespace App\Actions\Assets;

use Barryvdh\DomPDF\Facade\Pdf;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateNdaPdf
{
    use AsAction;

    public function handle(array $details)
    {
        return PDF::loadView('documents.candidate-nda', $details);
    }
}
