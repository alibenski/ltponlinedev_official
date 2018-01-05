<?php
namespace App\Helpers;

use App\Term;
use Carbon\Carbon;

class GlobalFunction
{
      public function currentTerm()
      {
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year;       
        //return string of Term_Begin of CURRENT term
        $current_term_begin = Term::whereYear('Term_End', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Term_End', '>=', $now_date)
                        ->min('Term_Begin');
        
        return "$current_term_begin";
      }
      public function startQueryLog()
      {
        \DB::enableQueryLog();
      }
      public function showQueries()
      {
        dd(\DB::getQueryLog());
      }
      public static function instance()
      {
         return new GlobalFunction();
      }
}