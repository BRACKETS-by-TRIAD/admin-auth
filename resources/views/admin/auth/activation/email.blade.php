@extends('brackets/admin-auth::admin.layout.simple')

@section('content')
    <div class="row">
        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
            <div class="card-group ">
                <div class="card p-a-2">
                    <div class="card-block">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('brackets/admin-auth:admin/activation/sendActivationEmail') }}">
                            {{ csrf_field() }}
                            <h1>Activate account</h1>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if ($errors->has('token'))
                                <div class="alert alert-danger">
                                    <strong>{{ $errors->first('token') }}</strong>
                                </div>
                            @endif
                            <p class="text-muted">Send activation link to e-mail.</p>
                            <div class="input-group m-b-1">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="email" class="form-control" placeholder="E-mail" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="hidden" name="remember" value="1">
                                    <button type="submit" class="btn btn-primary p-x-2">Send Activation Link</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
