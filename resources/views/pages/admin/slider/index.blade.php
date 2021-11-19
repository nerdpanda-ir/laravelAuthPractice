@extends('layouts.dashboard')
@section('main')
    @printSystemMessages
    @if($sliders->isEmpty())
        <div class="alert alert-danger rtl text-lg-center" role="alert">
            هیچ اسلایدی یافت نشد !!!
            <a href="{!! route('admin.slider.create') !!}" class="alert-link">ایجاد اسلاید</a>
        </div>
    @else
        <!-- Table with panel -->
        <div class="card card-cascade narrower" style="overflow: scroll">

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

                <a href="" class="white-text mx-3">Sliders</a>

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
                        <tr>
                            @foreach($columns as $column)
                                @if($column=='uri_title' xor $column=='deleted_at')
                                    @continue
                                @endif
                                <th class="th-lg">
                                    @switch($column)
                                        @case('id')
                                        #
                                <th>
                                    actions
                                </th>
                                @break
                                @case('user_id')
                                user
                                @break
                                @case('created_at')
                                @case('updated_at')
                                @case('deleted_at')
                                {!! substr( $column,0,strrpos($column,'_'))!!}
                                @break
                                @default
                                {!! $column !!}
                                @endswitch
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <!--Table head-->

                        <!--Table body-->
                        <tbody>
                        @foreach($sliders->items() as $slider)
                            <tr>
                                @php($informations = $slider->getAttributes())
                                @foreach($informations as $key=>$info)
                                    @if($key=='uri_title'  xor $key=='deleted_at')
                                        @continue
                                    @endif
                                    <td>
                                    @switch($key)
                                        @case('id')
                                        {!! $loop->parent->index+1 !!}
                                        <td>
                                            <a href="{!! route('admin.slider.edit',[$slider->id]) !!}" type="button"
                                               class="btn-floating blue-gradient"><i
                                                    class="fa-light fa-pen-to-square"></i></a>
                                            <form action="{!! route('admin.slider.destroy',[$slider->id]) !!}"
                                                  method="post">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn-floating  deleteBtn"><i
                                                        class="fa-light fa-trash-plus"></i></button>
                                            </form>
                                        </td>
                                        @break
                                        @case('image')
                                        <img src="{!! asset($info) !!}" alt="" class="sliderImg">
                                        @break
                                        @case('uri')
                                        <a href="{{$info}}"> {{ $slider->uri_title ?? 'click' }} </a>
                                        @break
                                        @case('active')
                                        @if($info)
                                            <i class="fa-thin fa-check text-success display-4"></i>
                                        @else
                                            <i class="fa-thin fa-cancel text-danger display-4"></i>
                                            @endif
                                            @break
                                            @case('created_at')
                                            @case('updated_at')
                                            @if(!is_null($info))
                                                {!! $info !!}
                                            <hr>
                                                @php($timeStamp = strtotime($info))
                                                @php($verta = verta()->setTimestamp($timeStamp))
                                                {!! $verta !!}
                                            <hr>
                                                {!! $verta->formatDifference()!!}
                                                    @else
                                                    -
                                                @endif
                                            @break
                                            @default
                                            {!! $info !!}
                                            @break
                                            @endswitch

                                            </td>

                                            @endforeach
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
        <br>
        {{$sliders->links('pagination::bootstrap-4')}}
    @endif

@endsection
@push('css')
    <style>
        .sliderImg {
            width: 100px;
            height: 100px;
        }

        .deleteBtn {
            background: #e52d27; /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #b31217, #e52d27); /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #b31217, #e52d27); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            margin-top: 10px !important;
        }
    </style>
@endpush

