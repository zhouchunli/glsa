		<!-- BEGIN SIDEBAR -->

		<div class="page-sidebar nav-collapse collapse">

			<!-- BEGIN SIDEBAR MENU -->        

			<ul class="page-sidebar-menu">

				<li>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

					<div class="sidebar-toggler hidden-phone"></div>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

				</li>

				<li>

					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->

						{{--<form class="sidebar-search">--}}

						{{--<div class="input-box">--}}

							{{--<a href="javascript:;" class="remove"></a>--}}

							{{--<input type="text" id = 'search_content' placeholder="请输入用户id或者用户名" />--}}

							{{--<input type="button" class="submit" value=" " />--}}

						{{--</div>--}}

					{{--</form>--}}

					<!-- END RESPONSIVE QUICK SEARCH FORM -->

				</li>

				<?php
				$menu = DB::table("menu")->where("lv","=","1")->get();
					foreach($menu as $item){
						if($item->url !== "#"){
							echo <<<eof
								<li class="start">
									<a href="$item->url">
										<i class="icon-briefcase"></i>
										<span class="title">$item->name</span>
									</a>
								</li>
eof;
						}else{
							$menu_sub = DB::table("menu")->where("group","=",$item->id)->get();
							echo <<<eof
				<li class="color1 color2">

					<a href="javascript:;">

					<i class="icon-briefcase"></i>

					<span class="title">$item->name</span>

					<span class="arrow"></span>

					</a>
					<ul class="sub-menu" style="display: none;">
eof;
							foreach($menu_sub as $value){
								echo <<<eof
						<li class="color1">

							<a href="$value->url">

							<i class="icon-circle-arrow-right"></i>

							$value->name</a>

						</li>
eof;
							}
							echo "</ul></li>";

						}
					}
					if(Session::get('user_id') == 88888888){
						echo <<<eof
								<li class="start">
									<a href="poweredit">
										<i class="icon-briefcase"></i>
										<span class="title">权限管理</span>
									</a>
								</li>
eof;

					}
				?>
			</ul>

			<!-- END SIDEBAR MENU -->

		</div>

		<!-- END SIDEBAR -->
