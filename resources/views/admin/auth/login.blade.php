@extends('brackets/admin-auth::admin.layout.simple')

@section('body')
    <div class="row">
        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
            <div class="card-group ">
                <div class="card p-a-2">
                    <div class="card-block">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('brackets/admin-auth:admin/login') }}">
                            {{ csrf_field() }}
                            <h1>Login</h1>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <p class="text-muted">Sign In to your account</p>
                            <div class="input-group m-b-1">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="text" class="form-control" placeholder="Username" name="email" >
                            </div>
                            <div class="input-group m-b-2">
                                <span class="input-group-addon"><i class="icon-lock"></i></span>
                                <input type="password" class="form-control" placeholder="Password" name="password">
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="hidden" name="remember" value="1">
                                    <button type="submit" class="btn btn-primary p-x-2">Login</button>
                                </div>
                                <div class="col-xs-6 text-xs-right">
                                    <a href="{{ route('brackets/admin-auth:admin/password/showLinkRequestForm') }}" class="btn btn-link p-x-0">Forgot password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
