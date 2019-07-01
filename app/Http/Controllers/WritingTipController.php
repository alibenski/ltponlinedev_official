<?php

namespace App\Http\Controllers;

use App\WritingTip;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WritingTipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = WritingTip::all();

        return view('writing_tips.index_writing_tip', compact('records') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $emails = DB::connection('drupal')->table('')->get()->pluck('val','key');
        $languages = DB::table('languages')->pluck("name","code")->all();

        return view('writing_tips.create_writing_tip', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'L' => 'required|',
            'subject' => 'string|',
            'text' => 'string|',
        ]);

        $record = new WritingTip;
        $record->L = $request->L;
        $record->subject = $request->subject;
        $record->text = $request->text;
        $record->created_by = Auth::id();
        $record->save();

        $request->session()->flash('success', 'Entry has been saved!'); //laravel 5.4 version
        return redirect()->route('writing-tips.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WritingTip  $writingTip
     * @return \Illuminate\Http\Response
     */
    public function show(WritingTip $writingTip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WritingTip  $writingTip
     * @return \Illuminate\Http\Response
     */
    public function edit(WritingTip $writingTip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WritingTip  $writingTip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WritingTip $writingTip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WritingTip  $writingTip
     * @return \Illuminate\Http\Response
     */
    public function destroy(WritingTip $writingTip)
    {
        //
    }
}
