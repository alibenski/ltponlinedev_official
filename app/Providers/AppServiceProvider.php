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

       // Using Closure based composers...
        view()->composer('partials._nav2', function ($view) {
            $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            $view->with('term', $current_enrol_term);
        });

        Validator::extend('not_equal_to_existing', function($attribute, $value, $parameters, $validator) {
            // get array values from $validator param
            $data = $validator->getData();
            $staff = $data['INDEXID'];
            $tecode = $data['Te_Code'];
            $form_counter = $data['form_counter'];
            $term = $data['Term'];
            
            $next_term_code = $term;
            $query_form = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $form_counter)
                                ->first(); 

            if ($attribute == 'decision') {
                $existing_appr_value = $query_form->approval;

                if($value != $existing_appr_value){
                    return true;
                } 
                    return false;
            } else if ($attribute == 'decisionhr') {
                $existing_appr_value_hr = $query_form->approval_hr;

                if($value != $existing_appr_value_hr){
                    return true;
                } 
                    return false;
            }
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
