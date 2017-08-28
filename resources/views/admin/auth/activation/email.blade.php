@extends('brackets/admin::admin.layout.master')

@section('body')
	<div class="container" id="app">
	    <div class="row">
	        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
	            <div class="card-group ">
	                <div class="card p-a-2">
	                    <div class="card-block">
	                        <user-form
	                            :action="'{{ route('brackets/admin-auth:admin/activation/sendActivationEmail') }}'"
	                            :data="{ 'email': '{{ old('email', '') }}' }"
	                            inline-template>
	                            <form class="form-horizontal" role="form" method="POST" action="{{ route('brackets/admin-auth:admin/activation/sendActivationEmail') }}">
	                                {{ csrf_field() }}
	                                <h1>{{ trans('brackets/admin-auth::admin.activation-form.title') }}</h1>
	                                <p class="text-muted">{{ trans('brackets/admin-auth::admin.activation-form.note') }}</p>
	                                @if (session('status'))
	                                    <div class="alert alert-success">
	                                        {{ session('status') }}
	                                    </div>
	                                @endif
	                                @if ($errors->has('email') || $errors->has('token'))
	                                    <div class="alert alert-danger">
	                                        @if ($errors->has('token'))
	                                            {{ $errors->first('token') }}<br />
	                                        @endif
	                                        @if ($errors->has('email'))
	                                            {{ $errors->first('email') }}<br />
	                                        @endif
	                                    </div>
	                                @endif
	                                <div class="form-group row" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
	                                    <label for="email" class="col-md-3 col-form-label text-md-right">{{ trans('brackets/admin-auth::admin.auth-global.email') }}</label>
	                                    <div class="col-md-9 col-xl-8 input-group">
	                                        <input type="text" v-model="form.email" v-validate="'required|email'" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.email') }}">
	                                        <div v-if="errors.has('email')" class="form-control-feedback" v-cloak>@{{ errors.first('email') }}</div>
	                                    </div>
	                                </div>
	                                <div class="row">
	                                    <div class="col-xs-6">
	                                        <input type="hidden" name="remember" value="1">
	                                        <button type="submit" class="btn btn-primary p-x-2">{{ trans('brackets/admin-auth::admin.activation-form.button') }}</button>
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
