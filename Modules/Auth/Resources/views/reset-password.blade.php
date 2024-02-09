<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{route('password.update')}}" method="post">
    @csrf
    <input type="hidden" name="token" value="{{request('token')}}">
    <div>
        <label for="email">Email: </label>
        <input type="text" name="email" value="{{request('email')}}" id="email">
    </div>
    <div>
        <label for="password">New Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div>
        <label for="password_confirmation">New Password Confirmation</label>
        <input type="password" name="password_confirmation" id="password_confirmation">
    </div>
    <input type="submit" value="reset">
</form>
</body>
</html>
