@extends('brackets/admin::admin.layout.master')

@section('title', trans('brackets/admin-auth::admin.password-reset.title'))

@section('content')
    <div class="container" id="app">
        <div class="row align-items-center justify-content-center auth">
            <div class="col-md-6 col-lg-5">
                <div class="card ">
                    <div class="card-block">
                        <user-form
                                :action="'{{ route('brackets/admin-auth:admin/password/reset') }}'"
                                :data="{ 'email': '{{ $email or old('email') }}' }"
                                inline-template>
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ route('brackets/admin-auth:admin/password/reset') }}" novalidate>
                                {{ csrf_field() }}
                                <div class="auth-header">
                                    <h1 class="auth-title">{{ trans('brackets/admin-auth::admin.password-reset.title') }}</h1>
                                    <p class="auth-subtitle">{{ trans('brackets/admin-auth::admin.password-reset.note') }}</p>
                                </div>
                                <div class="auth-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="form-group"
                                         :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
                                        <label for="email">{{ trans('brackets/admin-auth::admin.auth-global.email') }}</label>
                                        <div class="input-group input-group--custom">
                                            <div class="input-group-addon"><i class="input-icon input-icon--mail"></i>
                                            </div>
                                            <input type="text" v-model="form.email" v-validate="'required|email'"
                                                   class="form-control"
                                                   :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}"
                                                   id="email" name="email"
                                                   placeholder="{{ trans('brackets/admin-auth::admin.auth-global.email') }}">
                                        </div>
                                        <div v-if="errors.has('email')" class="form-control-feedback" v-cloak>@{{
                                            errors.first('email') }}
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="form-control-feedback"
                                                 v-cloak>{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group"
                                         :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
                                        <label for="password">{{ trans('brackets/admin-auth::admin.auth-global.password') }}</label>
                                        <div class="input-group input-group--custom">
                                            <div class="input-group-addon"><i class="input-icon input-icon--lock"></i>
                                            </div>
                                            <input type="password" v-model="form.password"
                                                   v-validate="'required|confirmed:password_confirmation|min:7'"
                                                   class="form-control"
                                                   :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}"
                                                   id="password" name="password"
                                                   placeholder="{{ trans('brackets/admin-auth::admin.auth-global.password') }}">
                                        </div>
                                        <div v-if="errors.has('password')" class="form-control-feedback" v-cloak>@{{
                                            errors.first('password') }}
                                        </div>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback"
                                                 v-cloak>{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group"
                                         :class="{'has-danger': errors.has('password_confirmation'), 'has-success': this.fields.password_confirmation && this.fields.password_confirmation.valid }">
                                        <label for="password_confirmation">{{ trans('brackets/admin-auth::admin.auth-global.password-confirm') }}</label>
                                        <div class="input-group input-group--custom">
                                            <div class="input-group-addon"><i class="input-icon input-icon--lock"></i>
                                            </div>
                                            <input type="password" v-model="form.password_confirmation"
                                                   v-validate="'required|confirmed:password_confirmation|min:7'"
                                                   class="form-control"
                                                   :class="{'form-control-danger': errors.has('password_confirmation'), 'form-control-success': this.fields.password_confirmation && this.fields.password_confirmation.valid}"
                                                   id="password_confirmation" name="password_confirmation"
                                                   placeholder="{{ trans('brackets/admin-auth::admin.auth-global.password') }}">
                                        </div>
                                        <div v-if="errors.has('password_confirmation')" class="form-control-feedback"
                                             v-cloak>@{{ errors.first('password') }}
                                        </div>
                                        @if ($errors->has('password_confirmation'))
                                            <div class="form-control-feedback"
                                                 v-cloak>{{ $errors->first('password_confirmation') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="remember" value="1">
                                        <button type="submit" class="btn btn-primary btn-block btn-spinner"><i
                                                    class="fa"></i> {{ trans('brackets/admin-auth::admin.password-reset.button') }}
                                        </button>
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
