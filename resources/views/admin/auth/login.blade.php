@extends('brackets/admin::admin.layout.master')

@section('content')
	<div class="container" id="app">
	    <div class="row">
	        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
	            <div class="card-group ">
	                <div class="card p-a-2">
	                    <div class="card-block">
	                        <user-form
	                            :action="'{{ route('brackets/admin-auth:admin/login') }}'"
	                            :data="{}"
	                            inline-template>
	                            <form class="form-horizontal" role="form" method="POST" action="{{ route('brackets/admin-auth:admin/login') }}">
	                                {{ csrf_field() }}
	                                <h1>{{ trans('brackets/admin-auth::admin.login.title') }}</h1>
	                                @if (session('status'))
	                                    <div class="alert alert-success">
	                                        {{ session('status') }}
	                                    </div>
	                                @endif
	                                <p class="text-muted">{{ trans('brackets/admin-auth::admin.auth-global.login.signInText') }}</p>
	                                <div class="form-group row" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
	                                    <label for="email" class="col-md-3 col-form-label text-md-right">{{ trans('brackets/admin-auth::admin.email') }}</label>
	                                    <div class="col-md-9 col-xl-8">
	                                        <input type="text" v-model="form.email" v-validate="'required|email'" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.email') }}">
	                                        <div v-if="errors.has('email')" class="form-control-feedback" v-cloak>@{{ errors.first('email') }}</div>
	                                    </div>
	                                </div>
	                                <div class="form-group row" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
	                                    <label for="password" class="col-md-3 col-form-label text-md-right">{{ trans('brackets/admin-auth::admin.auth-global.password') }}</label>
	                                    <div class="col-md-9 col-xl-8">
	                                        <input type="password" v-model="form.password" v-validate="''" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.password') }}">
	                                        <div v-if="errors.has('password')" class="form-control-feedback" v-cloak>@{{ errors.first('password') }}</div>
	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col-xs-6">
	                                        <input type="hidden" name="remember" value="1">
	                                        <button type="submit" class="btn btn-primary p-x-2">{{ trans('brackets/admin-auth::admin.login.button') }}</button>
	                                    </div>
	                                    <div class="col-xs-6 text-xs-right">
	                                        <a href="{{ route('brackets/admin-auth:admin/password/showLinkRequestForm') }}" class="btn btn-link p-x-0">{{ trans('brackets/admin-auth::admin.login.forgotPassword') }}</a>
	                                    </div>
	                                </div>
	                            </form>
	                        </user-form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
