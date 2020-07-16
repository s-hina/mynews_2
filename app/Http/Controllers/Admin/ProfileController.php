<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Profile;
use App\Profilehistory;
use Carbon\Carbon;


class ProfileController extends Controller
{
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        //Validationを行う
        $this->validate($request, Profile::$rules);

        $profile = new Profile;
        $form = $request->all();
    
        unset($form['_token']);
        //データベースに保存
        $profile->fill($form)->save();

        return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
        //Profile Modelからデータを取得
        $profile = Profile::find($request->id);
        if (empty($profile)) {
          abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
        //Validationをかける
        $this->validate($request, Profile::$rules);

        //News Modelからデータを取得
        $profile = Profile::find($request->id);

        //送信されてきたフォームデータを格納
        $profile_form = $request->all();

        unset($profile_form['_token']);

        //該当するデータを上書きして保存
        $profile->fill($profile_form)->save();

        $profilehistory = new Profilehistory;
        $profilehistory->profile_id = $profile->id;
        $profilehistory->edited_at = Carbon::now();
        $profilehistory->save();

        return redirect('admin/profile/edit');
    }
    
}
