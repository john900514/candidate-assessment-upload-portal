<?php

namespace App\Http\Controllers\Candidates\JobApplications;

use App\Actions\Users\Dashboards\JobApplicationDashboard;
use App\Aggregates\Candidates\JobPositionAggregate;
use App\Aggregates\Users\UserAggregate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobApplicationsViewerController extends Controller
{
    public function show(string $candidate_user_id)
    {
        if(backpack_user()->can('view_candidates'))
        {
            // @todo - check that the $candidate is a candidate
            // @todo - check that the $candidate has applied for this job
            if(request()->has('job'))
            {
                $job_id = request()->get('job');
                $job_aggy = JobPositionAggregate::retrieve($job_id);
                $user_aggy = UserAggregate::retrieve($candidate_user_id);
                $candidate_name = $user_aggy->getFirstName()." ".$user_aggy->getLastName();
                $data = [
                    'breadcrumbs' =>  [
                        'Dashboard' => url(config('backpack.base.route_prefix'), 'dashboard'),
                        'Candidates' => false,
                        'Job Applications' => false,
                        $candidate_name => false,
                    ],
                    'candidate_name' => $candidate_name,
                    'job_title' => $job_aggy->getJobTitle()
                ];

                $data['widgets']['before_content'] = [JobApplicationDashboard::run($job_aggy, $user_aggy)];


                return view('cms-custom-pages.candidates.job-applications.application-viewer-dashboard', $data);
            }
        }
        else
        {
            return view('errors.401');
        }


        return view('errors.500');
    }
}
