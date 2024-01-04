<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link {{ Request::is('teacher/home') ? null : 'collapsed' }}" href="/">
      <i class="bi bi-grid"></i>
      <span>Bosh sahifa</span>
    </a>
  </li><!-- End Dashboard Nav -->

  <li class="nav-item">
    <a class="nav-link {{ (Request::is('teacher/alltests') or Request::is('teacher/page_creating_test') )? null : 'collapsed' }}" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-question-circle"></i><span>Testlar</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-nav" class="nav-content collapse {{ (Request::is('teacher/alltests') or Request::is('teacher/page_creating_test') )? 'show' : null }} " data-bs-parent="#sidebar-nav">
      <li>
        <a href="/teacher/page_creating_test" class="{{ Request::is('teacher/page_creating_test') ? 'active' : null }}">
          <i class="bi bi-circle"></i><span>Test yaratish</span>
        </a>
      </li>
      <li>
        <a href="/teacher/alltests" class="{{  Request::is('teacher/alltests') ? 'active' : null }}">
          <i class="bi bi-circle"></i><span>Barcha testlar</span>
        </a>
      </li>
    </ul>
  </li><!-- End Components Nav -->

  <li class="nav-item">
    <a class="nav-link {{ (Request::is('teacher/page_create_topic') or Request::is('teacher/alltopics'))? null : 'collapsed' }}" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-layout-text-window-reverse"></i><span>Mavzular</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="tables-nav" class="nav-content collapse {{ (Request::is('teacher/page_create_topic') or Request::is('teacher/alltopics'))? 'show' : null }}" data-bs-parent="#sidebar-nav">
      <li>
        <a href="/teacher/page_create_topic" class="{{ Request::is('teacher/page_create_topic') ? 'active' : null }}">
          <i class="bi bi-circle"></i><span>Mavzu yaratish</span>
        </a>
      </li>
      <li>
        <a href="/teacher/alltopics" class="{{  Request::is('teacher/alltopics') ? 'active' : null }}">
          <i class="bi bi-circle"></i><span>Barcha mavzular</span>
        </a>
      </li>
    </ul>
  </li><!-- End Tables Nav -->
  
</ul>

</aside><!-- End Sidebar-->