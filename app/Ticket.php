<?php

namespace App;

use Log;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	//status to string conversion table
	const STATUS = array(	"new",  
							"closed", 
							"cancelled",
							"other (see messages)",
							"public",
							"on hold");

	protected $table = 'ticket';
    public $timestamps = false;
    protected $primary = 'id';

    //laravel relationships
    public function messages(){
    	return $this->hasMany('App\Message');
    }
    public function assignedTo(){
    	return $this->belongsTo('App\User', 'assigned_to', 'id');
    }
    public function placedBy(){
    	return $this->belongsTo('App\User', 'placed_by', 'id');
    }


    /*
	** gets the ticket status in a friendly format
    ** @param $status is an optional numeric value to use in place of the value stored in $this->status
    ** @returns the status of this ticket in a human readable format
    **/
    public function friendlyStatus($status = null){
 		if(is_null($status)){
 			$status = $this->status;
 		}

    	//for some reason, php doesn't play nice with isset(const)
    	$statuses= Ticket::STATUS;
 		if(isset($statuses[$status])){
 			return Ticket::STATUS[$status];
 		}
    	//status out of bounds
    	else{
			Log::error("Unknown status for ticket\n\tStatus: ".$status);
			throw new \Exception("status undefined: ".$status, 1);
			return 'Unknown status';
		}
    }

    //inverse of above function (friendlyStatus)
    public function getStatusFromFriendly($status_string){

    	//this line creates the inversion
 		$inverseStatuses = array_flip(Ticket::STATUS);
 		if(isset($inverseStatuses[$status_string])){
 			return $inverseStatuses[$status_string];
 		}

    	//if the value passed in isn't formatted according to the map, an error
    	//has occurred
    	else{
			Log::error("Unknown status for ticket\n\tStatus: ".$status_string);
			throw new \Exception("status undefined: ".$status_string, 1);
			return 'Unknown status';
		}
    }

    /**
    ** This method returns this so that the action
    ** Ticket::find($id)->setStatus(0)->save();
    ** can be written in one line. I LOVE this practice
    **/
    public function setStatus($status){
    	$this->status = $status;
    	return $this;
    }
}
