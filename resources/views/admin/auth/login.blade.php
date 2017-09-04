@extends('brackets/admin::admin.layout.master')

@section('title', trans('brackets/admin-auth::admin.login.title'))

@section('content')
	<div class="container" id="app">
	    <div class="row align-items-center justify-content-center auth">
	        <div class="col-md-6 col-lg-5">
				<div class="card">
					<div class="card-block">
						<user-form
								:action="'{{ route('brackets/admin-auth:admin/login') }}'"
								:data="{}"
								inline-template>
							<form class="form-horizontal" role="form" method="POST" action="{{ route('brackets/admin-auth:admin/login') }}" novalidate>
								{{ csrf_field() }}
								<div class="auth-header">
									<h1 class="auth-title">{{ trans('brackets/admin-auth::admin.login.title') }}</h1>
									<p class="auth-subtitle">{{ trans('brackets/admin-auth::admin.login.signInText') }}</p>
								</div>
								<div class="auth-body">
									@if (session('status'))
										<div class="alert alert-success">
											{{ session('status') }}
										</div>
									@endif
									<div class="form-group" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
										<label for="email">{{ trans('brackets/admin-auth::admin.auth-global.email') }}</label>
										<div class="input-group input-group--custom">
											<div class="input-group-addon"><i class="input-icon input-icon--mail"></i></div>
											<input type="text" v-model="form.email" v-validate="'required|email'" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.email') }}">
										</div>
										<div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
									</div>

									<div class="form-group" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
										<label for="password">{{ trans('brackets/admin-auth::admin.auth-global.password') }}</label>
										<div class="input-group input-group--custom">
											<div class="input-group-addon"><i class="input-icon input-icon--lock"></i></div>
											<input type="password" v-model="form.password" v-validate="''" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.password') }}">
										</div>
										<div v-if="errors.has('password')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('password') }}</div>
									</div>

									<div class="form-group">
										<input type="hidden" name="remember" value="1">
										<button type="submit" class="btn btn-primary btn-block btn-spinner"><i class="fa"></i> {{ trans('brackets/admin-auth::admin.login.button') }}</button>
									</div>
									<div class="form-group text-center">
										<a href="{{ route('brackets/admin-auth:admin/password/showLinkRequestForm') }}" class="auth-ghost-link">{{ trans('brackets/admin-auth::admin.login.forgotPassword') }}</a>
									</div>
								</div>
							</form>
						</user-form>
					</div>
				</div>
	        </div>
	    </div>
	</div>
@endsection
