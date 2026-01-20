<!doctype html>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$workshop->name}}</title>
    </head>

    <body>
        <h1>{{$workshop->name}}</h1>

        <p><strong>City:</strong>{{$workshop->city}}</p>
        <p><strong>Address:</strong>{{$workshop->address}}</p>
        <p><strong>Phone:</strong>{{$workshop->phone}}</p>

        @if($workshop->description)
            <p><strong>Description:</strong></p>
            <p>{{$workshop->description}}</p>
        @endif

        <p>
            <a href="{{route('home')}}">Back to Home</a>
        </p>

    </body>

</html>
