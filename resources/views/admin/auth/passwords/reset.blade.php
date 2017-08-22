@extends('brackets/admin-auth::admin.layout.simple')

@section('body')
    <div class="row">
        <div class="col-md-8 m-x-auto pull-xs-none vamiddle">
            <div class="card-group ">
                <div class="card p-a-2">
                    <div class="card-block">
                        <user-form
                            :action="'{{ route('brackets/admin-auth:admin/password/reset') }}'"
                            :data="{ 'email': '{{ $email or old('email') }}' }"
                            inline-template>
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('brackets/admin-auth:admin/password/reset') }}">
                                {{ csrf_field() }}
                                <h1>{{ trans('brackets/admin-auth::admin.password-reset.title') }}</h1>
                                <p class="text-muted">{{ trans('brackets/admin-auth::admin.password-reset.note') }}</p>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group row" :class="{'has-danger': errors.has('email'), 'has-success': this.fields.email && this.fields.email.valid }">
                                    <label for="email" class="col-md-3 col-form-label text-md-right">{{ trans('brackets/admin-auth::admin.auth-global.email') }}</label>
                                    <div class="col-md-9 col-xl-8">
                                        <input type="text" v-model="form.email" v-validate="'required|email'" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': this.fields.email && this.fields.email.valid}" id="email" name="email" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.email') }}">
                                        <div v-if="errors.has('email')" class="form-control-feedback" v-cloak>@{{ errors.first('email') }}</div>
                                        @if ($errors->has('email'))
                                            <div class="form-control-feedback" v-cloak>{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row" :class="{'has-danger': errors.has('password'), 'has-success': this.fields.password && this.fields.password.valid }">
                                    <label for="password" class="col-md-3 col-form-label text-md-right">{{ trans('brackets/admin-auth::admin.auth-global.password') }}</label>
                                    <div class="col-md-9 col-xl-8">
                                        <input type="password" v-model="form.password" v-validate="'required|confirmed:password_confirmation|min:7'" class="form-control" :class="{'form-control-danger': errors.has('password'), 'form-control-success': this.fields.password && this.fields.password.valid}" id="password" name="password" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.password') }}">
                                        <div v-if="errors.has('password')" class="form-control-feedback" v-cloak>@{{ errors.first('password') }}</div>
                                        @if ($errors->has('password'))
                                            <div class="form-control-feedback" v-cloak>{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row" :class="{'has-danger': errors.has('password_confirmation'), 'has-success': this.fields.password_confirmation && this.fields.password_confirmation.valid }">
                                    <label for="password_confirmation" class="col-md-3 col-form-label text-md-right">{{ trans('brackets/admin-auth::admin.auth-global.password-confirm') }}</label>
                                    <div class="col-md-9 col-xl-8">
                                        <input type="password" v-model="form.password_confirmation" v-validate="'required|confirmed:password_confirmation|min:7'" class="form-control" :class="{'form-control-danger': errors.has('password_confirmation'), 'form-control-success': this.fields.password_confirmation && this.fields.password_confirmation.valid}" id="password_confirmation" name="password_confirmation" placeholder="{{ trans('brackets/admin-auth::admin.auth-global.password') }}">
                                        <div v-if="errors.has('password_confirmation')" class="form-control-feedback" v-cloak>@{{ errors.first('password') }}</div>
                                        @if ($errors->has('password_confirmation'))
                                            <div class="form-control-feedback" v-cloak>{{ $errors->first('password_confirmation') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-6">
                                        <input type="hidden" name="remember" value="1">
                                        <button type="submit" class="btn btn-primary p-x-2">{{ trans('brackets/admin-auth::admin.password-reset.button') }}</button>
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
