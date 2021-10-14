@extends('layouts/contentLayoutMaster')

@section('title', 'Security')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
    @if($message = Session::get('success'))
        <x-alert
                :message="$message" color="success"
        />
        {!! Session::forget('success') !!}
    @elseif($message = Session::get('error'))
        <x-alert
                :message="$message" color="danger"
        />
        {!! Session::forget('error') !!}
    @endif
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-2">
                <!-- Account -->
                <li class="nav-item">
                    <a class="nav-link " href="{{route('user.account')}}">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Account</span>
                    </a>
                </li>
                <!-- security -->
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('user.security')}}">
                        <i data-feather="key" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Security</span>
                    </a>
                </li>
            @if(auth()->user()->can('user_permission') || auth()->user()->hasanyrole('super-admin'))
                <!-- permission -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.permissions',['id'=>$user->id])}}">
                            <i data-feather="lock" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">{{__('locale.Permissions')}}</span>
                        </a>
                    </li>
                @endif
            </ul>

            <!-- security -->

            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Change Password - {{$user->firstname.' '.$user->lastname}}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{route('user.change.password',['id'=>$user->id])}}"
                          method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label"
                                       for="account-old-password">{{__('locale.Current password')}}</label>
                                <div class="input-group form-password-toggle input-group-merge">
                                    <input
                                            type="password"
                                            class="form-control"
                                            id="account-old-password"
                                            name="password"
                                            placeholder="Enter current password"
                                            data-msg="Please current password"
                                    />
                                    <div class="input-group-text cursor-pointer">
                                        <i data-feather="eye"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label"
                                       for="account-new-password">{{__('locale.New Password')}}</label>
                                <div class="input-group form-password-toggle input-group-merge">
                                    <input
                                            type="password"
                                            id="account-new-password"
                                            name="new_password"
                                            class="form-control"
                                            placeholder="Enter new password"
                                    />
                                    <div class="input-group-text cursor-pointer">
                                        <i data-feather="eye"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label"
                                       for="account-retype-new-password">{{__('locale.Retype New Password')}}</label>
                                <div class="input-group form-password-toggle input-group-merge">
                                    <input
                                            type="password"
                                            class="form-control"
                                            id="account-retype-new-password"
                                            name="confirm_new_password"
                                            placeholder="Confirm your new password"
                                    />
                                    <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <p class="fw-bolder">Password requirements:</p>
                                <ul class="ps-1 ms-25">
                                    <li class="mb-50">Minimum 8 characters long - the more, the better</li>
                                    <li class="mb-50">At least one lowercase character</li>
                                    <li>At least one number, symbol, or whitespace character</li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <button type="submit"
                                        class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Changes Pasword')}}</button>
                                <button type="reset"
                                        class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ security -->
            <!-- deactivate account  -->
            <div class="card">
                <div class="card-body py-2 my-25">
                    <form id="formAccountDeactivation" class="validate-form" onsubmit="return false">

                    </form>
                </div>
            </div>
            <!--/ profile -->

        </div>
    </div>


@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/pages/page-account-settings-security.js')) }}"></script>
    <script>
        (() => {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.validate-form');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach((form) => {

                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        console.log(form);
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
