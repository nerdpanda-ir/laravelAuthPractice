@extends('layouts.dashboard')
@section('main')
    @printSystemMessages
    @if($admins->count()>0)
        @php
            $verta = verta();
        @endphp
        <!-- Table with panel -->
        <div class="card card-cascade narrower">

            <!--Card image-->
            <div
                class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">

                <div>
                    <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                        <i class="fas fa-th-large mt-0"></i>
                    </button>
                    <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                        <i class="fas fa-columns mt-0"></i>
                    </button>
                </div>

                <a href="" class="white-text mx-3"> ADMINS </a>

                <div>
                    <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                        <i class="fas fa-pencil-alt mt-0"></i>
                    </button>
                    <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                        <i class="far fa-trash-alt mt-0"></i>
                    </button>
                    <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2">
                        <i class="fas fa-info-circle mt-0"></i>
                    </button>
                </div>

            </div>
            <!--/Card image-->

            <div class="px-4">

                <div class="table-wrapper">
                    <!--Table-->
                    <table class="table table-hover mb-0">

                        <!--Table head-->
                        <thead>
                        {{--<tr>
                            <th class="th-lg">
                                #
                            </th>
                            <th class="th-lg">
                                image
                            </th>
                            <th class="th-lg">
                                user Name
                            </th>
                            <th class="th-lg">
                                active
                            </th>
                            <th class="th-lg">
                                status
                            </th>
                            <th class="th-lg">
                                nick
                            </th>
                            <th class="th-lg">
                                name
                            </th>
                            <th class="th-lg">
                                family
                            </th>
                            <th class="th-lg">
                                email
                            </th>
                            <th class="th-lg">
                                email_verified_at
                            </th>
                            <th class="th-lg">
                                phone
                            </th>
                            <th class="th-lg">
                                phone_verified_at
                            </th>
                            <th class="th-lg">
                                created_At
                            </th>
                            <th class="th-lg">
                                updated_at
                            </th>
                            <th class="th-lg">
                                last log in
                            </th>
                            <th class="th-lg">
                                action
                            </th>
                        </tr>--}}
                        <tr>
                            @foreach($columns as $column)
                                <th class="th-lg">
                                    {{$column}}
                                </th>
                            @endforeach
                                <th class="th-lg">
                                   actions
                                </th>
                        </tr>
                        </thead>
                        <!--Table head-->

                        <!--Table body-->
                        <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                @php
                                    $attributes = $admin->getAttributes();
                                @endphp
                                @foreach($attributes as $attrKey=>$attribute)
                                    @if($attrKey=='id')
                                        @continue
                                    @endif
                                    <td>
                                    @switch($attrKey)
                                        @case('#')
                                        {!! $loop->parent->index+1 !!}
                                        @break
                                        @case('emailVerifyDate')
                                        @case('phoneVerifyDate')
                                        @case('createDate')
                                        @case('updateDate')
                                        @case('last_log_in')
                                            @if(!is_null($attribute))
                                                {!! $attribute !!}
                                                <hr>
                                                @php
                                                    $verta->setTimestamp(strtotime($attribute))
                                                @endphp
                                                {{$verta->format('Y-m-d H:i:s')}}
                                                <hr>
                                                {{$verta->formatDifference()}}
                                            @else
                                                --------------
                                            @endif
                                        @break;

                                        @default
                                        {!!$attribute !!}
                                        @break
                                    @endswitch
                                @endforeach
                                <td>

                                    <a href="{{route('admin.management.edit',[$admin->id])}}" type="button" class="actionBtn  btn-floating btn-lg btn-tw"><i class="fas fa-user-edit"></i></a>
                                    <form method="post" action="{!! route('admin.management.destroy',[$admin->id]) !!}">
                                        @csrf
                                        @method('DELETE')
                                            <button type="submit" class="actionBtn btn-floating btn-lg btn-yt btn-danger"><i class="fa-light fa-eraser"></i></button>
                                    </form>
                                    <form action="{!! route('admin.management.delete',[$admin->id]) !!}" method="post" >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="actionBtn  btn-floating btn-lg btn-yt ripe-malinka-gradient"><i class="fa-light fa-trash-can-undo"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <!--Table body-->
                    </table>
                    <!--Table-->
                </div>

            </div>

        </div>
        <!-- Table with panel -->
    @else
        <section class="alert alert-danger rtl text-center">
            <h3>
                هیچ ایتمی یافت نشد !!
            </h3>
        </section>
    @endif
    <br><br>
    {{$admins->links('pagination::bootstrap-4')}}
@endsection
@push('css')
    <link rel="stylesheet" href="{!! asset('assets/nerdPanda/css/pages/admin/management/core.css') !!}">
@endpush
