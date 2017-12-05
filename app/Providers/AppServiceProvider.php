<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Validator;
use App\Preenrolment;
use App\Term;
use Carbon\Carbon;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        
        Validator::extend('not_equal_to_existing', function($attribute, $value, $parameters, $validator) {
            $now_date = Carbon::now()->toDateString(); dd($parameters[0]);
            $terms = Term::orderBy('Term_Code', 'desc')
                    ->whereDate('Term_End', '>=', $now_date)
                    ->get()->min();
            $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
            $query_form = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->first(); 
            $existing_appr_value = $query_form->approval;

            if(!empty($value) && $value != $existing_appr_value){
                return true;
            } 
                return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
