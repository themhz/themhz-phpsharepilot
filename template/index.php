<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="template/css/style.css">
    <!--Δήλωση του javascript για την λειτουργία του Menu-->
    <script src="template/js/main.js?v="<?php echo time(); ?>"></script>
    <meta charset="utf-8">
    <title>WebSite</title>


</head>

<body>
    <!-- <input type="button" value="ajax test" onclick="ajaxtest()"/>
    <textarea id="demo" style="width:700px;height:120px"></textarea> -->
    <!--Επειδή χρησιμοποιώ το css-grid ως τεχνική, δηλώνω ένα container για να τα βάλω όλα μέσα  -->
    <div class="grid-container">
        <!--Εδώ είναι η κεφαλίδα της σελίδας όπου θα τοποθετηθεί η εικονα ο τίτλος η είσοδος-->
        <div class="head">
            <div class="header-container">
                <div class="headl">
                    <!--Εκόνα με Link στν αρική-->
                    <a href="home"><img src="template/images/sharepilot.png" alt="sharepilot" /></a>
                </div>
                <!--Οι κεφαλιδες στην κεντρική-->
                <div class="headm">
                    <h1>Share Pilot</h1>
                </div>
                <!--το Λινκ της εισόδου-->
                <div class="headr">
                <?php if (isset($_SESSION["user"])){ ?>
                    <a id="logout" href="login?method=logout&format=raw">Έξοδος</a>                
                <?php } ?>
                    
                    
                </form>
                </div>                
            </div>
        </div>
        <!--Εδώ έχουμε οριζόντιο placeholder για το menu-->
        <div class="nav">
            &nbsp;
        </div>
        <!--Εδώ έχουμε κάθετο placeholder για το menu-->
        <?php if (isset($_SESSION["user"])) {?>
        <nav id="nav2" class="nav2">
            <ul>
            <?php if ($_SESSION["user"][0]->role == 1){ ?>
                <li id="listyoutube"><a href="youtube">Εύρεση Video από YouTube</a></li>
                <li id="listdatabase"><a href="database">Διαχείριση Link από την βάση</a></li>
                <li id="listschedule"><a href="schedule">Πρόγραμμα</a></li>
            <?php } ?>
            <?php if ($_SESSION["user"][0]->role == 2){ ?>
                <li id="listprofile"><a href="profile">Διαχείριση Profile</a></li>
                <li id="listprofessorprofilelessons"><a href="professorprofilelessons">Διαχείριση Μαθημάτων</a></li>
            <?php } ?>
            <?php if ($_SESSION["user"][0]->role == 3){ ?>
                <li id="listprofile"><a href="profile">Διαχείριση Profile</a></li>
                <li id="liststudentprofilelessons"><a href="studentprofilelessons">Διαχείριση Μαθημάτων</a></li>
            <?php } ?>
            </ul>
        </nav>
        <?php }?>
        <!--Εδώ έχουμε το κυρίως σώμα της σελίδας με τις ανακοινώσεις αριστερά και δεξιά
            επίσης κάθε ανακοίνωση ανα μια αλλάζει η κλάση της ώστε να πετύχουμε την διαφοροποίηση αριστερά δεξιά-->
        <div class="body">

            <?php
                include $page->load();
            ?>

        </div>
        <!--Εδώ έχουμε το footer. To χώρισα σε 2 κομμάτια, το αριστερό και το δεξιό.-->
        <!-- εδώ έχουμε την λίστα με το επικοινωνήστε το τηλέφωνο και το email-->
        <div class="footerl">
            <ul>
                <li class="lititle"></li>
                <li class="phone"></li>
                <li class="mail"></li>
            </ul>
        </div>
        <!--και εδώ οι όροι χρήσης -->
        <div class="footerr">
            <ul>
                <li>
<!--                    <a href="https://www.eap.gr/wp-content/uploads/2020/10/oroi-xr.pdf" target="_blank"> |</a>-->
                </li>
                <li>
<!--                    <a href="https://www.eap.gr/data-protection-team/" target="_blank"></a>-->
                </li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('readystatechange', function(evt) {
            if (evt.target.readyState == "complete") {
                <?php if(isset($_GET["page"]) && $_GET["page"]!="login") {?>
                    document.getElementById("list<?php echo $_GET["page"] ?>").className = "selected";
                <?php }?>
            }
        }, false);
    </script>
</body>

</html>