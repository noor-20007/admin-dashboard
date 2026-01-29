@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ $counts['users'] }}</h3>
                <p>Users</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                More info <i class="bi bi-link-45deg"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $counts['members'] }}</h3>
                <p>Members</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-people"></i>
            </div>
            <a href="{{ route('admin.members.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                More info <i class="bi bi-link-45deg"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>{{ $counts['posts'] }}</h3>
                <p>Posts</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-journal-text"></i>
            </div>
            <a href="{{ route('admin.posts.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                More info <i class="bi bi-link-45deg"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $counts['portfolios'] }}</h3>
                <p>Portfolios</p>
            </div>
            <div class="small-box-icon">
                <i class="bi bi-briefcase"></i>
            </div>
            <a href="{{ route('admin.portfolios.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                More info <i class="bi bi-link-45deg"></i>
            </a>
        </div>
    </div>
</div>
@endsection
