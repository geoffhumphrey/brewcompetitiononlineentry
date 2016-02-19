<?php if (($logged_in) && ($_SESSION['userLevel'] <= 1) && ($section == "admin")) { ?>


<script>
(function(e){function n(b){e(b).on("click"+p,this.toggle)}function q(b,a){if(g){a||(a=[g]);var c;g[0]!==a[0][0]?c=g:(c=a[a.length-1],c.parent().hasClass(k)&&(c=c.parent()));c.find("."+f).removeClass(f);c.hasClass(f)&&c.removeClass(f);c===g&&(g=null,e(l).remove())}}function r(b){var a=b.attr("data-target");a||(a=(a=b.attr("href"))&&/#[A-Za-z]/.test(a)&&a.replace(/.*(?=#[^\s]*$)/,""));return(a=a&&e(a))&&a.length?a:b.parent()}var l=".dropdown-backdrop",k="dropdown-menu",p=".bs.dropdown",f="open",s="ontouchstart"in
document.documentElement,g,h=n.prototype;h.toggle=function(b){var a=e(this);if(!a.is(".disabled, :disabled")){var a=r(a),c=a.hasClass(f),d;if(a.hasClass("dropdown-submenu")){for(var m=[a];!d||d.hasClass("dropdown-submenu");)d=(d||a).parent(),d.hasClass(k)&&(d=d.parent()),d.children('[data-toggle="dropdown"]')&&m.unshift(d);d=m}else d=null;q(b,d);if(!c){d||(d=[a]);if(s&&!a.closest(".navbar-nav").length&&!d[0].find(l).length)e('<div class="'+l.substr(1)+'"/>').appendTo(d[0]).on("click",q);b=0;for(a=
d.length;b<a;b++)d[b].hasClass(f)||(d[b].addClass(f),c=d[b].children("."+k),m=d[b],c.hasClass("pull-center")&&c.css("margin-right",c.outerWidth()/-2),c.hasClass("pull-middle")&&c.css("margin-top",c.outerHeight()/-2-m.outerHeight()/2));g=d[0]}return!1}};h.keydown=function(b){if(/(38|40|27)/.test(b.keyCode)){var a=e(this);b.preventDefault();b.stopPropagation();if(!a.is(".disabled, :disabled")){var c=r(a),d=c.hasClass("open");if(!d||d&&27==b.keyCode)return 27==b.which&&c.find('[data-toggle="dropdown"]').trigger("focus"),
a.trigger("click");a=c.find('li:not(.divider):visible > input:not(disabled) ~ label, [role="menu"] li:not(.divider):visible a, [role="listbox"] li:not(.divider):visible a');a.length&&(c=a.index(a.filter(":focus")),38==b.keyCode&&0<c&&c--,40==b.keyCode&&c<a.length-1&&c++,~c||(c=0),a.eq(c).trigger("focus"))}}};h.change=function(b){var a,c="";a=e(this).closest("."+k);(b=a.parent().find("[data-label-placement]"))&&b.length||(b=a.parent().find('[data-toggle="dropdown"]'));b&&b.length&&!1!==b.data("placeholder")&&
(void 0==b.data("placeholder")&&b.data("placeholder",e.trim(b.text())),c=e.data(b[0],"placeholder"),a=a.find("li > input:checked"),a.length&&(c=[],a.each(function(){var a=e(this).parent().find("label").eq(0),b=a.find(".data-label");b.length&&(a=e("<p></p>"),a.append(b.clone()));(a=a.html())&&c.push(e.trim(a))}),c=4>c.length?c.join(", "):c.length+" selected"),a=b.find(".caret"),b.html(c||"&nbsp;"),a.length&&b.append(" ")&&a.appendTo(b))};var t=e.fn.dropdown;e.fn.dropdown=function(b){return this.each(function(){var a=
e(this),c=a.data("bs.dropdown");c||a.data("bs.dropdown",c=new n(this));"string"==typeof b&&c[b].call(a)})};e.fn.dropdown.Constructor=n;e.fn.dropdown.clearMenus=function(b){e(l).remove();e("."+f+' [data-toggle="dropdown"]').each(function(){var a=r(e(this)),c={relatedTarget:this};a.hasClass("open")&&(a.trigger(b=e.Event("hide"+p,c)),b.isDefaultPrevented()||a.removeClass("open").trigger("hidden"+p,c))});return this};e.fn.dropdown.noConflict=function(){e.fn.dropdown=t;return this};e(document).off(".bs.dropdown.data-api").on("click.bs.dropdown.data-api",
q).on("click.bs.dropdown.data-api",'[data-toggle="dropdown"]',h.toggle).on("click.bs.dropdown.data-api",'.dropdown-menu > li > input[type="checkbox"] ~ label, .dropdown-menu > li > input[type="checkbox"], .dropdown-menu.noclose > li',function(b){b.stopPropagation()}).on("change.bs.dropdown.data-api",'.dropdown-menu > li > input[type="checkbox"], .dropdown-menu > li > input[type="radio"]',h.change).on("keydown.bs.dropdown.data-api",'[data-toggle="dropdown"], [role="menu"], [role="listbox"]',h.keydown)})(jQuery);
</script>

<style>
.dropdown-menu > li > label {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: normal;
  line-height: 1.42857143;
  color: #333333;
  white-space: nowrap;
}
.dropdown-menu > li > label:hover,
.dropdown-menu > li > label:focus {
  text-decoration: none;
  color: #262626;
  background-color: #f5f5f5;
}
.dropdown-menu > li > input:checked ~ label,
.dropdown-menu > li > input:checked ~ label:hover,
.dropdown-menu > li > input:checked ~ label:focus,
.dropdown-menu > .active > label,
.dropdown-menu > .active > label:hover,
.dropdown-menu > .active > label:focus {
  color: #ffffff;
  text-decoration: none;
  outline: 0;
  background-color: #428bca;
}
.dropdown-menu > li > input[disabled] ~ label,
.dropdown-menu > li > input[disabled] ~ label:hover,
.dropdown-menu > li > input[disabled] ~ label:focus,
.dropdown-menu > .disabled > label,
.dropdown-menu > .disabled > label:hover,
.dropdown-menu > .disabled > label:focus {
  color: #999999;
}
.dropdown-menu > li > input[disabled] ~ label:hover,
.dropdown-menu > li > input[disabled] ~ label:focus,
.dropdown-menu > .disabled > label:hover,
.dropdown-menu > .disabled > label:focus {
  text-decoration: none;
  background-color: transparent;
  background-image: none;
  filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
  cursor: not-allowed;
}
.dropdown-menu > li > label {
  margin-bottom: 0;
  cursor: pointer;
}
.dropdown-menu > li > input[type="radio"],
.dropdown-menu > li > input[type="checkbox"] {
  display: none;
  position: absolute;
  top: -9999em;
  left: -9999em;
}
.dropdown-menu > li > label:focus,
.dropdown-menu > li > input:focus ~ label {
  outline: thin dotted;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.dropdown-menu.pull-right {
  right: 0;
  left: auto;
}
.dropdown-menu.pull-top {
  bottom: 100%;
  top: auto;
  margin: 0 0 2px;
  -webkit-box-shadow: 0 -6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 -6px 12px rgba(0, 0, 0, 0.175);
}
.dropdown-menu.pull-center {
  right: 50%;
  left: auto;
}
.dropdown-menu.pull-middle {
  right: 100%;
  margin: 0 2px 0 0;
  box-shadow: -5px 0 10px rgba(0, 0, 0, 0.2);
  left: auto;
}
.dropdown-menu.pull-middle.pull-right {
  right: auto;
  left: 100%;
  margin: 0 0 0 2px;
  box-shadow: 5px 0 10px rgba(0, 0, 0, 0.2);
}
.dropdown-menu.pull-middle.pull-center {
  right: 50%;
  margin: 0;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}
.dropdown-menu.bullet {
  margin-top: 8px;
}
.dropdown-menu.bullet:before {
  width: 0;
  height: 0;
  content: '';
  display: inline-block;
  position: absolute;
  border-color: transparent;
  border-style: solid;
  -webkit-transform: rotate(360deg);
  border-width: 0 7px 7px;
  border-bottom-color: #cccccc;
  border-bottom-color: rgba(0, 0, 0, 0.15);
  top: -7px;
  left: 9px;
}
.dropdown-menu.bullet:after {
  width: 0;
  height: 0;
  content: '';
  display: inline-block;
  position: absolute;
  border-color: transparent;
  border-style: solid;
  -webkit-transform: rotate(360deg);
  border-width: 0 6px 6px;
  border-bottom-color: #ffffff;
  top: -6px;
  left: 10px;
}
.dropdown-menu.bullet.pull-right:before {
  left: auto;
  right: 9px;
}
.dropdown-menu.bullet.pull-right:after {
  left: auto;
  right: 10px;
}
.dropdown-menu.bullet.pull-top {
  margin-top: 0;
  margin-bottom: 8px;
}
.dropdown-menu.bullet.pull-top:before {
  top: auto;
  bottom: -7px;
  border-bottom-width: 0;
  border-top-width: 7px;
  border-top-color: #cccccc;
  border-top-color: rgba(0, 0, 0, 0.15);
}
.dropdown-menu.bullet.pull-top:after {
  top: auto;
  bottom: -6px;
  border-bottom: none;
  border-top-width: 6px;
  border-top-color: #ffffff;
}
.dropdown-menu.bullet.pull-center:before {
  left: auto;
  right: 50%;
  margin-right: -7px;
}
.dropdown-menu.bullet.pull-center:after {
  left: auto;
  right: 50%;
  margin-right: -6px;
}
.dropdown-menu.bullet.pull-middle {
  margin-right: 8px;
}
.dropdown-menu.bullet.pull-middle:before {
  top: 50%;
  left: 100%;
  right: auto;
  margin-top: -7px;
  border-right-width: 0;
  border-bottom-color: transparent;
  border-top-width: 7px;
  border-left-color: #cccccc;
  border-left-color: rgba(0, 0, 0, 0.15);
}
.dropdown-menu.bullet.pull-middle:after {
  top: 50%;
  left: 100%;
  right: auto;
  margin-top: -6px;
  border-right-width: 0;
  border-bottom-color: transparent;
  border-top-width: 6px;
  border-left-color: #ffffff;
}
.dropdown-menu.bullet.pull-middle.pull-right {
  margin-right: 0;
  margin-left: 8px;
}
.dropdown-menu.bullet.pull-middle.pull-right:before {
  left: -7px;
  border-left-width: 0;
  border-right-width: 7px;
  border-right-color: #cccccc;
  border-right-color: rgba(0, 0, 0, 0.15);
}
.dropdown-menu.bullet.pull-middle.pull-right:after {
  left: -6px;
  border-left-width: 0;
  border-right-width: 6px;
  border-right-color: #ffffff;
}
.dropdown-menu.bullet.pull-middle.pull-center {
  margin-left: 0;
  margin-right: 0;
}
.dropdown-menu.bullet.pull-middle.pull-center:before {
  border: none;
  display: none;
}
.dropdown-menu.bullet.pull-middle.pull-center:after {
  border: none;
  display: none;
}
.dropdown-submenu {
  position: relative;
}
.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -6px;
  margin-left: -1px;
  border-top-left-radius: 0;
}
.dropdown-submenu > a:before {
  display: block;
  float: right;
  width: 0;
  height: 0;
  content: "";
  margin-top: 6px;
  margin-right: -8px;
  border-width: 4px 0 4px 4px;
  border-style: solid;
  border-left-style: dashed;
  border-top-color: transparent;
  border-bottom-color: transparent;
}
@media (max-width: 767px) {
  .navbar-nav .dropdown-submenu > a:before {
    margin-top: 8px;
    border-color: inherit;
    border-style: solid;
    border-width: 4px 4px 0;
    border-left-color: transparent;
    border-right-color: transparent;
  }
  .navbar-nav .dropdown-submenu > a {
    padding-left: 40px;
  }
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > a,
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > label {
    padding-left: 35px;
  }
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > a,
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > label {
    padding-left: 45px;
  }
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > a,
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > label {
    padding-left: 55px;
  }
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > a,
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > label {
    padding-left: 65px;
  }
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > a,
  .navbar-nav > .open > .dropdown-menu > .dropdown-submenu > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > .dropdown-menu > li > label {
    padding-left: 75px;
  }
}
.navbar-default .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a,
.navbar-default .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a:hover,
.navbar-default .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a:focus {
  background-color: #e7e7e7;
  color: #555555;
}
@media (max-width: 767px) {
  .navbar-default .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a:before {
    border-top-color: #555555;
  }
}
.navbar-inverse .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a,
.navbar-inverse .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a:hover,
.navbar-inverse .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a:focus {
  background-color: #080808;
  color: #ffffff;
}
@media (max-width: 767px) {
  .navbar-inverse .navbar-nav .open > .dropdown-menu > .dropdown-submenu.open > a:before {
    border-top-color: #ffffff;
  }
}

/*
.dropdown-submenu{position:relative;}
.dropdown-submenu>.dropdown-menu{top:0;left:-95%;max-width:180px;margin-top:-6px;margin-right:-1px;-webkit-border-radius:6px 6px 6px 6px;-moz-border-radius:6px 6px 6px 6px;border-radius:6px 6px 6px 6px;}
.dropdown-submenu:hover>.dropdown-menu{display:block;}
.dropdown-submenu>a:after{display:block;content:" ";float:left;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 5px 5px 0;border-right-color:#999;margin-top:5px;margin-right:10px;}
.dropdown-submenu:hover>a:after{border-left-color:#ffffff;}
.dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 6px 6px 6px;-moz-border-radius:6px 6px 6px 6px;border-radius:6px 6px 6px 6px;}
.dropdown-menu-right {margin-left:0;}
*/
</style>





 <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="fa fa-dashboard"></span> <span class="caret"></span></a>
              <ul class="dropdown-menu pull-right" role="menu">
              		<li><a href="<?php echo $base_url."index.php?section=admin"; ?>" tabindex="-1">Admin Dashboard</a></li>
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Preferences</a>
                    	<ul class="dropdown-menu pull-right">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=preferences"; ?>" tabindex="-1">Global</a></li>
                        			<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging_preferences"; ?>" tabindex="-1">Organization</a></li>
                    			
                        </ul>
                	</li>
                    <!-- End Defining Preferences Sub-Menu -->
                    <!-- Preparing Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Competition Preparation</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles"; ?>" tabindex="-1">Accepted Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best"; ?>" tabindex="-1">Custom Category Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types&amp;action=add"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles&amp;action=add"; ?>" tabindex="-1">Custom Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best&amp;action=add"; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts&amp;action=add"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff&amp;action=add"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors&amp;action=add"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Edit</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contest_info"; ?>" tabindex="-1">Competition Info</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Upload</a>
                            	<ul class="dropdown-menu">
                                	<!-- The following will should be changed to utilize a "smart" upload function -->
                              		<li><a href="<?php echo $base_url."admin/upload.admin.php"; ?>" tabindex="-1">Competition Logo</a></li>
                                    <li><a href="<?php echo $base_url."admin/upload.admin.php"; ?>" tabindex="-1">Sponsor Logos</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Preparing Sub-Menu -->
                    <!-- Entry and Data Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Accepting Entries</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles"; ?>" tabindex="-1">Accepted Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best"; ?>" tabindex="-1">Custom Category Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants&amp;filter=judges"; ?>" tabindex="-1">Available Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards"; ?>" tabindex="-1">Available Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types&amp;action=add"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles&amp;action=add"; ?>" tabindex="-1">Custom Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best&amp;action=add"; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts&amp;action=add"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff&amp;action=add"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors&amp;action=add"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Assign</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges"; ?>" tabindex="-1">Participants as Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards"; ?>" tabindex="-1">Participants as Stewards</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Entry and Data Sub-Menu -->
                    <!-- Sorting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Sorting Entries</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Check In</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=checkin"; ?>" tabindex="-1">Bar-Coded Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Print</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/sorting.php?section=admin&amp;go=default&amp;filter=default"; ?>" tabindex="-1">Sorting Sheets</a></li>
                                    <li><a href="<?php echo $base_url."output/sorting.php?section=admin&amp;go=cheat&amp;filter=default"; ?>" tabindex="-1">Entry/Judging Number Cheat Sheet</a></li>
                                    <li><a href="<?php echo $base_url."output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default"; ?>" tabindex="-1">Bottle Labels (Entry Numbers)</a></li>
                                    <li><a href="<?php echo $base_url."output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default"; ?>" tabindex="-1">Bottle Labels (Judging Numbers)</a>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Sorting Sub-Menu -->
                    <!-- Organizing Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Organizing</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judging Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Styles Not Assigned to Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Assigned Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Assigned Stewards</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">A Judging Table</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Assign</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participants as Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participants as Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Tables to Rounds</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judges or Stewards to a Table</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Best of Show Judges</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Organizing Sub-Menu -->
                    <!-- Scoring Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Scoring</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judging Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Scores by Table</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Scores by Category</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Best of Show Entries/Places</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Custom Category Entries</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Scoring Sub-Menu -->
                    <!-- Printing and Reporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Printing and Reporting</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Before Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Entry Totals by Drop-Off Location</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">List of Entries by Drop-Off Location</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Pullsheets</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Table Cards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judge Assignments</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Steward Assignments</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judge Sign In Sheet</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Steward Sign In Sheet</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Name Tags</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">During Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Pullsheets</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Cup Mats</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">After Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Results Report with Scores</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Results Report without Scores</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Results Report</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BJCP Points Report</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participant Address Labels</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participant Summaries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Post-Judging Inventory</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Printing Sub-Menu -->
                    <!-- Exporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Exporting</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Email CSV</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/email_export.php"; ?>" tabindex="-1">All Participants</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_judges&amp;action=email"; ?>" tabindex="-1">Available Judges</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_stewards&amp;action=email"; ?>" tabindex="-1">Available Stewards</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email"; ?>" tabindex="-1">Assigned Judges</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email"; ?>" tabindex="-1">Assigned Stewards</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=staff&amp;action=email"; ?>" tabindex="-1">Assigned Staff</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners"; ?>" tabindex="-1">Winners</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;action=email"; ?>" tabindex="-1">All Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email&amp;view=all"; ?>" tabindex="-1">All Paid Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email"; ?>" tabindex="-1">All Paid &amp; Received Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email&amp;view=all"; ?>" tabindex="-1">All Non-Paid Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email"; ?>" tabindex="-1">All Non-Paid &amp; Received Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Data CSV</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;action=all&amp;filter=all"; ?>" tabindex="-1">All Entries (All Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv"; ?>" tabindex="-1">All Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;view=all"; ?>" tabindex="-1">All Paid Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid"; ?>" tabindex="-1">All Paid &amp; Received Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;view=all"; ?>" tabindex="-1">All Non-Paid Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay"; ?>" tabindex="-1">All Non-Paid &amp; Received Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/participants_export.php?section=admin&amp;go=csv"; ?>" tabindex="-1">All Participants</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners"; ?>" tabindex="-1">Winners</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Promo Materials</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=html&amp;action=html"; ?>" tabindex="-1">HTML</a></li>
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=word&amp;action=word"; ?>" tabindex="-1">Word</a></li>
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=word&amp;action=bbcode"; ?>" tabindex="-1">Bulletin Board Code (BBC)</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Exporting Sub-Menu -->
                    <!-- Exporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Archiving</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=archives"; ?>" tabindex="-1">Archives</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Exporting Sub-Menu -->
              	</ul>
            </li>
            <!-- END Admin Menu -->
            



	<!-- Load Off-Canvas Menu JS for Admin -->
    <!-- Based upon Theme Armada's code at http://blog.themearmada.com/off-canvas-slide-menu-for-bootstrap/ -->
    <script src="https://cdn.jsdelivr.net/jquery.navgoco/0.1.3/jquery.navgoco.min.js"></script>
	<script>$(document).ready(function(){$("#nav-expander").on("click",function(n){n.preventDefault(),$("body").toggleClass("nav-expanded")}),$("#nav-close").on("click",function(n){n.preventDefault(),$("body").removeClass("nav-expanded")}),$(".main-menu").navgoco({caret:'<span class="caret"></span>',accordion:!0,openClass:"open",save:!0,cookie:{name:"navgoco",expires:!1,path:"/"},slide:{duration:300,easing:"swing"}})});</script>
	<nav>
		<ul class="list-unstyled main-menu">
			<li class="text-right"><a href="#" id="nav-close"><span class="fa fa-remove"></span></a></li>
			<li><a href="<?php echo $link_admin; ?>">Admin Dashboard <span class="icon"></span></a></li>
			<li><a href="#">Competition Preparation</a>
			<ul class="list-unstyled">
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info">Edit Competition Info <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Manage Contacts <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Manage Custom Categories <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Manage Drop-Off Locations <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Manage Judging Locations <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Manage Sponsors <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Manage Styles Accepted <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Manage Style Types <span class="icon"></span></a></li>
			</ul>
			</li>
			<li><a href="#">Entries and Participants</a>
			<ul class="list-unstyled">
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage Entries <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Manage Participants <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Assign Judges <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Assign Stewards <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick">Quick Register a Judge/Steward <span class="icon"></span></a></li>
			</ul>
			</li>
			<li><a href="#">Sorting</a>
			<ul class="list-unstyled">
				<li class="sub-nav"><a data-confirm="Are you sure you want to regenerate judging numbers for all entries?<?php if ($_SESSION['prefsEntryForm'] == "N") echo " This will over-write all judging numbeers, including those that have been assigned via the barcode scanning function."; ?>" href="<?php echo $base_url; ?>includes/process.inc.php?section=admin&amp;go=default&amp;action=generate_judging_numbers&amp;sort=id&amp;dir=ASC">Regenerate Judging Numbers <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manually <span class="icon"></span></a></li>
				<?php if ($_SESSION['prefsEntryForm'] == "N") { ?>
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">Via Barcode Scanner <span class="icon"></span></a></li>
			   <?php } ?>
			</ul>
			</li>
			<li><a href="#">Organizing</a>
			<ul class="list-unstyled">
				<li class="sub-nav"><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Manage Tables <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="#">Assign Judges/Stewards to Tables <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="#">Add BOS Judges <span class="icon"></span></a></li>
			</ul>
			</li>
			<li><a href="#">Scoring</a>
			<ul class="list-unstyled">
				<li class="sub-nav"><a href="#">Manage Scores <span class="icon"></span></a></li>
				<li class="sub-nav"><a href="#">Manage BOS Entries and Places <span class="icon"></span></a></li>
			</ul>
			</li>
			
			<li><a href="#">Menu Four <span class="icon"></span></a></li>
			<li><a href="#">Menu Five <span class="icon"></span></a></li>
		</ul>
	</nav>
<?php } ?>







<!-- Sub Menu Dropdowns http://behigh.github.io/bootstrap_dropdowns_enhancement/ -->
            <!-- Begin Admin Dropdown Menu -->
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Admin <span class="caret"></span></a>
              <ul class="dropdown-menu pull-right" role="menu">
              		<li><a href="<?php echo $base_url."index.php?section=admin"; ?>" tabindex="-1">Admin Dashboard</a></li>
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Preferences</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Define</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=preferences"; ?>" tabindex="-1">Global</a></li>
                        			<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging_preferences"; ?>" tabindex="-1">Organization</a></li>
                    			</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- End Defining Preferences Sub-Menu -->
                    <!-- Preparing Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Competition Preparation</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles"; ?>" tabindex="-1">Accepted Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best"; ?>" tabindex="-1">Custom Category Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types&amp;action=add"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles&amp;action=add"; ?>" tabindex="-1">Custom Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best&amp;action=add"; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts&amp;action=add"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff&amp;action=add"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors&amp;action=add"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Edit</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contest_info"; ?>" tabindex="-1">Competition Info</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Upload</a>
                            	<ul class="dropdown-menu">
                                	<!-- The following will should be changed to utilize a "smart" upload function -->
                              		<li><a href="<?php echo $base_url."admin/upload.admin.php"; ?>" tabindex="-1">Competition Logo</a></li>
                                    <li><a href="<?php echo $base_url."admin/upload.admin.php"; ?>" tabindex="-1">Sponsor Logos</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Preparing Sub-Menu -->
                    <!-- Entry and Data Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Accepting Entries</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles"; ?>" tabindex="-1">Accepted Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best"; ?>" tabindex="-1">Custom Category Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants&amp;filter=judges"; ?>" tabindex="-1">Available Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants&amp;filter=stewards"; ?>" tabindex="-1">Available Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=style_types&amp;action=add"; ?>" tabindex="-1">Style Types</a></li>
                              		<li><a href="<?php echo $base_url."index.php?section=admin&amp;go=styles&amp;action=add"; ?>" tabindex="-1">Custom Style Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=special_best&amp;action=add"; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judging&amp;action=add"; ?>" tabindex="-1">Judging Locations/Dates</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=contacts&amp;action=add"; ?>" tabindex="-1">Competition Contacts</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=dropoff&amp;action=add"; ?>" tabindex="-1">Drop-Off Locations</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=sponsors&amp;action=add"; ?>" tabindex="-1">Sponsors</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Assign</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=judges"; ?>" tabindex="-1">Participants as Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;action=assign&amp;go=judging&amp;filter=stewards"; ?>" tabindex="-1">Participants as Stewards</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Entry and Data Sub-Menu -->
                    <!-- Sorting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Sorting Entries</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Check In</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=checkin"; ?>" tabindex="-1">Bar-Coded Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Print</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/sorting.php?section=admin&amp;go=default&amp;filter=default"; ?>" tabindex="-1">Sorting Sheets</a></li>
                                    <li><a href="<?php echo $base_url."output/sorting.php?section=admin&amp;go=cheat&amp;filter=default"; ?>" tabindex="-1">Entry/Judging Number Cheat Sheet</a></li>
                                    <li><a href="<?php echo $base_url."output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-entry&amp;filter=default"; ?>" tabindex="-1">Bottle Labels (Entry Numbers)</a></li>
                                    <li><a href="<?php echo $base_url."output/labels.output.php?section=admin&amp;go=entries&amp;action=bottle-judging&amp;filter=default"; ?>" tabindex="-1">Bottle Labels (Judging Numbers)</a>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Sorting Sub-Menu -->
                    <!-- Organizing Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Organizing</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=participants"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judging Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Styles Not Assigned to Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Assigned Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Assigned Stewards</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Add</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=judge&amp;action=register"; ?>" tabindex="-1">Participants as Judges or Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">A Judging Table</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Assign</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participants as Judges</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participants as Stewards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Tables to Rounds</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judges or Stewards to a Table</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Best of Show Judges</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Organizing Sub-Menu -->
                    <!-- Scoring Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Scoring</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entrant&amp;action=register"; ?>" tabindex="-1">Participants</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=entries"; ?>" tabindex="-1">Entries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judging Tables</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Scores by Table</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Scores by Category</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Best of Show Entries/Places</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Custom Categories</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Custom Category Entries</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Scoring Sub-Menu -->
                    <!-- Printing and Reporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Printing and Reporting</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Before Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Entry Totals by Drop-Off Location</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">List of Entries by Drop-Off Location</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Pullsheets</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Table Cards</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judge Assignments</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Steward Assignments</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Judge Sign In Sheet</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Steward Sign In Sheet</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Name Tags</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">During Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Pullsheets</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Cup Mats</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">After Judging</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Results Report with Scores</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Results Report without Scores</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BOS Results Report</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">BJCP Points Report</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participant Address Labels</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Participant Summaries</a></li>
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go="; ?>" tabindex="-1">Post-Judging Inventory</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Printing Sub-Menu -->
                    <!-- Exporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Exporting</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Email CSV</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/email_export.php"; ?>" tabindex="-1">All Participants</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_judges&amp;action=email"; ?>" tabindex="-1">Available Judges</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=avail_stewards&amp;action=email"; ?>" tabindex="-1">Available Stewards</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=judges&amp;action=email"; ?>" tabindex="-1">Assigned Judges</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=stewards&amp;action=email"; ?>" tabindex="-1">Assigned Stewards</a></li>
                                    <li><a href="<?php echo $base_url."output/email_export.php?section=admin&amp;go=csv&amp;filter=staff&amp;action=email"; ?>" tabindex="-1">Assigned Staff</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners"; ?>" tabindex="-1">Winners</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;action=email"; ?>" tabindex="-1">All Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email&amp;view=all"; ?>" tabindex="-1">All Paid Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;action=email"; ?>" tabindex="-1">All Paid &amp; Received Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email&amp;view=all"; ?>" tabindex="-1">All Non-Paid Entries</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;action=email"; ?>" tabindex="-1">All Non-Paid &amp; Received Entries</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Data CSV</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;action=all&amp;filter=all"; ?>" tabindex="-1">All Entries (All Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv"; ?>" tabindex="-1">All Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid&amp;view=all"; ?>" tabindex="-1">All Paid Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=paid"; ?>" tabindex="-1">All Paid &amp; Received Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay&amp;view=all"; ?>" tabindex="-1">All Non-Paid Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=nopay"; ?>" tabindex="-1">All Non-Paid &amp; Received Entries (Limited Data)</a></li>
                                    <li><a href="<?php echo $base_url."output/participants_export.php?section=admin&amp;go=csv"; ?>" tabindex="-1">All Participants</a></li>
                                    <li><a href="<?php echo $base_url."output/entries_export.php?section=admin&amp;go=csv&amp;filter=winners"; ?>" tabindex="-1">Winners</a></li>
                            	</ul>
                          	</li>
                            <li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Promo Materials</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=html&amp;action=html"; ?>" tabindex="-1">HTML</a></li>
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=word&amp;action=word"; ?>" tabindex="-1">Word</a></li>
                                    <li><a href="<?php echo $base_url."output/promo_export.php?section=admin&amp;go=word&amp;action=bbcode"; ?>" tabindex="-1">Bulletin Board Code (BBC)</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Exporting Sub-Menu -->
                    <!-- Exporting Sub-Menu -->
                	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Archiving</a>
                    	<ul class="dropdown-menu">
                          	<li class="dropdown-submenu"><a href="#" tabindex="-1" data-toggle="dropdown">Manage/View</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="<?php echo $base_url."index.php?section=admin&amp;go=archives"; ?>" tabindex="-1">Archives</a></li>
                            	</ul>
                          	</li>
                        </ul>
                	</li>
                    <!-- END Exporting Sub-Menu -->
              	</ul>
            </li>
            <!-- END Admin Menu -->
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            <!-- Begin Admin Dropdown Menu -->
            
            
            <li class="dropdown">
                <a href="#" title="Admin" class="my-dropdown" data-toggle="dropdown" data-placement="bottom"><span class="fa fa-dashboard"></span> <span class="caret"></span></a>
                <ul class="dropdown-menu">
                	<li><a href="<?php echo $base_url; ?>index.php?section=admin">Admin Dashboard</a></li>
                    <!--
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contest_info">Edit Competition Info</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=contacts">Manage Contacts</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=special_best">Manage Custom Categories</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=dropoff">Manage Drop-Off Locations</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging">Manage Judging Locations</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=sponsors">Manage Sponsors</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=styles">Manage Styles Accepted</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=style_types">Manage Style Types</a></li>
                    -->
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Manage Entries</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=participants">Manage Participants</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=judges">Assign Judges</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging&amp;action=assign&amp;filter=stewards">Assign Stewards</a></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judge&amp;action=register&amp;view=quick">Quick Register a Judge/Steward</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=entries">Check-In Entries Manually</a></li>
                    <?php if ($_SESSION['prefsEntryForm'] == "N") { ?>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=checkin">Check-In Entries Barcode</a></li>
                   	<?php } ?>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?php echo $base_url; ?>index.php?section=admin&amp;go=judging_tables">Manage Tables</a></li>
                    <li><a href="#">Assign Judges/Stewards to Tables</a></li>
                    <li><a href="#">Add BOS Judges</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Manage Scores</a></li>
                    <li><a href="#">Manage BOS Entries and Places</a></li>
                </ul>
            </li>