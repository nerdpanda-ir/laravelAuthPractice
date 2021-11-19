@extends('layouts.mdb')
@section('body')
    <x-layouts.dashboard.header />
    <x-layouts.dashboard.body />
    <x-layouts.dashboard.footer />
@endsection
@push('bodyAttrs')
    class="fixed-sn white-skin"
@endpush
@push('css')
    <style>
    .card.card-cascade.narrower
    {
            width: fit-content !important;
    }
    </style>
@endpush
@section('jsFooter')
    @parent
    <!--Custom scripts-->
    <script>
        // SideNav Initialization
        $(".button-collapse").sideNav();

        var container = document.querySelector('.custom-scrollbar');
        var ps = new PerfectScrollbar(container, {
            wheelSpeed: 2,
            wheelPropagation: true,
            minScrollbarLength: 20
        });

    </script>
@endsection
