@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', 'User does not have permission to access this page.')
