<?php

namespace App\Http\Controllers\Candidates\Assessments;

use App\Aggregates\Candidates\Assessments\AssessmentAggregate;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Enums\JobTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Assets\SourceCodeUpload;
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



        return view('cms-custom-pages.candidates.assessments.assessment-dashboard', $data);
    }

    public function show(string $assessment_id)
    {
        $data = [
            'assessment' => [],
            'userData' => []
        ];

        $user_aggy = UserAggregate::retrieve(backpack_user()->id);
        $assy_aggy = AssessmentAggregate::retrieve($assessment_id);
        $data['assessment']['id'] = $assessment_id;
        $data['assessment']['name'] = $assy_aggy->getName();
        $data['assessment']['concentration'] = JobTypeEnum::tryFrom($assy_aggy->getConcentration())->name;
        $data['assessment']['tasks'] = [
            'total' => count($assy_aggy->getTasks()),
            'list' => $assy_aggy->getTasks()
        ];
        $data['assessment']['quizzes'] = [
            'total' => count($assy_aggy->getQuizzes()),
            'list' => $assy_aggy->getDetailedQuizzes()
        ];
        $data['assessment']['source'] = [
            'has_source' => $assy_aggy->hasCodeWork(),
            'source' => $assy_aggy->hasCodeWork() ? SourceCodeUpload::find($assy_aggy->getCodeWorkId())->toArray() ?? [] : []
        ];

        $open_positions = $user_aggy->getOpenJobPositions();
        foreach($open_positions as $idx => $job_id)
        {
            if($assessment_status = $user_aggy->getAssessmentStatus($job_id, $assessment_id))
            {
                break;
            }
        }

        if($assessment_status ?? false)
        {
            $data['userData'] = $assessment_status;

            // check if the installer was downloaded and we'll assume it was installed
            $data['userData']['sourceInstalled'] = $user_aggy->hasDownloadedInstaller();
        }
        else
        {
            $data['userData'] = [
                "status" => "Not Started",
                "badge" => "badge-danger",
                "quizzesReqd" => 0,
                "tasksReqd" => 0,
                "sourceReqd" => true,
                "quizzesCompleted" => false,
                "tasksCompleted" => false,
                "sourceUploaded" => false,
            ];
        }

//dd($data);

        return Inertia::render('Candidates/Assessments/AssessmentDashboard', $data);
    }
}
