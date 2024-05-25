<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CompanyLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompanyLikesController extends Controller
{
    public function like(Request $request)
    {
        $request->validate(['company_id' => 'required|exists:companies,id']);

        $userId = Auth::id();
        $companyId = $request->company_id;

        $like = CompanyLike::withTrashed()
            ->where('user_id', $userId)
            ->where('company_id', $companyId)
            ->first();

        if ($like) {
            if ($like->trashed()) {
                $like->restore(); // Restore like if it was soft-deleted
                $message = 'Liked successfully';
            } else {
                $like->delete(); // Soft delete the like
                $message = 'Unliked successfully';
            }
        } else {
            CompanyLike::create(['user_id' => $userId, 'company_id' => $companyId]);
            $message = 'Liked successfully';
        }

        return response()->json(['message' => $message]);
    }

    public function isLiked(Request $request)
    {
        $request->validate(['company_id' => 'required|exists:companies,id']);

        $userId = Auth::id();
        $companyId = $request->company_id;

        $isLiked = CompanyLike::where('user_id', $userId)
            ->where('company_id', $companyId)
            ->exists();

        return response()->json(['liked' => $isLiked]);
    }
}
