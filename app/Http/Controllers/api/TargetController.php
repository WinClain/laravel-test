<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\TargetRequest;
use App\Models\Target;
use Exception;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    /**
     * Request for creating new model
     * @param TargetRequest $request
     * @return mixed
     */
    public function createTarget(TargetRequest $request): mixed
    {
        $result = [
            'stasus' => false
        ];

        try {
            // Get post data for model attributes
            $attributes = $request->validated();
            
            return json_encode($attributes);
            // Set status
            $attributes['status'] = Target::OPEN_STATUS;

            $model = new Target();
            $model->fill($attributes);
            $model->save();

            if ($model->save()) {
                $result['stasus'] = true;
            } else {
                throw new Exception('Ocured error by saving data');
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return json_encode($result);
    }

    /**
     * Request for saving existing model
     * @param Request $request
     * @return mixed
     */
    public function editTarget(Request $request): mixed
    {
        $result = [
            'stasus' => false
        ];

        try {
            $attributes = json_decode($request->input('attributes'), true);
            $userId = $request->input('userId');

            $model = Target::find($attributes['id']);
            // Checking access
            if ($model->user_id == $userId) {
                // Validation
                $validatedData = (new TargetRequest)->validate($attributes);

                $model->fill($validatedData);

                if ($model->save()) {
                    $result['stasus'] = true;
                } else {
                    throw new Exception('Ocured error by saving data');
                }
            } else {
                throw new Exception('You don\'t have permissions');
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return json_encode($result);
    }

    /**
     * Request to get targets
     * @param Request $request
     * @return mixed
     */
    public function getTargets(Request $request): mixed
    {
        $result = [
            'stasus' => false
        ];
        
        try {
            $allTargetsOfUser = Target::
                where('user_id', $request->input('user_id'))->
                where('parent_id', 0)->
                with('allChildren')->
                get();
            $otherTargets = Target::
                where('user_id', '!=', $request->input('user_id'))->
                where('parent_id', 0)->
                with('allChildren')->
                get();

            $result['allTargetsOfUser'] = $allTargetsOfUser;
            $result['otherTargets'] = $otherTargets;
            $result['stasus'] = true;
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return json_encode($result);
    }

    /**
     * Close target
     * @param Request $request
     * @return mixed
     */
    public function closeTarget(Request $request): mixed
    {
        $result = [
            'stasus' => false
        ];
        
        try {
            $model = Target::
                where('id', $request->input('target_id'))->
                where('user_id', $request->input('user_id'))->
                first();

            // Check if target exists and is not closed 
            if ($model && !$model->completed_at) {
                $model->completed_at = date('Y-m-d H:i:s');
                if ($model->save()) {
                    $result['stasus'] = true;
                }
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return json_encode($result);
    }
}
