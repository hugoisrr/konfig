<?php

namespace App\Http\Controllers;

use App\Model\AccessReason;
use Illuminate\Http\Request;

class AccessReasonController extends Controller
{
    /**
     * @param int $status
     * @return AccessReason
     */
    public static function store(int $status)
    {
        $accessReason = new AccessReason;
        $accessReason->status = $status;

        $accessReason->save();

        return $accessReason;
    }
}
