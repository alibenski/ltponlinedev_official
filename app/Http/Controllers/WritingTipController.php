<?php

namespace App\Http\Controllers;

use App\WritingTip;
use Illuminate\Http\Request;

class WritingTipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('writing_tips.create_writing_tip');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
