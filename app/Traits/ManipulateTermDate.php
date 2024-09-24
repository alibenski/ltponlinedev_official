<?php

namespace App\Traits;

use App\Term;
use Carbon\Carbon;

trait ManipulateTermDate
{
    public function manipulateTermDateEn($term)
    {
        // get term values and convert to strings
        $term_date_begin = new Carbon(Term::where('Term_Code', $term)->first()->Term_Begin);
        $termBeginFormat = $term_date_begin->addWeeks(1)->format('Y-m-d');
        $termBeginStr = date('j F', strtotime($termBeginFormat));

        $term_date_end = new Carbon(Term::where('Term_Code', $term)->first()->Term_End);
        $termEndFormat = $term_date_end->format('Y-m-d');
        $termEndStr = date('j F Y', strtotime($termEndFormat));

        $termNameStr = $termBeginStr . ' - ' . $termEndStr;
        $term_en = $termNameStr;

        return $term_en;
    }

    public function manipulateTermDateFr($term)
    {
        // get term values and convert to strings
        $term_date_begin = new Carbon(Term::where('Term_Code', $term)->first()->Term_Begin);
        $termBeginFormat = $term_date_begin->addWeeks(1)->format('Y-m-d');

        $term_date_end = new Carbon(Term::where('Term_Code', $term)->first()->Term_End);
        $termEndFormat = $term_date_end->format('Y-m-d');

        // translate 
        $termBeginMonth = date('F', strtotime($termBeginFormat));
        $termEndMonth = date('F', strtotime($termEndFormat));
        $termBeginDate = date('j', strtotime($termBeginFormat));
        $termEndDate = date('j', strtotime($termEndFormat));
        $termBeginYear = date('Y', strtotime($termBeginFormat));
        $termEndYear = date('Y', strtotime($termEndFormat));

        $termBeginMonthFr = __('months.' . $termBeginMonth, [], 'fr');
        $termEndMonthFr = __('months.' . $termEndMonth, [], 'fr');

        $termNameFr = $termBeginDate . ' ' . $termBeginMonthFr . ' au ' . $termEndDate . ' ' . $termEndMonthFr . ' ' . $termEndYear;
        $term_fr = $termNameFr;

        return $term_fr;
    }
}
