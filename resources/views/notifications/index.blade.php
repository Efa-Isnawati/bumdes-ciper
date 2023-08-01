{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Notifications</h1>

        @if($notifications->count() > 0)
            <div class="list-group mt-4">
                @foreach($notifications as $notification)
                    <a href="#" class="list-group-item list-group-item-action">
                        <h5 class="mb-1">{{ $notification->data['title'] }}</h5>
                        <p class="mb-1">{{ $notification->data['message'] }}</p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </a>
                @endforeach
            </div>
        @else
            <p>No notifications.</p>
        @endif
    </div>
@endsection --}}
