<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\SDDEXTR;
use App\Observers\UserObserver;
use App\Repo;
use App\Observers\PashObserver;
use App\Preenrolment;
use App\PlacementForm;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap(); // The paginator now uses the Tailwind CSS framework for its default styling. In order to keep using Bootstrap, you should add the following method call 
        User::observe(UserObserver::class);
        SDDEXTR::observe(UserObserver::class);
        Repo::observe(PashObserver::class);
        Preenrolment::observe(PashObserver::class);
        PlacementForm::observe(PashObserver::class);

        Schema::defaultStringLength(191);

        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
            Mail::raw("Mail Queue Failing: " . $event->connectionName . " " . $event->exception, function ($message) {
                $message->from('clm_language@unog.ch', 'CLM Language');
                $message->to('allyson.frias@un.org')->subject('Alert: Mail Queue Failing');
            });
        });

        // Using Closure based composers...
        view()->composer('partials._nav2', function ($view) {
            $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            $view->with('term', $current_enrol_term);
        });
        view()->composer('partials.late._nav2', function ($view) {
            $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            $view->with('term', $current_enrol_term);
        });

        Validator::extend('not_equal_to_existing', function ($attribute, $value, $parameters, $validator) {
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

                if ($value != $existing_appr_value) {
                    return true;
                }
                return false;
            } else if ($attribute == 'decisionhr') {
                $existing_appr_value_hr = $query_form->approval_hr;

                if ($value != $existing_appr_value_hr) {
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
