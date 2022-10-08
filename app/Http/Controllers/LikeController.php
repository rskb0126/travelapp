<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Plan;
use App\Use;
use App\Like;

class LikeController extends Controller
{
    public function store(Plan $plan)
    {
               
        $user = Auth::user();

       
            if($plan->isLiked(Auth::id())) {
                // 対象のレコードを取得して、削除する。
                $delete_record = $plan->getLiked($user->id);
                 \Log::debug($delete_record);
                Like::destroy($delete_record[0]->id);
            } else {
                $like = Like::firstOrCreate(
                    array(
                        'user_id' => Auth::user()->id,
                        'plan_id' => $plan->id
                    )
                );
           }
       
    }
    
    public function destroy($planId) {
        Auth::user()->unlike($planId);
        return 'ok!';
    }
    
    public function check(Plan $plan) {
        $count = $plan->isLiked(Auth::id());
        // \Log::debug($count);
        return response()->json(['result' => $count], 200);
    }
    
    public function counts(Plan $plan) {
        $count = $plan->likes()->count();
        \Log::debug($count . 'count');
        return response()->json(['result' => $count], 200);
    }
}
