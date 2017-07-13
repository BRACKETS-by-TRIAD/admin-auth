@extends('brackets/admin-auth::admin.layout.simple')

@section('content')
    <div class="row">
        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
            <div class="card-group ">
                <div class="card p-a-2">
                    <div class="card-block">
                        <h1>Login</h1>
                        <p class="text-muted">Sign In to your account</p>
                        <div class="input-group m-b-1">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" class="form-control" placeholder="Username" v-validate="'required'" name="email" v-model="david">
                        </div>
                        <div class="input-group m-b-2">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <button type="button" class="btn btn-primary p-x-2">Login</button>
                            </div>
                            <div class="col-xs-6 text-xs-right">
                                <button type="button" class="btn btn-link p-x-0">Forgot password?</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
