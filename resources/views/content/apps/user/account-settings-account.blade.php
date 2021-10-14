@extends('layouts/contentLayoutMaster')

@section('title', 'Account')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel='stylesheet' href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
    <link rel='stylesheet' href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
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
    <?php
    $permission = ((auth()->user()->can('user_edit') || auth()->user()->hasRole('super-admin')) ? '' : true);
    ?>
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-2">
                <!-- Account -->
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('user.account',['id'=>$user->id])}}">
                        <i data-feather="user" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">{{__('locale.Account')}}</span>
                    </a>
                </li>
                <!-- security -->
                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.security')}}">
                        <i data-feather="key" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">{{__('locale.Security')}}</span>
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

            <!-- profile -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">{{__('locale.Profile Of')}} {{$user->firstname.' '.$user->lastname}}</h4>
                </div>
                <div class="card-body">
                    <form class="needs-validation" action="{{route('user.update',['id'=>$user->id])}}"
                          method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="media mb-2">
                            <img
                                    src="{{asset('/images/users/'.$user->image)}}"
                                    alt="users avatar"
                                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer"
                                    height="90"
                                    width="90"
                            />
                            <div class="media-body mt-50">
                                <h4>{{$user->firstname.' '.$user->lastname}}</h4>
                                <div class="col-12 d-flex mt-1 px-0">
                                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                                        <span class="d-none d-sm-block">{{__('locale.Change')}}</span>
                                        <input
                                                class="form-control"
                                                type="file"
                                                id="change-picture"
                                                hidden
                                                name="image"
                                                accept="image/png, image/jpeg, image/jpg"
                                        />
                                        <span class="d-block d-sm-none">
                    <i class="mr-0" data-feather="edit"></i>
                  </span>
                                    </label>
                                    <button class="btn btn-outline-secondary d-none d-sm-block">{{__('locale.Remove')}}</button>
                                    <button class="btn btn-outline-secondary d-block d-sm-none">
                                        <i class="mr-0" data-feather="trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-1">
                                <x-ImputForm
                                        title="firstname"
                                        class="form-control"
                                        :value="$user->firstname"
                                        type="text"
                                        :required="true"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <x-ImputForm
                                        title="lastname"
                                        class="form-control"
                                        :value="$user->lastname"
                                        type="text"
                                        :required="true"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <x-ImputForm
                                        title="email"
                                        class="form-control"
                                        :value="$user->email"
                                        type="email"
                                        :required="true"
                                        :readonly="$permission"
                                />
                            </div>

                            <div class="col-12 col-sm-6 mb-1">
                                <x-SelectForm
                                        title="sex"
                                        class="form-group"
                                        values=""
                                        :defoult="$user->sex"
                                        :required="true"
                                        type="sex"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <x-ImputForm
                                        title="phone"
                                        class="form-control"
                                        :value="$user->phone"
                                        type="number"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <x-ImputForm
                                        title="extension"
                                        class="form-control"
                                        :value="$user->extension"
                                        type="number"
                                />
                            </div>
                            @hasanyrole('super-admin')
                            <div class="col-12 col-sm-6 mb-1">
                                <x-SelectForm
                                        title="acl"
                                        class="form-group"
                                        :values="$roles"
                                        :defoult="$userRole[0]"
                                        :required="true"
                                />
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <x-SelectForm
                                        title="status"
                                        class="form-group"
                                        values=""
                                        :defoult="$user->status"
                                        :required="true"
                                        type="status"
                                />
                            </div>
                            @endhasallroles
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                <button type="submit"
                                        class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Save Changes')}}</button>
                                <button type="reset"
                                        class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/pages/page-account-settings-account.js')) }}"></script>
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
