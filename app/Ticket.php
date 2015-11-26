<?php

namespace App;

use Log;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	protected $table = 'ticket';
    public $timestamps = false;
    protected $primary = 'id';
    //
    public function messages(){
    	return $this->hasMany('App\Message');
    }
    public function assignedTo(){
    	return $this->belongsTo('App\User', 'assigned_to', 'id');
    }
    public function createdBy(){
    	return $this->belongsTo('App\User', 'placed_by', 'id');
    }

    public function 

    public function friendlyStatus($status = null){
 		if(is_null($status)){
 			$status = $this->status;
 		}
		switch ($status) {
			case 0:
				return 'open';
			case 1:
				return 'closed';
			case 2:
				return 'cancelled';
			case 3:
				return 'other (see messages)';
			case 4:
				return 'public';
			case 5:
				return 'on hold';
			default:
				Log::error("Unknown status for ticket\n\tStatus: ".$status_int);
				throw new Exception("status undefined: ".$status_int, 1);
				return 'Unknown status';
		}
    }

    /**
    ** This method returns this so that the action
    ** Ticket::find($id)->setStatus(0)->
    **/
    public function setStatus($status){
    	$this->status = $status;
    	return $this;
    }
}
