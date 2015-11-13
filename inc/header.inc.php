<header>
   <a href="http://localhost/basc_test/index.php" class="nohover">
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
                  <a href="http://localhost/basc_test/galas.php" role="link">Galas</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/upcoming-galas.php" role="link">Upcoming Galas</a></li>
                     <li><a href="http://localhost/basc_test/gala-results.php" role="link">Gala Results</a></li>                
                     <li><a href="http://localhost/basc_test/galas.php" role="link">View All</a></li>
                     <li><a href="http://localhost/basc_test/galas/create.php" role="link">Create</a></li>
                     <li><a href="http://localhost/basc_test/galas/edit.php" role="link">Edit</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/members.php" role="link">Members</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/members.php" role="link">View All</a></li>
                     <li><a href="http://localhost/basc_test/members/create.php" role="link">Create</a></li>
                     <li><a href="http://localhost/basc_test/members/edit.php" role="link">Edit</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/squads.php" role="link">Squads</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/squads.php" role="link">View All</a></li>
                     <li><a href="http://localhost/basc_test/squads/create.php" role="link">Create</a></li>
                     <li><a href="http://localhost/basc_test/squads/edit.php" role="link">Edit</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/venues.php" role="link">Venues</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/venues.php" role="link">View All</a></li>
                     <li><a href="http://localhost/basc_test/venues/create.php" role="link">Create</a></li>
                     <li><a href="http://localhost/basc_test/venues/edit.php" role="link">Edit</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/news.php" role="link">News</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/news.php" role="link">View All</a></li>
                     <li><a href="http://localhost/basc_test/news/create.php" role="link">Create</a></li>
                     <li><a href="http://localhost/basc_test/news/edit.php" role="link">Edit</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="http://localhost/basc_test/gallery.php" role="link">Gallery</a></li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/shop.php" role="link">Shop</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/shop.php" role="link">View All</a></li>
                     <li><a href="http://localhost/basc_test/shop/create.php" role="link">Create</a></li>
                     <li><a href="http://localhost/basc_test/shop/edit.php" role="link">Edit</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/about.php" role="link">About</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/about.php" role="link">About BASC</a></li>
                     <li><a href="http://localhost/basc_test/about/committee.php" role="link">Committee</a></li>
                     <li><a href="http://localhost/basc_test/about/timetable.php" role="link">Timetable</a></li>
                     <li><a href="http://localhost/basc_test/about/club-records.php" role="link">Club Records</a></li>
                     <li><a href="http://localhost/basc_test/about/join.php" role="link">Join Us</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li><a href="http://localhost/basc_test/contact.php" role="link">Contact</a></li>
               <li class="divider"></li>
               <li><a href="http://localhost/basc_test/member-area.php">Member Area</a></li> 
               <li class="divider"></li>
               <li class="has-form"> <a href="http://localhost/basc_test/logout.php" class="small button">Logout</a> </li> 
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
               <li class="lightgradient"><a href="http://localhost/basc_test/upcoming-galas.php" role="link">Upcoming Galas</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="http://localhost/basc_test/gala-results.php" role="link">Gala Results</a></li>                
               <li class="divider"></li>
               <li class="lightgradient"><a href="http://localhost/basc_test/news.php" role="link">News</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="http://localhost/basc_test/gallery.php" role="link">Gallery</a></li>
               <li class="divider"></li>
               <li class="lightgradient"><a href="http://localhost/basc_test/shop.php" role="link">Shop</a></li>
               <li class="divider"></li>
               <li class="has-dropdown not-click">
                  <a href="http://localhost/basc_test/about.php" role="link">About</a> 
                  <ul class="dropdown">
                     <li><a href="http://localhost/basc_test/about.php" role="link">About BASC</a></li>
                     <li><a href="http://localhost/basc_test/about/committee.php" role="link">Committee</a></li>
                     <li><a href="http://localhost/basc_test/about/timetable.php" role="link">Timetable</a></li>
                     <li><a href="http://localhost/basc_test/about/club-records.php" role="link">Club Records</a></li>
                     <li><a href="http://localhost/basc_test/about/join.php" role="link">Join Us</a></li>
                  </ul>
               </li>
               <li class="divider"></li>
               <li><a href="http://localhost/basc_test/contact.php" role="link">Contact</a></li>
               <li class="divider"></li>
               <li class="has-form"> <a href="http://localhost/basc_test/login.php" class="small button">Login</a> </li> 
               <li class="divider"></li>
            </ul>
         </div>
      </nav>
   </div>';
   }
?>
</header>