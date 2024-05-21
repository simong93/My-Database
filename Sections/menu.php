<div class="top-bar d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center"> 
        <span class="mr-4"> </span>
        <div class="search-container">
            <input type="text" class="form-control mr-4" placeholder="Search...">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>
    <span class="mr-4"><?php echo date('l, F j, Y h:i A', time() - 3600); ?></span> <!-- Adjusted the time -->
    <div class="d-flex align-items-center">
        <i class="fas fa-moon mode-icon mr-3"></i>
        <span> <?php echo $greeting; ?>, <?php echo $username; ?> </span> 
    </div>
</div>

<nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="matchBettingDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-gamepad mr-1"></i> Match Betting
            </a>
            <ul class="dropdown-menu" aria-labelledby="matchBettingDropdown">
                <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Casino</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="Add_Mb_Record.php?type=casino">Add Casino</a></li>
                        <li><a class="dropdown-item" href="View_Mb_Records.php?type=casino">View Casino</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Sports</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="Add_Mb_Record.php?type=sports">Add Sports</a></li>
                        <li><a class="dropdown-item" href="View_Mb_Records.php?type=sports">View Sports</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu">
                    <a class="dropdown-item dropdown-toggle" href="#">Exchange</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="Add_Mb_Record.php?type=exchange">Add Exchange</a></li>
                        <li><a class="dropdown-item" href="View_Mb_Records.php?type=exchange">View Exchange</a></li>
                    </ul>
                </li>
            </ul>
        </li>

                <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="scriptPagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-code mr-1"></i> Projects
            </a>
            <ul class="dropdown-menu" aria-labelledby="scriptPagesDropdown">
                <li><a class="dropdown-item" href="AddProjectPage.php">Add Projects</a></li>
                <li><a class="dropdown-item" href="ProjectsPage.php">View Projects</a></li>
            </ul>
        </li>
    </ul>
    <a class="nav-link" href="logout.php">Logout</a>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script> 
$(document).ready(function(){
  $('.dropdown-submenu a.dropdown-toggle').on("click", function(e){
    // close any open menus
    $('.dropdown-submenu .dropdown-menu').not($(this).next('ul')).hide();
    
    // open the selected menu
    $(this).next('ul').toggle();
    
    e.stopPropagation();
    e.preventDefault();
  });
});

document.addEventListener("DOMContentLoaded", function() {
    const modeIcon = document.querySelector(".mode-icon");

    modeIcon.addEventListener("click", function() {
        document.body.classList.toggle("dark-mode");

        if(document.body.classList.contains("dark-mode")) {
            modeIcon.classList.remove("fa-sun");
            modeIcon.classList.add("fa-moon");
        } else {
            modeIcon.classList.remove("fa-moon");
            modeIcon.classList.add("fa-sun");
        }
    });
});

</script>