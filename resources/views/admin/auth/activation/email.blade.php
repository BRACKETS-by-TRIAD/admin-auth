@extends('brackets/admin::admin.layout.master')

@section('title', trans('brackets/admin-auth::admin.activation-form.title'))

@section('content')
    <div class="container" id="app">
        <div class="row align-items-center justify-content-center auth">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-block">
                        <auth-form
                                :action="'{{ route('brackets/admin-auth:admin/activation/sendActivationEmail') }}'"
                                :data="{ 'email': '{{ old('email', '') }}' }"
                                inline-template>
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ route('brackets/admin-auth:admin/activation/sendActivationEmail') }}"
                                  novalidate>
                                {{ csrf_field() }}
                                <div class="auth-header">
                                    <h1 class="auth-title">{{ trans('brackets/admin-auth::admin.activation-form.title') }}</h1>
                                    <p class="auth-subtitle">{{ trans('brackets/admin-auth::admin.activation-form.note') }}</p>
                                </div>
                                <div class="auth-body">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @if ($errors->has('email') || $errors->has('token'))
                                        <div class="alert alert-danger">
                                            @if ($errors->has('token'))
                                                {{ $errors->first('token') }}<br/>
                                            @endif
                                            @if ($errors->has('email'))
                                                {{ $errors->first('email') }}<br/>
                                            @endif
                                        </div>
                                    @endif
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
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="remember" value="1">
                                        <button type="submit" class="btn btn-primary btn-block btn-spinner"><i
                                                    class="fa"></i> {{ trans('brackets/admin-auth::admin.activation-form.button') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </auth-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
