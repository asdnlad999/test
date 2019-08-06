<div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fe fe-home"></i> 首页</a>
                    </li>
                    @if (auth()->user()->is_admin)
                        <li class="nav-item">
                            <a href="/backend/user" class="nav-link"><i class="fe fe-users"></i> 账号管理</a>
                        </li>
                        <li class="nav-item">
                            <a href="/backend/domain" class="nav-link"><i class="fa fa-internet-explorer"></i> 域名管理</a>
                        </li>
                        <li class="nav-item">
                            <a href="/backend/promotion" class="nav-link"><i class="fe fe-send"></i> 推广管理</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="/backend/ad" class="nav-link"><i class="fe fe-layers"></i> 广告管理</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
