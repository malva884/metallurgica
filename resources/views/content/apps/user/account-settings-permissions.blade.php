@extends('layouts/contentLayoutMaster')

@section('title', 'Permissions')

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
                    <a class="nav-link" href="{{route('user.account',['id'=>$user->id])}}">
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

            <!-- notifications -->
            <form class="form-validate" action="{{route('permessi.upload',['id'=>$user->id])}}"
                  method="post">
                @csrf
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="card-title">{{__('locale.Permissions User')}} - {{$user->firstname.' '.$user->lastname}}</h4>
                    </div>
                    <div class="card-body pt-2">
                        <h5 class="mb-0">
                            - <strong>Request permission</strong>
                        </h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap text-center border-bottom">
                            <thead>
                            <tr>
                                <th class="text-start">{{__('locale.Permissions')}}</th>
                                @foreach(\App\User::$modules as $module)
                                    <th class="text-start">{{__('locale.'.$module)}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($titoli  as $titolo)

                                <tr>
                                    <th>{{__('locale.'.ucwords($titolo))}}</th>
                                    @foreach(\App\User::$modules as $module)
                                        @if(\App\User::getPermission($module, $titolo))
                                            <td>
                                                <div class="form-check d-flex justify-content-center">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           id="{{$module.'_'.$titolo}}"
                                                           name="{{$module.'_'.$titolo}}"
                                                           {{(in_array($module.'_'.$titolo,$permessi_user) ? 'checked':'') }}
                                                           {{(empty($permessi[$module.'_'.$titolo]) ? 'disabled':'')}})/>
                                                </div>
                                            </td>
                                        @else
                                            <td>
                                            </td>
                                        @endif

                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body mt-50">

                        <div class="row gy-2">
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-1">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Discard</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            <!--/ notifications -->

        </div>
    </div>
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

<style>
    .form-check-input {

        color-adjust: exact !important;
        height: 1.285rem !important;
        margin-top: .0825rem !important;
        width: 1.285rem !important;
    }

    .form-check-input:checked {
        #background-color: #7367f0 !important;
        #border-color: #7367f0 !important;
    }
</style>