<!DOCTYPE html>
<html>
    <head>
        <title>{{ $title }}</title>
    </head>
    <body>
        <h2>VOTE n°{{ $id }}</h2>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>
        <img src="{{public_path('storage/charts/vote_'.$id.'.png')}}" style="width: 100%;">
    </body>
</html>