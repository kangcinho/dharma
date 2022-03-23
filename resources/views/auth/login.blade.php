@extends('master.master.masterPage')
@section('pageTitle','Login DhaRMa')
@section('content')
    <div class="ui middle aligned center aligned grid">
        <div class="row">
            <div class="eight wide column">
                <form class="ui large form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="ui stacked segment">
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="user icon"></i>
                                <input type="text" name="username" placeholder="Username" id="username">
                            </div>
                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                                <i class="lock icon"></i>
                                <input type="password" name="password" placeholder="Password" id="password">
                            </div>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="field">
                            <div class="ui left icon input">
                            <div class="ui checkbox">
                                <input type="checkbox" tabindex="0" class="hidden" name="remember" id="remember">
                                <label for="remember">{{ __('Remember Me') }}</label>
                            </div>
                            </div>
                        </div>
                        <button class="ui fluid large teal submit button" type="submit">Login</button>
                    </div>          
                </form>
            </div>
        </div>
    </div>
</div>
@endsection()