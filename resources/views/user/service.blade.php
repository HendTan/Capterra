@extends("shared.user.layout")

@section("title")
Service
@endsection

@section("style")
<style>
    .col-6 {
        text-align: center;
    }

    .col-6 i{
        font-size: 5rem;
    }

    .col-6 a{
        color: var(--primary-text-color);
        text-decoration: none
    }
</style>
@endsection

@section("content")
<div class="container">
    @if(count($data) > 0)
        <div class="row">
            @foreach($data as $d)
                <div class="col-6 mt-3">
                    <a href="{{ $d["link"] }}">
                        <i class="bi bi-chat-right-text"></i> <br>
                        {{ $d["name"] }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection