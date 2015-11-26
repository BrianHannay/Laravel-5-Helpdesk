@extends('app')
@section('title', 'View ticket')

@section('content')
    @each('model.ticket', $tickets, 'ticket')
@overwrite