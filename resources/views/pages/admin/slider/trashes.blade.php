@extends('layouts.dashboard')
@section('main')
    @printSystemMessages
    <br><br><br>
    @php
    /** @var \Illuminate\Pagination\LengthAwarePaginator $sliders*/
    @endphp
    @if( $sliders->currentPage()>$sliders->lastPage() or $sliders->count()===0)
        <section class="alert alert-danger rtl text-center">
            <h4>در این صفحه هیچ اطلاعاتی موجود نیست !!! </h4>
        </section>
    @else
        @php
            $verta = new \Hekmatinasser\Verta\Verta();
            $dateFormat = 'Y/m/d H:i:s';
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

                <a href="" class="white-text mx-3"> Trash Sliders </a>

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
                            @foreach( $columns as $column)
                                <th class="th-lg">
                                    <a>
                                        {!! $column !!}
                                    </a>
                                </th>
                                @if($column=='#')
                                    <th> actions</th>
                                @endif
                            @endforeach
                        </tr>
                        </thead>
                        <!--Table head-->

                        <!--Table body-->
                        <tbody>
                        @foreach($sliders->items() as $index=>$item)
                            <tr>
                                <td> {{$index + 1 }} </td>
                                <td>
                                    <a href="{!! route('admin.slider.restore',[$item->getAttribute('id')]) !!}" type="button" class=" btn btn-primary px-3"><i
                                            class="fa-solid fa-trash-can-arrow-up" aria-hidden="true"></i></a>
                                    <form method="post" action="{!! route('admin.slider.destroy',[$item->getAttribute('id')]) !!}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger px-3"><i
                                                class="fa-solid fa-trash-can-xmark " aria-hidden="true"></i></button>
                                    </form>
                                </td>
                                @foreach($item->getAttributes() as $key=>$attribute)
                                    @if($key=='id' xor $key=='#')
                                        @continue
                                    @elseif(in_array($key,['CreatedAt','UpdatedAt','DeletedAt']))
                                        <td>

                                            @if(is_null($attribute))
                                                ------
                                            @else
                                                {!!  $attribute !!}
                                                <hr>
                                                @php
                                                    $timeStamp = strtotime($attribute);
                                                    $verta->setTimestamp($timeStamp);
                                                @endphp
                                                {!! $verta  !!}
                                                <hr>
                                                {!! $verta->formatDifference() !!}

                                            @endif
                                        </td>
                                    @else
                                        <td>{!! $attribute !!}</td>
                                    @endif

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
    @endif
    <br><br>
    {!! $sliders->links('pagination::bootstrap-4') !!}
@endsection
@push('css')
    <style>
        table img {
            width: 100px;
            height: 100px;
        }

        td i {
            font-size: 20px;
        }

    </style>
@endpush
