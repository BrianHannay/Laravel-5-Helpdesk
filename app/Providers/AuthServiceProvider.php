<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //the user can change a ticket status if they own it or they are the tech assigned to it.
        $gate->define('change-ticket-status', function($user, $ticket){
            return $user->id === $ticket->user_id || $user->id === $ticket->assigned_to || $user->is('admin');
        });
        
        //the message can only be added if you are in the ticket
        $gate->define('add-message-to-ticket', function($user, $ticket){
            return $user->id === $ticket->user_id || $user->id === $ticket->assigned_to || $user->is('admin');
        });

        //if you are an admin, you can change any ticket. Otherwise, if you are assigned the ticket, you can re-assign it
        //if you cannot solve the problem.
        $gate->define('assign-ticket', function($user, $ticket){
            return $user->id === $ticket->assigned_to || $user->is('admin');
        });

        //you can view a ticket if you are a tech, a admin, the user who created the ticket, or if the ticket is in the public DB.
        $gate->define('view-ticket', function($user, $ticket){
            
            //if you have the admin role
            return $user->is('admin') || 

                    //if you are a tech and this ticket is either assigned to you
                    //or the ticket is open
                    $user->is('tech')  && ($ticket->assigned_to === $user->id || $ticket->status === 0)||
                    
                    //the user created the ticket
                    $user->id === $ticket->user_id ||

                    //the ticket is public
                    $ticket->status === 4;
        });
        
        //you can only edit roles if you are an admin.
        $gate->define('edit-roles', function($user){
            return $user->is('admin');
        });
    }
}
