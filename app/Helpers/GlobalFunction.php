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
      public function currentTermObject()
      {
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;

        //query the current term object based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
        $currentTermObject = Term::orderBy('Term_Code', 'desc')
                        ->whereDate('Term_End', '>=', $now_date)
                        ->get()->min();
        return $currentTermObject;                
      }      
      public function currentEnrolTermObject()
      {
        $now_date = Carbon::now()->toDateString();

        $currentEnrolTermObject = Term::orderBy('Term_Code', 'desc')
                        ->whereDate('Enrol_Date_End', '>=', $now_date)
                        ->get()->min();
        return $currentEnrolTermObject;                
      }
      public function nextTermCode()
      {
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;

        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
        $terms = Term::orderBy('Term_Code', 'desc')
                        ->whereDate('Term_End', '>=', $now_date)
                        ->get()->min();
        //query the next term based Term_Begin column is greater than today's date and then get min
        //output the Term_Code of the next term
        $next_term = Term::orderBy('Term_Code', 'desc')
                        ->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');

        return "$next_term";                
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