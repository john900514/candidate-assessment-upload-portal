<?php

namespace App\Http\Controllers\Candidates\Assessments;

use App\Aggregates\Users\UserAggregate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssessmentViewerController extends Controller
{
    public function index()
    {
        $data = [];
        $aggy = UserAggregate::retrieve(backpack_user()->id);

        if($aggy->isEmployee())
        {
            \Alert::add('warning', 'This area is for Candidates. If you are a developer. Use your local to log in as an applicant.')->flash();
            return redirect()->back();
        }

        return view('cms-custom-pages.candidates.assessments.assessment-dashboard', $data);
    }
}
