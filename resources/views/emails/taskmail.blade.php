<!DOCTYPE html>
<html>
<head>
    <title>Todo - Api</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
     <p>Hi {{ $details['name'] }}</p> 
    <p>{{ $details['body'] }}</p>   
   
    @foreach($details['todoList'] as $key => $list)
    	@if($key != 'email')
    		<p>{{ $key }} : {{ $list }}</p> 
        @endif
    @endforeach

    <p>Thank you</p>
</body>
</html>