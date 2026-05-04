<aside id="aside" class="ui-aside">
    <ul class="nav" ui-nav>
      <li class="nav-head">
          <h5 class="nav-title text-uppercase light-txt">Navigation</h5>
      </li>
      <li>
          <a href="#"><i class="fa fa-server"></i><span>Maklumat Aduan</span><i class="fa fa-angle-right pull-right"></i></a>
          <ul class="nav nav-sub">
            <li class="nav-sub-header"><a href="#"><span>Maklumat Aduan</span></a></li>
            <li><a href="{{ route('new_complaint') }}"><span>Aduan Baru</span></a></li>
            <li><a href="{{ route('home') }}"><span>Senarai Aduan</span></a></li> 
          </ul>
      </li>
      <li>
        <a target="_blank" href="{{ asset('user_manuals/Manual_Pengguna.pdf') }}"><i class="fa fa-book"></i><span>Manual Pengguna</span></a>
      </li>
      <li>
        <a href="{{ route('faq_list') }}"><i class="fa fa-question"></i><span>Soalan Lazim</span></a>
      </li>
    </ul>
</aside>