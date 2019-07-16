<div class="leftbar">
  <div class="avatar">
    <a class="avatar__image" href="admin/cabinet">
      <img src='{{ Config::get('admin.avatar_path')."/thumb/".Auth::user()->avatar }}' title="avatar" alt="avatar" />
    </a>
    <div class="avatar__name">{{ Auth::user()->name }}</div>
    <div class="avatar__level">{{ Auth::user()->level}}</div>
  </div>
  <nav class="admin-nav">

  @if ( in_array(Auth::id(), Config::get('admin.admin_ids'))) {
    <ul class="admin-nav__list">
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "settings") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/settings">@lang('admin::main.navigation.settings')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "seo") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/seo">@lang('admin::main.navigation.seo')</a>
      </li>
      @if(class_exists("Demos\Banners\BannersServiceProvider"))
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "banners") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/banners">@lang('admin::main.navigation.banners')</a>
      </li>
      @endif

      @if(class_exists("Demos\Units\UnitsServiceProvider"))
      <li class="admin-nav__item {{ (isset($alias_for_menu) && ($alias_for_menu == "categories" || $alias_for_menu == "list" || $alias_for_menu == "attrs") ) ? "active" : "" }}">
        <div class="admin-nav__link {{ (isset($alias_for_menu) && ($alias_for_menu == "categories" || $alias_for_menu == "list" || $alias_for_menu == "attrs") ) ? "active" : "" }}">
          @lang('admin::main.navigation.units')
          <div class="admin-nav__icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 444.819 444.819" style="enable-background:new 0 0 444.819 444.819;" xml:space="preserve">
								<g>
									<path d="M434.252,114.203l-21.409-21.416c-7.419-7.04-16.084-10.561-25.975-10.561c-10.095,0-18.657,3.521-25.7,10.561
									L222.41,231.549L83.653,92.791c-7.042-7.04-15.606-10.561-25.697-10.561c-9.896,0-18.559,3.521-25.979,10.561l-21.128,21.416
									C3.615,121.436,0,130.099,0,140.188c0,10.277,3.619,18.842,10.848,25.693l185.864,185.865c6.855,7.23,15.416,10.848,25.697,10.848
									c10.088,0,18.75-3.617,25.977-10.848l185.865-185.865c7.043-7.044,10.567-15.608,10.567-25.693
									C444.819,130.287,441.295,121.629,434.252,114.203z"></path>
								</g>
							</svg>
          </div>
        </div>
        <ul class="admin-nav__inside {{ (isset($alias_for_menu) && ($alias_for_menu == "categories" || $alias_for_menu == "list" || $alias_for_menu == "attrs") ) ? "active" : "" }}">
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && $alias_for_menu == "categories" ) ? "active" : "" }}">
            <a class="admin-nav__inside-link " href="{{ route('admin.units.showCategories') }}">@lang('units::main.cats_list')</a>
          </li>
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && $alias_for_menu == "list" ) ? "active" : "" }}">
            <a class="admin-nav__inside-link" href="{{ route('admin.units.showUnits') }}">@lang('units::main.units_list')</a>
          </li>
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && $alias_for_menu == "attrs" ) ? "active" : "" }}">
            <a class="admin-nav__inside-link" href="{{ route('admin.units.showAttrTypes') }}">Атрибуты</a>
          </li>
        </ul>
      </li>
      @endif

      @if(class_exists("Demos\Slider\SliderServiceProvider"))
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "slider") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/slider">@lang('admin::main.navigation.slider')</a>
      </li>
      @endif
      @if(class_exists("Demos\Busshop\BusshopServiceProvider"))
      <li class="admin-nav__item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "actualize_images" || $alias_for_menu == "orders_list") ) ? "active" : "" }}">
        <div class="admin-nav__link {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "actualize_images" || $alias_for_menu == "orders_list") ) ? "active" : "" }}">
          @lang('admin::main.navigation.busshop')
          <div class="admin-nav__icon">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 444.819 444.819" style="enable-background:new 0 0 444.819 444.819;" xml:space="preserve">
                <g>
                  <path d="M434.252,114.203l-21.409-21.416c-7.419-7.04-16.084-10.561-25.975-10.561c-10.095,0-18.657,3.521-25.7,10.561
                  L222.41,231.549L83.653,92.791c-7.042-7.04-15.606-10.561-25.697-10.561c-9.896,0-18.559,3.521-25.979,10.561l-21.128,21.416
                  C3.615,121.436,0,130.099,0,140.188c0,10.277,3.619,18.842,10.848,25.693l185.864,185.865c6.855,7.23,15.416,10.848,25.697,10.848
                  c10.088,0,18.75-3.617,25.977-10.848l185.865-185.865c7.043-7.044,10.567-15.608,10.567-25.693
                  C444.819,130.287,441.295,121.629,434.252,114.203z"></path>
                </g>
              </svg>
          </div>
        </div>
        <ul class="admin-nav__inside {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "actualize_images" || $alias_for_menu == "orders_list") ) ? "active" : "" }}">
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "orders_list") ) ? "active" : "" }}">
            <a class="admin-nav__inside-link " href="{{ route('admin.busshop.ordersList') }}">@lang('busshop::main.orders_list')</a>
          </li>
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "orders_list") ) ? "active" : "" }}">
            <a class="admin-nav__inside-link " href="/admin/busshop/categories">Категории товаров</a>
          </li>
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "actualize_images") ) ? "active" : "" }}">
            <a class="admin-nav__inside-link" href="{{ route('admin.busshop.actualizeImages') }}">@lang('busshop::main.actualize_images')</a>
          </li>

          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "buggoods") ) ? "active" : "" }}">
            <a class="admin-nav__inside-link" href="{{ route('admin.busshop.buggoods') }}">@lang('busshop::main.buggoods')</a>
          </li>
          <li class="admin-nav__inside-item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "goodsnoimages") ) ? "active" : "" }}">
            <a class="admin-nav__inside-link" href="{{ route('admin.busshop.goodsnoimages') }}">@lang('busshop::main.goodsnoimages')</a>
          </li>


        </ul>
      </li>
      @endif
      @if(class_exists("Demos\Reviews\ReviewsServiceProvider"))
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "reviews") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/reviews">@lang('admin::main.navigation.reviews')</a>
      </li>
      @endif
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "users") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/users/list">@lang('admin::main.navigation.users')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "cabinet") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/cabinet">@lang('admin::main.navigation.cabinet')</a>
      </li>
    </ul>
  @else
    <ul class="admin-nav__list">
      <li class="admin-nav__item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "orders_list") ) ? "active" : "" }}">
        <a class="admin-nav__link " href="/admin/busshop/categories">Категории товаров</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "list" ) ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/units/list/2">Разобранные автомобили</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "categories" ) ? "active" : "" }}">
        <a class="admin-nav__link" href="{{ route('admin.units.showCategories') }}">@lang('units::main.cats_list')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "orders_list") ? "active" : "" }}">
        <a class="admin-nav__link" href="{{ route('admin.busshop.ordersList') }}">@lang('busshop::main.orders_list')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "buggoods")  ? "active" : "" }}">
        <a class="admin-nav__link" href="{{ route('admin.busshop.buggoods') }}">@lang('busshop::main.buggoods')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && ($alias_for_menu == "busshop" || $alias_for_menu == "actualize_images") ) ? "active" : "" }}">
        <a class="admin-nav__link" href="{{ route('admin.busshop.actualizeImages') }}">@lang('busshop::main.actualize_images')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "goodsnoimages")  ? "active" : "" }}">
        <a class="admin-nav__link" href="{{ route('admin.busshop.goodsnoimages') }}">@lang('busshop::main.goodsnoimages')</a>
      </li>

      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "reviews") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/reviews">@lang('admin::main.navigation.reviews')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "users") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/users/list">@lang('admin::main.navigation.users')</a>
      </li>
      <li class="admin-nav__item {{ (isset($alias_for_menu) && $alias_for_menu == "cabinet") ? "active" : "" }}">
        <a class="admin-nav__link" href="admin/cabinet">@lang('admin::main.navigation.cabinet')</a>
      </li>
    </ul>
  @endif
  </nav>
  @if(isset($site_contacts['phone']))
    @foreach($site_contacts['phone'] AS $phone)
      @if( ($phone->img != '' || is_file(Config::get('admin.img_path')."/".$phone->img)))
         <p> <img class='contact_ico' src='{{ Config::get('admin.img_path')."/".$phone->img}} ' /> {{ $phone->lang->name or "" }} {{ $phone->value or "" }} </p>
      @else
        <p> {{ $phone->lang->name or "" }} {{ $phone->value or "" }} </p>
      @endif
    @endforeach
  @endif
  @if(isset($site_contacts['address']))
    @foreach($site_contacts['address'] AS $phone)
      @if( ($phone->img != '' || is_file(Config::get('admin.img_path')."/".$phone->img)))
         <p> <img class='contact_ico' src='{{ Config::get('admin.img_path')."/".$phone->img}} ' /> {{ $phone->lang->name or "" }} {{ $phone->lang->value or "" }} </p>
      @else
        <p> {{ $phone->lang->name or "" }} {{ $phone->lang->value or "" }} </p>
      @endif
    @endforeach
  @endif
</div>
