@extends('layouts.dashboard')
@section('main')
    @printSystemMessages
    @if($items->isEmpty())
        <section class="alert alert-danger rtl text-center mb-5">
            <h3>
                سطل اشغال خالی میباشد !!!
            </h3>
        </section>
    @else
        @php
            $verta = new \Hekmatinasser\Verta\Verta();
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

                <a href="" class="white-text mx-3"> trashes </a>

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
                                @if($column=='id')
                                    @continue
                                @endif
                                <th class="th-lg">
                                    {!! $column !!}
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
                        @foreach($items as $item)
                            @php
                                $attrs = $item->getAttributes();
                            @endphp
                            <tr>
                                @foreach($attrs as $attrKey => $attr)
                                    @if($attrKey=='id')
                                        @continue
                                    @endif
                                    <td>
                                        @switch($attrKey)
                                            @case('#')
                                                {!! $loop->parent->index + 1  !!}
                                            @break
                                            @case('createDate')
                                            @case('updateDate')
                                            @case('emailVerifyDate')
                                            @case('phoneVerifyDate')
                                            @case('deletedAt')
                                                @if(is_null($attr))
                                                    ------------
                                                @else
                                                    {{$attr}}
                                                    <hr>
                                                    @php
                                                        $verta->setTimestamp(strtotime($attr))
                                                    @endphp
                                                    {{ $verta->formatJalaliDatetime() }}
                                                    <hr>
                                                    {!! $verta->formatDifference() !!}
                                                @endif
                                            @break
                                            @default
                                                {!! $attr !!}
                                            @break
                                        @endswitch

                                    </td>
                                @endforeach
                                <td>
                                    <a href="{!! route('admin.management.restore',[$item->getAttribute('id')]) !!}" class="btn-floating btn-lg btn-tw actionBtn"><i class="fa-light fa-trash-can-arrow-up"></i></a>
                                    <form method="post" action="{!! route('admin.management.destroy',[$item->id]) !!}">
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit" class="btn-floating btn-lg btn-yt actionBtn"><i class="fa-light fa-eraser"></i></button>
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
    @endif
    {{ $items->links('pagination::bootstrap-4') }}
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset('assets/nerdPanda/css/pages/admin/management/core.css')}}">
@endsection
