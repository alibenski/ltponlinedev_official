<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Mail\sendWritingTip;
use App\WritingTip;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class WritingTipController extends Controller
{
    public function selectiveSendWritingTipEmail(Request $request, WritingTip $writingTip)
    {
        if ($request->ajax()) {

            $drupalEmailRecords = explode(',', $request->join_selected_values);

            $arr = [];
            foreach ($drupalEmailRecords as $emailAddress) {
                Mail::to($emailAddress)
                    ->send(new sendWritingTip($writingTip));
                $arr[] = $emailAddress;
            }

            $data = $drupalEmailRecords;
            return response()->json($data);
        }
    }

    public function sendWritingTipEmail(Request $request, WritingTip $writingTip)
    {
        $drupalEmailRecordsArray = DB::connection('drupal')->table('webform_submitted_data')->where('nid', '16098')->get(["data"])
            ->unique();
        // ->take(3);
        // ->first();

        // test sample dataset
        // $drupalEmailRecordsArray =collect(    [
        //         0 => ["data" => "allyson.frias@un.org"],
        //         1 => ["data" => "jeanpierre.gaviano@un.org"],
        //         2 => ["data" => "fabienne.pairon@un.org"]
        //     ]);

        foreach ($drupalEmailRecordsArray as $key => $drupalEmailRecords) {
            $baseDelay = Carbon::now();

            $getDelay = cache('jobs.' . SendEmailJob::class, $baseDelay);

            $setDelay = Carbon::parse(
                $getDelay
            )->addSeconds(60);

            // insert data to cache table
            cache([
                'jobs.' . SendEmailJob::class => $setDelay
            ], 5);

            $job = (new SendEmailJob($drupalEmailRecords, $writingTip))->delay($setDelay);
            dispatch($job);
        }


        // foreach ($drupalEmailRecords as $key => $emailAddress) {
        //     // $when = Carbon\Carbon::now()->addSeconds(3);

        //     Mail::to($emailAddress->data)
        //         ->queue(new sendWritingTip($writingTip));
        //         // ->later($when, new sendWritingTip($writingTip));

        // //     sleep(5);
        // }

        $request->session()->flash('success', 'Entry has been sent!');
        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = WritingTip::all();

        return view('writing_tips.index_writing_tip', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $emails = DB::connection('drupal')->table('')->get()->pluck('val','key');
        $languages = DB::table('languages')->pluck("name", "code")->all();

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
            'subject' => 'string|required',
            'text' => 'string|required',
        ]);

        $message = [
            'required' => 'The Language field is required.',
        ];
        $this->validate($request, ['L' => 'required'], $message);

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
        $drupalEmailRecords = DB::connection('drupal')->table('webform_submitted_data')->where('nid', '16098')
            ->get(["data"])->unique();
        // ->unique("data");

        return view('writing_tips.show_writing_tip', compact('writingTip', 'drupalEmailRecords'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WritingTip  $writingTip
     * @return \Illuminate\Http\Response
     */
    public function edit(WritingTip $writingTip)
    {
        return view('writing_tips.edit_writing_tip', compact('writingTip'));
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
        $this->validate($request, [
            'subject' => 'nullable',
            'text' => 'nullable',
        ]);

        $text = $writingTip;

        if (!is_null($request->subject)) {
            $text->subject = $request->subject;
        }
        if (!is_null($request->text)) {
            $text->text = $request->text;
        }

        $text->save();

        return redirect()->route('writing-tips.show', $writingTip->id);
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
