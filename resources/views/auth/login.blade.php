@extends('app')
@section('title', 'Login')
@section('content')
    <div class="login-form align-self-center h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-lg-5 col-md-9 col-xs-12 align-self-center">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('login.custom') }}" autocomplete="off">
                                @csrf
                                <div class="form-group mb-3 w-75 m-auto">
                                    <input type="text" placeholder="Email" id="email" class="form-control" name="email"
                                        required
                                        autofocus>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3 w-75 m-auto">
                                    <input type="password" placeholder="Password" id="password" class="form-control"
                                        name="password" required>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="d-grid mx-auto w-75 m-auto">
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
