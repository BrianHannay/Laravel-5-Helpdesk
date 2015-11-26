<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\Ticket;
use App\Message;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\DocBlock\Type\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class HelpDeskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTicket()
    {
        if(!Auth::check()){
            return Redirect::to('/auth/login');
        }
        return view('helpdesk/newTicket');
    }

    /**
     * Display the specified user or the logged in user
     * @param $id is an optional paramater which is used to identify a user to show
     */
    public function showUser($id = null){
        if($id === null){
            return HelpDeskController::showUser(Auth::id());
        }
        $user = User::find($id);
        $tickets = $user->tickets;
        $roles = Role::all();
        return view('auth/show', compact("user", "tickets", "roles"));
        //todo: show user
    }

    /*
    **  searches tickets based on a posted query
    */
    public function searchTickets(){

        //str replace to fix an edge case - a user wants to search for a literal %
        $query = str_replace('%', '\%', Input::get('query'));

        //collect tickets from various places matched by the search
        $tickets = Ticket::where('subject', 'like', "%${query}%")->
            get()->
            merge(

                //any tickets which have messages with matching text
                Message::where('text', 'like', "%${query}%")->
                    get()->
                    map(function($message){
                        return $message->ticket;
                    })
            )->
            unique()->
            filter(function($ticket){
                return Gate::allows('view-ticket', $ticket);
            });

        return view("helpdesk/searchResults", compact('tickets'));
    }


    /*
    ** adds/removes a role from a user
    ** @param $userId identifies the user to add/remove roles on
    */
    public function editRoles($userId = null){
        if(is_null($userId)){
            return editRoles(Auth::id());
        }

        //if you can't edit roles, abort.
        if(!Gate::allows('edit-roles')){
            abort(403);
        }
        $user = User::find($userId);

        //example posted data: adminRole=Add
        Role::all()->each(function($role){
            if(Input::has($role->description."Role")){
                Input::get($role->description."Role");
                $action = Input::get($inputName);
                if($action === "Add"){
                    $user->roles()->attach($role);
                }
                elseif($action === "Remove"){
                    $user->roles()->detach($role);
                }
                else{
                    Redirect::to('/error/whatAreYouEvenTryingToDo');
                }
            }
        });
        return Redirect::to('/user/'.$user->id);
    }

    //create a ticket from posted data
    public function postTicket(){

        //require login
        if(!Auth::check()){
            return Redirect::to("auth/login");
        }

        //auth check passed
        $ticket = new Ticket();
        
        //this ticket should be associated with nobody when it is first created.
        $ticket->assigned_to = null;
        $ticket->setStatus($ticket->getStatusFromFriendly('new'));
        $ticket->priority = 2;
        $ticket->date_created = Carbon::now();
        $ticket->placedBy()->associate(Auth::user());
        $ticket->subject = Input::get("ticketTitle");
        $ticket->save();

        //add a message containing the ticket text
        $message = new Message();
        $message->user()->associate(Auth::user());
        $message->ticket()->associate($ticket);
        $message->text = Input::get("ticketText");
        $message->ticket_status = $ticket->status;
        $message->created = Carbon::now();
        $message->save();

        //now show the ticket
        return view('helpdesk/ticket', compact("ticket"));
    }

    /*
    ** creates a view a ticket
    ** @param $ticketId the ID of the ticket to show
    ** @returns the view created for the ticket
    */
    public function showTicket($ticketId){
        $ticket = Ticket::find($ticketId);
        if(Gate::denies('view-ticket', $ticket)){
            abort(403);
        }
        return view('helpdesk/ticket', compact("ticket"));
    }

    /*
    ** reads from field data from Input::get and adds a message to the ticket with the specified ID
    ** 
    */
    public function postMessage($ticketId, $messageText){

        //get ticket and auth
        $ticket = Ticket::find($ticketId);
        if(Gate::denies('add-message-to-ticket', $ticket)){
            abort(403);
        }

        //if the ticket status needs to be changed, change it
        $status = Input::get('newStatus');
        if($ticket->status != $status){
            $ticket->setStatus($status)->save();
            
            //Add a notice that the status was changed
            $message = new Message();
            $message->ticket()->associate($ticket);
            $message->text = Auth::user()->name . " set status to ".ucfirst($ticket->friendlyStatus())."\n";
            $message->created = Carbon::now();
            $message->ticket_status;
            $message->save();
        }

        //create the message
        $message = new Message();        
        $message->user()->associate(Auth::user());
        $message->ticket()->associate($ticket);
        $message->text = Input::get('message');
        $message->created = Carbon::now();
        $message->ticket_status;
        $message->save();

        return view('helpdesk/ticket', compact('ticket'));
    }
        
    //shows all the tickets that you're allowed to see
    public function showTickets(){
        $tickets = Ticket::all()->
            filter(function($ticket){
                return Gate::allows('view-ticket', $ticket);
            });
        return view('helpdesk/manyTickets', compact('tickets'));
    }


    /**
     * shows whichever page you should be viewing with your current permissions
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){

            if(Auth::user()->is('tech')){
                return Redirect::to('/tickets');
            }

            else if(Auth::user()->is('admin')){
                return view('helpdesk/assignTickets');
            }

            else{
                return Redirect::to('/user/me');
            }
        }
        else{
            return view('helpdesk/search');
        }
    }
}
