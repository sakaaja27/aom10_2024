<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Register</title>
</head>
<body>
  <div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 side-image">
            </div>

            <div class="col-md-6 right">
                
                <div class="input-box">
                   
                   <header>Welcome Back to <span class="text-danger">AOM</span></header>
                   <form action="{{route('register')}}" method="post">
                    @csrf

                    <div class="input-field">
                        <input type="text" class="input" id="username" required name="name" value="{{ old('username') }}" autocomplete="off" autofocus>
                        <label for="username">Username</label> 
        
                    </div> 
                   <div class="input-field">
                        <input type="text" class="input" id="email" name="email" value="{{ old('email') }}" required autocomplete="off">
                        <label for="email">Email</label> 
                    </div> 
                   <div class="input-field">
                        <input type="password" class="input" name="password" value="{{ old('password') }}" id="pass" required>
                        <label for="pass">Password</label>
                    </div> 
                    
                   <div class="input-field">
                        
                        <input type="submit" class="submit" value="Sign Up">
                   </div> 
                   </form>
                   <div class="signin">
                    <span>Already have an account? <a href="{{route('login')}}">Log in here</a></span>
                   </div>
                   <div class="signin pb-lg-5">
                    <span>Back to<a href="{{route('home')}}"> Home</a></span>
                   </div>
                </div>  
            </div>
        </div>
    </div>
</div>
</body>
</html>