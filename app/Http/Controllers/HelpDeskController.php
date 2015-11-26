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
    public function create()
    {
        if(!Auth::check()){
            return Redirect::to('/auth/login');
        }
        return view('helpdesk/newTicket');
    }

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

    public function searchTickets(){
        $query = Input::get('query');
        $tickets = Ticket::where('subject', 'like', "%${query}%")->get();
        $ticketFromMessages = Message::where('text', 'like', "%${query}%")->get()->map(function($message){
            return $message->ticket;
        });
        $tickets = $tickets->merge($ticketFromMessages)->unique();
        $tickets = $tickets->filter(function($ticket){
            return Gate::allows('view-ticket', $ticket);
        });
        return view("helpdesk/searchResults", compact('tickets'));
    }

    public function editRoles($userId){
        //if you can't edit roles, abort.
        if(!Gate::allows('edit-roles')){
            abort(403);
        }

        $user = User::find($userId);
        $a = [];
        foreach(Role::all() as $role){
            $action = Input::get($role->description . "Role");
            if(!is_null($action)){
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
        }
        return Redirect::to('/user/'.$user->id);

    }

    public function postTicket(){
        if(!Auth::check()){
            return Redirect::to("auth/login");
        }
        $ticket = (new Ticket())->open();
        $ticket->priority = 2;
        $ticket->date_created = Carbon::now();
        $ticket->placed_by = Auth::id();
        $ticket->assigned_to = null;
        $ticket->subject = Input::get("ticketTitle");
        $ticket->save();

        $message = new Message();
        $message->user()->associate(Auth::user());
        $message->ticket()->associate($ticket);
        $message->text = Input::get("ticketText");
        $message->ticket_status = $ticket->status;
        $message->created = Carbon::now();
        $message->save();

        return HelpDeskController::showTicket($ticket->id);
    }


    public function showTicket($ticketId){
        $ticket = Ticket::find($ticketId);
        if(Gate::denies('view-ticket', $ticket)){
            abort(403);
        }
        return view('helpdesk/ticket', compact("ticket"));
    }

    public function message($ticketId){

        //get ticket and auth
        $ticket = Ticket::find($ticketId);
        if(Gate::denies('add-message-to-ticket', $ticket)){
            abort(403);
        }

        //if the ticket status needs to be changed
        $status = Input::get('newStatus');
        if($ticket->status != $status){

            $ticket->setStatus($status)->save();
            //Add a notice
        $message = new Message();
        $message->ticket()->associate($ticket);
        $message->text = Auth::user()->name . " set status to ".ucfirst($ticket->friendlyStatus())."\n";
        $message->created = Carbon::now();
        $message->ticket_status;
        $message->save();
        }
        $message = new Message();        
        $message->user()->associate(Auth::user());
        $message->ticket()->associate($ticket);
        $message->text = Input::get('message');
        $message->created = Carbon::now();
        $message->ticket_status;
        $message->save();

        return view('helpdesk/ticket', compact('ticket'));
    }
        

    public function showTickets(){
        $tickets = Ticket::all();
        $tickets->filter(function($ticket){
            return Gate::allows('view-ticket', $ticket);
        });

        return view('helpdesk/manyTickets', compact('tickets'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){
            if(Auth::user()->is('tech')){
                return Redirect::to('/tickets');
            }
            return Redirect::to('/user/me');
        }
        return view('helpdesk/search');
    }
}
