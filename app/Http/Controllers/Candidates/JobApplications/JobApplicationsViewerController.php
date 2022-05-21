<?php

namespace App\Http\Controllers\Candidates\JobApplications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobApplicationsViewerController extends Controller
{
    public function show(string $user_id)
    {
        return backpack_view('errors.500');
    }
}
