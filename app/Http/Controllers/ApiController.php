<?php

namespace App\Http\Controllers;

use App\Models\ApplyJob;
use Exception;
use Illuminate\Http\Request;
use App\Models\Job;

class ApiController extends Controller
{
    public function index()
    {
        try {
            $data = Job::with('company')
                ->get()
                ->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'job_name' => $value->job_name,
                        'job_type' => $value->job_type,
                        'company_name' => $value->company->company_name,
                        'company_address' => $value->company->company_address,
                        'logo' => $value->company->logo,
                        'is_mark' => false,
                        'req' => json_decode($value->requirement),
                    ];
                });

            return response()->json([
                'status' => "ok",
                'message' => 200,
                'jobs' => $data
            ]);
        } catch (Exception $error) {
            return tpid_response_error(null, $error->getMessage());
        }
    }

    public function applyJob(Request $request)
    {
        try {
            $data = ApplyJob::create([
                'user_id' => $request->user_id,
                'job_id' => $request->job_id,
                'description' => $request->description,
            ]);

            return tpid_response_success(null, 'success apply job');
        } catch (Exception $error) {
            return tpid_response_error(null, $error->getMessage());
        }
    }

    public function jobByUserId($userId)
    {
        try {
            $data = ApplyJob::with('job')
                ->where('user_id', $userId)
                ->get()
                ->map(function ($value) {
                    return [
                        'id' => $value->id,
                        'job_name' => $value->job->job_name,
                        'job_type' => $value->job->job_type,
                        'company_name' => $value->job->company->company_name,
                        'company_address' => $value->job->company->company_address,
                        'logo' => $value->job->company->logo,
                    ];
                });

            return tpid_response_success($data);
        } catch (Exception $error) {
            return tpid_response_error(null, $error->getMessage());
        }
    }
}
