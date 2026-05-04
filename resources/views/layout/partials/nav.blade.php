<aside id="aside" class="ui-aside">
    <ul class="nav" ui-nav>
        <li class="nav-head">
            <h5 class="nav-title text-uppercase light-txt">Navigation</h5>
        </li>
        
        <li>
            <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i><span> Dashboard </span></a>
        </li>

        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
        <li>
            <a href="#"><i class="fa fa-cog"></i><span>Pengurusan Sistem</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href="#"><span>Pengurusan Sistem</span></a></li>
                <li><a href="{{ route('user') }}"><span>Pengguna</span></a></li>
                <li><a href="{{ route('pembekal') }}"><span>Pembekal</span></a></li>
                <li><a href="{{ route('category') }}"><span>Kategori</span></a></li>
                <li><a href="{{ route('sub-category') }}"><span>Sub-Kategori</span></a></li>
                <li><a href="{{ route('detail') }}"><span>Perincian</span></a></li>
                <li><a href="{{ route('faq') }}"><span>Soalan Lazim</span></a></li>
                <li><a href="{{ route('holidays') }}"><span>Cuti Umum</span></a></li>
            </ul>
        </li>
        @endif

        @if(Auth::user()->role_id != 1)
        <li>
            <a href="#"><i class="fa fa-server"></i><span>Maklumat Aduan</span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href="#"><span>Maklumat Aduan</span></a></li>
                @if(Auth::user()->role_id != 5)
                <li><a href="{{ route('ontask') }}"><span>Dalam Tindakan <small class="label label-danger">{{ App\Helper\Utilities::totalOnTask() }}</small></span></a></li>
                @endif
                <li><a href="{{ route('complaintlist') }}"><span>Senarai Aduan @if(Auth::user()->role_id == 2) <small class="label label-danger">{{ App\Helper\Utilities::totalNew() }}</small> @endif</span></a></li>
                @if(Auth::user()->role_id == 2)
                <li><a href="{{ route('meetinglist') }}"><span>Senarai Mesyuarat <small class="label label-danger">{{ App\Helper\Utilities::totalMeeting() }}</small></span></a></li>
                @endif
                <li><a href="{{ route('newcomplaint') }}"><span>Aduan Baru</span></a></li>
                <li><a href="{{ route('archive') }}"><span>Arkib</span></a></li>
            </ul>
        </li>
        @endif

        @if(Auth::user()->role_id == 2 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
        {{-- <li>
            <a href="{{ route('task') }}"><i class="fa fa-users"></i><span> Senarai Pelaksana </span></a>
        </li> --}}
        <li>
            <a href="#"><i class="fa fa-file-text-o"></i><span> Laporan </span><i class="fa fa-angle-right pull-right"></i></a>
            <ul class="nav nav-sub">
                <li class="nav-sub-header"><a href="#"><span>Laporan</span></a></li>
                <li><a href="{{ route('staff_detail') }}"><span>Perincian Pegawai Teknikal</span></a></li>
                <li><a href="{{ route('staff_kpi') }}"><span>Perincian Pegawai Mengikut KPI</span></a></li>
                <li><a href="{{ route('staff_stat') }}"><span>Statistik Tindakan Pegawai Teknikal</span></a></li>
                <li><a href="{{ route('category_report') }}"><span>Mengikut Kategori Aduan</span></a></li>
            </ul>
        </li>
        <!-- <li>
            <a href="{{ route('audit') }}"><i class="fa fa-cogs"></i><span> Jejak Audit </span></a>
        </li> -->
        @endif

    </ul>
</aside>