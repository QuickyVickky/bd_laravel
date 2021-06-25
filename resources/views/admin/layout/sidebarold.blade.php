<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
  <nav id="sidebar">
    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories" id="accordionExample">
      <?php $getAllMenus = getAllMenus(); ?>
      
      @foreach($getAllMenus as $menu)
      @if($menu->level==0 && $menu->has_submenu==0 && $menu->main_url!='')
      @if(Session::get("adminrole")=="A" || ( in_array($menu->id, Session::get("admin_assigned_ids")) && Session::get("adminrole")!="A" ) )
      <?php 
		$ifouccurs = 0; 
		foreach($getAllMenus as $submenu){
			if($submenu->main_url!='' && $submenu->main_url==Route::currentRouteName() && $ifouccurs==0 && $submenu->path_to==$menu->id ){
				$ifouccurs++;
			}
		}
		?>
      <li class="menu"> <a href="#{{ $menu->main_url.$menu->id }}" data-toggle="collapse" data-active="{{ ($ifouccurs>0) ? 'true' : 'false' }}" aria-expanded="false" class="dropdown-toggle">
        <div class=""> {!! $menu->any_svg !!} <span>{{ $menu->name }}</span> </div>
        <div> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
          <polyline points="9 18 15 12 9 6"></polyline>
          </svg> </div>
        </a>
        
        <ul class="collapse submenu list-unstyled  {{ ($ifouccurs>0) ? 'show' : '' }}" id="{{ $menu->main_url.$menu->id }}" data-parent="#accordionExample">
          @foreach($getAllMenus as $submenu)
          @if($submenu->level==1 && $submenu->main_url!='' && $submenu->path_to==$menu->id)
          @if(Session::get("adminrole")=="A" || ( in_array($submenu->id, Session::get("admin_assigned_ids")) && Session::get("adminrole")!="A" ) )
          <li class="{{ (request()->is(env("ADMINBASE_NAME").'/'.$submenu->main_url)) ? 'active' : '' }}"> <a href="{{ route($submenu->main_url) }}" > {{ $submenu->name }} </a> </li>
          @endif
          @endif
          @endforeach
        </ul>
      </li>
      @endif
      @elseif($menu->level==0 && $menu->has_submenu==1 && $menu->main_url!='')
      @if(Session::get("adminrole")=="A" || ( in_array($menu->id, Session::get("admin_assigned_ids")) && Session::get("adminrole")!="A" ) )
      <li class="menu"> <a href="{{ route($menu->main_url) }}" aria-expanded="{{ (request()->is(env("ADMINBASE_NAME").'/'.$menu->main_url)) ? 'true' : 'false' }}" class="dropdown-toggle">
        <div class=""> {!! $menu->any_svg !!} <span>{{ $menu->name }}</span> </div>
        </a> </li>
        @endif
      @endif
      @endforeach
    </ul>

    
  </nav>
</div>
<!--  END SIDEBAR  --> 