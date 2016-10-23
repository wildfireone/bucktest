<?php
//Set domain here as not all pages include the database file (for some reason...)
$domain='http://bucktest.dev/';


//Show edit link for each section if the url is on the correct viewing page
/*function showEditLink($domain,$view, $edit, $parm)
{
    if($_SERVER['PHP_SELF'] === $view) {
        $id = $_GET[$parm];
        $link = $domain . $edit. '?' . $parm . '=' . $id;

        echo '<li><a href="'.$link.'" role="link">Edit</a></li>';
    }
}*/

?>
<header>
   <a href="<?php echo $domain ?>index.php" class="nohover">
      <div class="banner">
         <h1 class="middle centre">Bucksburn Amateur Swimming Club</h1>
      </div>
   </a>
<?php
   if (isset($_SESSION["username"])) {
      echo '<div class="contain-to-grid header-section">
      <nav role="navigation" class="top-bar important-class" data-topbar>
         <div class="top-bar-section">
            <ul>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'galas.php" role="link">Galas</a> 
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'upcoming-galas.php" role="link">Upcoming Galas</a></li>
                     <li><a href="'. $domain . 'gala-results.php" role="link">Gala Results</a></li>                
                     <li><a href="'. $domain . 'galas.php" role="link">View All</a></li>
                     <li><a href="'. $domain . 'galas/create.php" role="link">Create</a></li>
                     ';
                    showEditLink($domain, '/galas/view.php','galas/edit.php', 'id',$_SESSION["username"],$connection,$memberValidation);
                    echo'
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'members.php" role="link">Members</a> 
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'members.php" role="link">View All</a></li>
                     <li><a href="'. $domain . 'members/create.php" role="link">Create</a></li>';
                     showEditLink($domain, '/members/view.php','members/edit.php', 'u',$_SESSION["username"],$connection,$memberValidation);
                echo '</ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'squads.php" role="link">Squads</a>
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'squads.php" role="link">View All</a></li>
                     <li><a href="'. $domain . 'squads/create.php" role="link">Create</a></li>';
                      showEditLink($domain, '/squads/view.php','squads/edit.php', 'id',$_SESSION["username"],$connection,$memberValidation);
                  echo'</ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'venues.php" role="link">Venues</a>
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'venues.php" role="link">View All</a></li>
                     <li><a href="'. $domain . 'venues/create.php" role="link">Create</a></li>';
                     showEditLink($domain, '/venues/view.php','venues/edit.php', 'id',$_SESSION["username"],$connection,$memberValidation);
                  echo'</ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'news.php" role="link">News</a>
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'news.php" role="link">View All</a></li>
                     <li><a href="'. $domain . 'news/create.php" role="link">Create</a></li>';
                     showEditLink($domain, '/news/view.php','news/edit.php', 'id',$_SESSION["username"],$connection, $memberValidation);
                  echo'</ul>
               </li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="'. $domain . 'gallery.php" role="link">Gallery</a></li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'shop.php" role="link">Shop</a>
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'shop.php" role="link">View All</a></li>
                     <li><a href="'. $domain . 'shop/create.php" role="link">Create</a></li>';
                     showEditLink($domain, '/shop/view.php','shop/edit.php', 'id', $_SESSION["username"],$connection, $memberValidation);
                  echo '</ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'about.php" role="link">About</a>
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'about.php" role="link">About BASC</a></li>
                     <li><a href="'. $domain . 'about/committee.php" role="link">Committee</a></li>
                     <li><a href="'. $domain . 'about/timetable.php" role="link">Timetable</a></li>
                     <li><a href="'. $domain . 'about/club-records.php" role="link">Club Records</a></li>
                     <li><a href="'. $domain . 'about/join.php" role="link">Join Us</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li><a href="'. $domain . 'contact.php" role="link">Contact</a></li>
               <li class="divider"></li>
               <li><a href="'. $domain . 'member-area.php">Member Area</a></li>
               <li class="divider"></li>
               <li class="has-form"> <a href="'. $domain . 'logout.php" class="small button">Logout</a> </li>
               <li class="divider"></li>
            </ul>
         </div>
      </nav>
   </div>
   <div class="header-fill"></div>';
   } else {
      echo '<div class="contain-to-grid header-section">
      <nav role="navigation" class="top-bar important-class" data-topbar>
         <div class="top-bar-section">
            <ul class="middle">
               <li class="divider"></li>
               <li class="lightgradient"><a href="'. $domain . 'upcoming-galas.php" role="link">Upcoming Galas</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="'. $domain . 'gala-results.php" role="link">Gala Results</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="'. $domain . 'news.php" role="link">News</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="'. $domain . 'gallery.php" role="link">Gallery</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="'. $domain . 'shop.php" role="link">Shop</a></li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="'. $domain . 'about.php" role="link">About</a>
                  <ul class="dropdown">
                     <li><a href="'. $domain . 'about.php" role="link">About BASC</a></li>
                     <li><a href="'. $domain . 'about/committee.php" role="link">Committee</a></li>
                     <li><a href="'. $domain . 'about/timetable.php" role="link">Timetable</a></li>
                     <li><a href="'. $domain . 'about/club-records.php" role="link">Club Records</a></li>
                     <li><a href="'. $domain . 'about/join.php" role="link">Join Us</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li><a href="'. $domain . 'contact.php" role="link">Contact</a></li>
               <li class="divider"></li>
               <li class="has-form"> <a href="'. $domain . 'login.php" class="small button">Login</a> </li>
               <li class="divider"></li>
            </ul>
         </div>
      </nav>
   </div>';
   }
?>
</header>