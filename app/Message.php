<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $table = 'message';

    public $timestamps = false;
    


    public function ticket(){
    	return $this->belongsTo('App\Ticket', 'ticket_id', 'id');
    }

    public function user(){
    	return $this->belongsTo('App\User');
    }



}