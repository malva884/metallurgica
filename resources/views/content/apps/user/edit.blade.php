@extends('layouts/contentLayoutMaster')

@section('title', __('locale.User manager'))

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
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
    <!-- users edit start -->
    <section class="app-user-edit">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a
                                class="nav-link d-flex align-items-center active"
                                id="account-tab"
                                data-toggle="tab"
                                href="#account"
                                aria-controls="account"
                                role="tab"
                                aria-selected="true"
                        >
                            <i data-feather="user"></i><span class="d-none d-sm-block">{{__('locale.Account')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                                class="nav-link d-flex align-items-center"
                                id="information-tab"
                                data-toggle="tab"
                                href="#information"
                                aria-controls="information"
                                role="tab"
                                aria-selected="false"
                        >
                            <i data-feather="info"></i><span
                                    class="d-none d-sm-block">{{__('locale.Linee manager')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                                class="nav-link d-flex align-items-center"
                                id="role-tab"
                                data-toggle="tab"
                                href="#role"
                                aria-controls="role"
                                role="tab"
                                aria-selected="false"
                        >
                            <i data-feather="lock"></i><span
                                    class="d-none d-sm-block">{{__('locale.Permissions manager')}}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                                class="nav-link d-flex align-items-center"
                                id="notify-tab"
                                data-toggle="tab"
                                href="#notify"
                                aria-controls="notify"
                                role="tab"
                                aria-selected="false"
                        >
                            <i data-feather="share-2"></i><span
                                    class="d-none d-sm-block">{{__('locale.Notify manager')}}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Account Tab starts -->
                    <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                        <div class="col-md-6 col-12">
                            <!-- users edit account form start -->
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
                                    <x-ImputForm
                                            title="email"
                                            class="form-control"
                                            :value="$user->email"
                                            type="email"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="firstname"
                                            class="form-control"
                                            :value="$user->firstname"
                                            type="text"
                                            :required="true"
                                    />
                                    <x-ImputForm
                                            title="lastname"
                                            class="form-control"
                                            :value="$user->lastname"
                                            type="text"
                                            :required="true"
                                    />
                                    <x-SelectForm
                                            title="sex"
                                            class="form-group"
                                            values=""
                                            :defoult="$user->sex"
                                            :required="true"
                                            type="sex"
                                    />
                                    <x-ImputForm
                                            title="phone"
                                            class="form-control"
                                            :value="$user->phone"
                                            type="number"
                                    />
                                    <x-ImputForm
                                            title="extension"
                                            class="form-control"
                                            :value="$user->extension"
                                            type="number"
                                    />


                                    <x-SelectForm
                                            title="acl"
                                            class="form-group"
                                            :values="$roles"
                                            :defoult="$userRole[0]"
                                            :required="true"
                                    />

                                    <x-SelectForm
                                            title="status"
                                            class="form-group"
                                            values=""
                                            :defoult="$user->status"
                                            :required="true"
                                            type="status"
                                    />
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
                        <!-- users edit account form ends -->
                    </div>
                    <!-- Account Tab ends -->

                    <!-- Information Tab starts -->
                    <div class="tab-pane" id="information" aria-labelledby="information-tab" role="tabpanel">
                        <!-- users edit Info form start -->

                        <!-- users edit Info form ends -->
                    </div>
                    <!-- Information Tab ends -->

                    <!-- role Tab starts -->
                    <div class="tab-pane" id="role" aria-labelledby="role-tab" role="tabpanel">
                        <!-- User Permissions Starts -->
                        <div class="col-md-12">
                            <!-- User Permissions -->
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{__('locale.Permissions manager')}}</h4>
                                </div>
                                <p class="card-text ml-2">{{__('locale.Permissions message')}}</p>
                                <div class="table-responsive">
                                    <form class="form-validate" action="{{route('permessi.upload',['id'=>$user->id])}}"
                                          method="post">
                                        @csrf
                                        <table class="table table-striped table-borderless">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>Module</th>
                                                @foreach($titoli  as $titolo)
                                                    <th>{{__('locale.'.ucwords($titolo))}}</th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach(\App\User::$modules as $module)
                                                <tr>
                                                    <td>{{__('locale.'.$module)}}</td>
                                                  @foreach($titoli  as $titolo)
                                                        @if(\App\User::getPermission($module, $titolo))
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                           name="{{$module.'_'.$titolo}}"
                                                                           id="{{$module.'_'.$titolo}}"
                                                                           {{(in_array($module.'_'.$titolo,$permessi_user) ? 'checked':'') }} {{(empty($permessi[$module.'_'.$titolo]) ? 'disabled':'')}})/>
                                                                    <label class="custom-control-label"
                                                                           for="{{$module.'_'.$titolo}}"></label>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td>
                                                            </td>
                                                        @endif

                                                  @endforeach
                                                </tr>
                                            @endforeach

                                            <!-- >
                      @foreach($all_permessi as $key=>$permessi)
                                                <tr>
                                                  <td>{{__('locale.'.$key)}}</td>
                        @foreach($titoli_permessi as $param => $titoli)
                                                    @if(in_array($key.'_'.$param,$all_permessi[$key]))
                                                        <td>
                                                          <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" name="{{$key.'_'.$param}}" id="{{$key.'_'.$param}}" {{(in_array($key.'_'.$param,$permessi_user) ? 'checked':'') }} {{(empty($permessi[$param]) ? 'disabled':'')}})  />
                                  <label class="custom-control-label" for="{{$key.'_'.$param}}"></label>
                                </div>
                              </td>
                            @else
                                                        <td>

                                                        </td>
@endif
                                                @endforeach
                                                        </tr>
@endforeach
                                                    < -->


                                            </tbody>
                                        </table>
                                        <div class="col-12 d-flex flex-sm-row flex-column mt-2">
                                            <button type="submit"
                                                    class="btn btn-primary mb-1 mb-sm-0 mr-0 mr-sm-1">{{__('locale.Save')}}</button>
                                            <button type="reset"
                                                    class="btn btn-outline-secondary">{{__('locale.Reset')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /User Permissions -->
                        </div>
                        <!-- User Permissions Ends -->
                    </div>
                    <!-- role Tab ends -->
                    <!-- Notify Tab starts -->
                    <div class="tab-pane" id="notify" aria-labelledby="notify-tab" role="tabpanel">
                        <!-- users edit notify form start -->

                        <!-- users edit social form ends -->
                    </div>
                    <!-- Social Tab ends -->
                </div>
            </div>
        </div>
    </section>
    <!-- users edit ends -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
    <script>
        (() => {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
