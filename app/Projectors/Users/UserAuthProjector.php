<?php

namespace App\Projectors\Users;

use App\Models\Assets\UploadedFile;
use App\Models\Assets\UserFileUpload;
use App\Models\User;
use App\Models\UserDetails;
use App\StorableEvents\Users\Activity\Account\PasswordUpdated;
use App\StorableEvents\Users\Activity\Account\UserVerified;
use App\StorableEvents\Users\ApplicantCreated;
use App\StorableEvents\Users\Applicants\ApplicantLinkedToJobPosition;
use App\StorableEvents\Users\Applicants\ApplicantRoleChanged;
use App\StorableEvents\Users\Applicants\ApplicantUploadedResume;
use App\StorableEvents\Users\UserCreated;
use App\StorableEvents\Users\UserUpdated;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\StorableEvents\Users\Activity\API\AccessTokenGranted;

class UserAuthProjector extends Projector
{
    public function onUserCreated(UserCreated $event)
    {
        $user = User::create($event->details);
        $user->id = $event->user_id;
        $user->save();

        $employee = UserDetails::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'employee_status',
            'active' => 1
        ]);

        $employee->value  = 'employee';
        $employee->save();

        Bouncer::assign($event->role)->to($user);
    }
    public function onApplicantCreated(ApplicantCreated $event)
    {
        $user = User::create($event->details);
        $user->id = $event->user_id;
        $user->save();

        $employee = UserDetails::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'employee_status',
            'active' => 1
        ]);

        $employee->value  = 'non-employee';
        $employee->save();

        Bouncer::assign($event->role)->to($user);
    }

    public function onApplicantRoleChanged(ApplicantRoleChanged $event)
    {
        $user = User::find($event->user_id);
        $current_role = $user->getRoles()[0];
        Bouncer::retract($current_role)->from($user);
        Bouncer::assign($event->role)->to($user);
    }

    public function onApplicantLinkedToJobPosition(ApplicantLinkedToJobPosition $event)
    {
        $user = User::find($event->user_id);
        $detail = UserDetails::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'available_positions',
            'active' => 1
        ]);

        $detail->value = true;
        $detail->misc = $event->job_positions;
        $detail->save();
    }

    public function onAccessTokenGranted(AccessTokenGranted $event)
    {
        $user = User::find($event->user);
        $user->tokens()->delete();
        $token = $user->createToken($user->email)->plainTextToken;
        $deet = UserDetails::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'api-token',
            'active' => 1
        ]);

        $deet->value = base64_encode($token);
        $deet->save();
    }

    public function onUserUpdated(UserUpdated $event)
    {
        $user = User::find($event->user_id);
        $user->update($event->details);
    }

    public function onUserVerified(UserVerified $event)
    {
        $user = User::find($event->user_id);
        $user->update(['email_verified_at' => $event->date]);
    }

    public function onPasswordUpdated(PasswordUpdated $event)
    {
        $user = User::find($event->user_id);
        $user->update(['password' => $event->getPw()]);
    }

    public function onApplicantUploadedResume(ApplicantUploadedResume $event)
    {
        $uploaded_file_record = UploadedFile::firstOrCreate([
            'file_path' => $event->path,
            'entity_id' => $event->user_id,
            'entity' => User::class,
            'active' => 1
        ]);

        $user_file_upload = UserFileUpload::firstOrCreate([
            'file_id' => $uploaded_file_record->id,
            'user_id' => $event->user_id,
            'file_nickname' => 'Candidate Resume',
            'description' => 'The candidate\'s resume.'
        ]);

        $detail = UserDetails::firstOrCreate([
            'user_id' => $event->user_id,
            'name' => 'resume_uploaded',
        ]);
        $detail->value = $user_file_upload->id;
        $detail->misc = ['path' => $event->path];
        $detail->save();
    }
}
