<section id="users">

    <div class="container   ">
        @foreach($users as $user)
            @include('partials.user.card', ['user' => $user])
        @endforeach
    </div>

</section>
