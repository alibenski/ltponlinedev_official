<?php

namespace App\Http\Controllers;

use App\Repo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PrinterController extends Controller
{
    public function pdfAttestation(Request $request)
    {
        $printLanguage = $request->language;
        $pashId = Repo::orderBy('id', 'desc')
            ->where('id', $request->id)->first();
        $userName = $pashId->users->name;
        $termSeasonEn = $pashId->terms->Comments;
        $termSeasonFr = $pashId->terms->Comments_fr;
        $termNameEn = $pashId->terms->Term_Name;
        $termNameFr = $pashId->terms->Term_Name_Fr;
        $termYear = Carbon::parse($pashId->terms->Term_Begin)->year;
        $dateOfPrinting = Carbon::now()->format('d F Y');
        $result = $pashId->Result;
        $selfPay = $pashId->is_self_pay_form;
        $teCode = $pashId->Te_Code;
        $teCodeOld = $pashId->Te_Code_old;
        $pashCourseSched = $pashId->courseschedules;
        if ($pashCourseSched != null) {
            $price = $pashId->courseschedules->prices->price;
        } else {
            $price = '';
        }
        if ($teCode != null) {
            if ($pashId->courses) {
                $courseEn = $pashId->courses->Description;
                $courseFr = $pashId->courses->FDescription;
            } else {
                $courseEn = 'N/A';
                $courseFr = 'N/A';
            }
        } elseif ($teCodeOld != null) {
            if ($pashId->coursesOld) {
                $courseEn = $pashId->coursesOld->Description;
                $courseFr = $pashId->coursesOld->FDescription;
            } else {
                $courseEn = 'N/A';
                $courseFr = 'N/A';
            }
        } else {
            $courseEn = 'N/A';
            $courseFr = 'N/A';
        }
        if ($request->filled('download')) {
            if ($printLanguage == 'Fr') {
                $pdf = PDF::loadView('pdf_forms.pdfFrAttestationCompletedCourse', compact('userName', 'termSeasonFr', 'termYear', 'dateOfPrinting', 'courseFr', 'result', 'selfPay', 'termNameFr', 'price'));
                return $pdf->stream();
            }
            if ($printLanguage == 'En') {
                $pdf = PDF::loadView('pdf_forms.pdfEnAttestationCompletedCourse', compact('userName', 'termSeasonEn', 'termYear', 'dateOfPrinting', 'courseEn', 'result', 'selfPay', 'termNameEn', 'price'));
                return $pdf->stream();
            }
        }
        return 'Error: Link attributes not satisfied';
    }
}
