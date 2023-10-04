@can('access-admin')
    @include('layouts.sidebars.admin_sidebar')
@endcan
@can('access-dokter')
    @include('layouts.sidebars.spesialistik_sidebar')
@endcan