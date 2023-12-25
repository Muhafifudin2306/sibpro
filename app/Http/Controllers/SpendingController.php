<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Notification;
use Illuminate\Http\Request;

class SpendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function indexAttribute()
    {
        $attributes = Attribute::select('attribute_name','slug')->where('attribute_type',1)->get();
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();
        return view('spending.attribute.index', compact('attributes','notifications'));
    }

    public function detailAttribute($slug)
    {
        $data = Attribute::where('slug',$slug)->first();

        if (!$data) {
            abort(404);
        }

        $id = $data->id;

        $attribute = Attribute::find($id);
        $notifications = Notification::orderByRaw("CASE WHEN notification_status = 0 THEN 0 ELSE 1 END, updated_at DESC")->limit(10)->get();

        return view('spending.attribute.detail', compact('attribute','notifications'));
    }

}
