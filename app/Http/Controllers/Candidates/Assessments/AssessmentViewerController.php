<?php

namespace App\Http\Controllers\Candidates\Assessments;

use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        $available_assessments = [];
        $open_ids = $aggy->getOpenJobPositions();
        if(count($open_ids) > 0)
        {
            foreach ($open_ids as $open_id)
            {
                $job_aggy = JobPositionAggregate::retrieve($open_id);
                $assessments = $job_aggy->getAssessments();
                $available_assessments[$open_id] = $assessments;
            }
        }

        dd($available_assessments);

        return view('cms-custom-pages.candidates.assessments.assessment-dashboard', $data);
    }

    public function show(string $assessment_id)
    {
        $data = [];
        return Inertia::render('Candidates/Assessments/AssessmentDashboard', $data);
    }
}
