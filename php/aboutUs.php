<?php 
    require_once("functions.php");
    if(isset($_SESSION['user'])){
        $user=unserialize($_SESSION['user']);
        if($user->role=='administrator')
            header("Location: admin.php");  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O nama</title>
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/font-awesome-4.7.0/css/font-awesome.css">
    <script src= "../css/bootstrap-4.3.1-dist/js/bootstrap.min.js"> </script> 
</head>
<body>
    <div class='container-fluid'>
        <div class="modal fade" id="modalCookies" role="dialog" >
            <div class="modal-dialog container modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="m-2">Koristimo kolačiće kako bismo poboljšali iskustvo na sajtu! 
                        </p>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="text-right modal-body p-2">
                        <a href="termsAndConditions.php#cookies"><button type="button" class="mr-3 bg-warning btn font-weight-bold text-light">Pročitaj više</button></a>  
                        <a href="tasks.php?task=setCookie"><button type="button" class="mr-3 bg-success btn font-weight-bold text-light">Dozvoli kolačiće</button></a>                        
                    </div>
                </div>
            </div>
        </div>
        <div class='row navbar p-0 m-0' style='z-index:2 !important'>
            <ul class="list-inline ml-auto nav-flex-icons m-0 p-0">
                <form class="form-inline" method='post' action='tasks.php?task=search'>
                    <li>
                        <div class="md-form my-0">
                            <input class="form-control mr-0 text-light" id='search' name='search' style='background-color:transparent; width:20vmax' type="text" placeholder="Pretraži" aria-label="Search">   
                        </div>
                    </li>
                    <li>
                        <button class="nav-link pr-1 pl-1" type='submit'>
                            <i class="fa fa-search fa-lg mx-1"></i>
                        </button>
                    </li>
                    <?php
                        if(isset($_SESSION['user']))
                            echo '<li class="nav-item list-inline-item m-0"><a class="nav-link px-0 pr-2" href="cart.php"><i class="fa fa-shopping-cart fa-lg"></i></a></li>';
                    ?>
                    <li class="nav-item list-inline-item dropdown m-0 p-0">
                        <a class="nav-link dropdown-toggle pl-1 pr-1 mr-1" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user fa-lg"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333">
                            <?php
                                if(!isset($_SESSION['user'])){
                                    echo '<a class="dropdown-item nav-link" href="account.php#menu1">Uloguj se</a><a class="dropdown-item nav-link" href="account.php#menu2">Napravi nalog</a>';
                                }
                                else{
                                    echo '<a class="dropdown-item nav-link" href="account.php">Moj profil</a><a class="dropdown-item nav-link" href="tasks.php?task=logOut">Odjavi se</a>';
                                }
                            ?>
                        </div>
                    </li>
                </form>
            </ul>
        </div>
        <nav class="navbar p-0 navbar-expand-md navbar-dark text-dark" style='z-index:1 !important'>
            <a class="navbar-brand" href="index.php"><img src="../pictures/logo.png" height='70px' alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <?php
                        $result=getCategories();
                        if($result)
                        for($i=0;$i<mysqli_num_rows($result);$i++){
                            $row=mysqli_fetch_assoc($result);
                            if($row['amount']>0)
                                echo '<li class="nav-item slide p-1"><a class="nav-link m-0 px-0" href="items.php?category='.$row['idCategory'].'">'.strtoupper($row['nameCategory']).'</a></li>';
                        }
                    ?>
                    <li class="nav-item p-1"> 
                        <div class="nav-item dropdown position-static p-0"> 
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> BRENDOVI</a>                                
                            <div class="dropdown-menu m-0 p-0 w-100 shadow border-outline-success" aria-labelledby="navbarDropdown"> 
                                <div class='row col-12 px-0 mr-0 pl-3'>
                                    <?php
                                        $result1=getBrands();
                                        if($result1)
                                        for($i=0;$i<mysqli_num_rows($result1);$i++){
                                            $row=mysqli_fetch_assoc($result1);
                                            echo '<div class="col-6 col-md-3 m-0 p-0 dropdown-item"><a class="nav-link" href="items.php?idBrand='.$row['idBrand'].'">'.$row['nameBrand'].'</a></div>';
                                        }
                                    ?>     
                                </div> 
                            </div>
                        </div> 
                    </li>
                </ul> 
            </div> 
        </nav>
        <div class='row mt-2 mx-1'>
            <div class='col-12 col-md-8'>
                <h1 id='about' class='text-warning underline'>O NAMA</h1>
                    <div class='text-justify p-2'>Kompanija N Sport je jedan od vodećih distributera najpoznatijih sportskih i modnih brendova sa više od 60 prodajnih objekata na teritoriji Srbije i Bosne i Hercegovine.
                    Usmereni smo na zadovoljenje potreba kupaca i omogućavamo da individualnost svakog pojedinca bude prikazana i kroz lični stil, jer on predstavlja odraz nečije ličnosti i načina života. Iz tog razloga N Sport pruža otpor konvencionalnosti i skreće pažnju na različitost i jedinstvenost svake osobe.
                    <br><hr>
                    Kompanija je prepoznatljiva po svoja tri osnovna prodajna multibrend koncepta: N Sport, N Fashion i N Selection.
                    <br><hr>
                    N Sport koncept odgovara aktivnom životu savremenih muškaraca i žena i predstavlja sportske brendove: PUMA, adidas, Nike, Sergio Tacchini, Russell Athletic, Converse, Reebok, Skechers, Rider/Ipanema, Timberland, Columbia, Northland...
                    <br><hr>
                    N Fashion je posvećen klijentima koji imaju istančan modni ukus i prate svetske trendove. Za njih su odabrani najbolji high street brendovi: Tommy Hilfiger, Liu Jo, Superdry, Replay, Guess, Armani Exchange, Trussardi Jeans, Baldinini, Bogner, Ugg, Mou, Staff Jeans...
                    <br><hr>
                    N Selection koncept je inspirisan urbanim ritmom svetskih metropola i predstavlja najbolje od svetskih modnih i sportskih brendova kao što su PUMA Prime i Select linije, adidas Originals, Nike, Tommy Hilfiger, Guess, Replay, Armani Exchange, Bogner, Trussardi Jeans...    </div>
                </div>
            <div class="col-md-4 col-12">
                <div class='row'>
                    <div class="m-2">
                        <div class="selection">
                            <img class="img-responsive" src="../pictures/phone.jpg" width='100%' height='300px' alt="">
                            <h2 class='text-warning'>CALL CENTAR</h2>
                            <div class="font-weight-bold text-light ">
                                Dostupni smo telefonom svakog radnog dana od 9h do 21h. 
                                <br>U subotu možete nas kontaktirati od 09h do 15h.
                                <br>
                                <button class='btn btn-large bg-warning text-dark mt-3 font-weight-bold btnShow'>+31 416 652803</button>
                            </div>
                        </div>
                    </div>  
                    <div class="m-2">
                        <a href='items.php?discount=20'>
                            <img class="img-responsive" src="../pictures/sale00.png" width='100%' height='300px' alt="">
                        </a>
                    </div>  
                </div>
            </div>    
        </div>
        <div class='row'>
            <div class="container text-center my-3">
                <?php
                    if($result1){
                        echo '<h3 class="text-warning">IZABERITE VAŠ OMILJENI BREND</h3>
                        <div id="recipeCarousel" class="carousel slide w-100 h-100" data-ride="carousel">
                            <div class="carousel-inner w-100 " role="listbox">';
                        $counter=0;$converse="";
                        mysqli_data_seek($result1,0);
                        for($i=0;$i<mysqli_num_rows($result1);$i++){
                            $row=mysqli_fetch_assoc($result1);
                            
                            if(strcmp(trim($row['nameBrand']),"Converse")==0)
                                $converse=$row['idBrand'];
                            if($i%2==0){
                                if($i==0)
                                    echo '<div class="carousel-item row no-gutters active">';
                                else    
                                    echo '</div><div class="carousel-item row no-gutters">';
                            }
                            echo '<a href="items.php?idBrand='.$row['idBrand'].'"><div class=" col-6 float-left"><img class="img-fluid" src="../pictures/'.$row['pictureBrand'].'"></div></a>';
                        }
                        echo '</div>
                        <a class="carousel-control-prev" href="#recipeCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#recipeCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        </div>
                    </div>';
                    }
                ?>
            </div>
        </div>
        <div class='row text-dark py-4 m-1'>
            <div class='col-12'>
                <h1 id='questions' class='text-warning underline'> <i class="fa fa-question"></i> ČESTO POSTAVLJANA PITANJA</h1>
                <div id="accordion">
                    <?php
                        $name="../FAQ.txt";
                        if(file_exists($name)){
                            $fp=fopen($name,"r");
                            $count=0;
                            while(!feof($fp)){
                                $arr=explode("~",fgets($fp));
                                if(count($arr)==2)
                                    echo '<div class="card">
                                    <div class="card-header " id="heading'.$count.'">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link text-dark  text-decoration-none" data-toggle="collapse" data-target="#collapse'.$count.'" aria-expanded="true" aria-controls="collapse'.$count.'">
                                                '.$arr[0].'
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse'.$count.'" class="collapse" aria-labelledby="heading'.$count.'" data-parent="#accordion">
                                        <div class="card-body">
                                            '.$arr[1].'
                                        </div>
                                    </div>
                                </div>  ';
                                $count++;
                            }
                            fclose($fp);
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class='row mt-2 mb-3 mx-1'>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class='row'>
                    <div class="selection">
                        <img class="img-responsive" src="../pictures/new.jpg" width='100%' height='300px' alt="">
                        <div class="info text-warning font-weight-bold"><hr>
                            <span class='text-light font-lg'> NOVO! </span> POGLEDAJTE NAŠU NOVU KOLEKCIJU! <span class='text-light font-lg'> NOVO! </span> <br>
                            <a href="items.php"><button class='btn btn-large bg-warning text-dark mt-3 font-weight-bold btnShow'>KUPUJ SAD</button></a>
                        </div>
                    </div>
                    <div class="selection">
                        <img class="img-responsive" src="../pictures/converse1.jpg" width='100%' height='300px' alt="">
                        <div class="info text-light font-weight-bold"><hr>
                                POGLEDAJTE NAŠU NOVU CONVERSE KOLEKCIJU! <br>
                                <a href="items.php?idBrand=<?php echo $converse;?>"><button class='btn btn-large bg-danger text-dark mt-3 font-weight-bold btnShow'>KUPUJ SAD</button></a>
                        </div>                     
                    </div>
                </div>
            </div>
            <div class='col-md-8 col-12 frmWrite'>
                <h1 id='write' class='text-warning underline'>PIŠITE NAM<i class="fa fa-commenting fa-2x text-warning"></i></h1>
                <div class="mt-3 p-3 border rounded border-dark text-light bg-dark"> 
                    <label for="name"><i class="fa fa-user-o fa-2x text-warning"> </i></label>
                    <input type="text" id="name" placeholder="Unesite ime" class='w-75 w-md-50 mb-1 p-2'> 
                    <nobr> 
                    <label for="email"><i class="fa fa-envelope-open fa-2x text-warning"></i> </label>
                    <input type="email" id="email" placeholder="Unesite e-mail" class='w-md-50 w-75 p-2'></nobr> <br>
                    <label for="comment" class='text-warning mt-2'>Vaš komentar:</label> <br>
                    <textarea name="" id="comment" cols="20" rows="7" resize="horizontal" class="md-textarea form-control"></textarea><br>
                    <button class="btn btn-lg btnShow p-2 bg-warning border-warning d-inline p-1 font-weight-bold" id="sendComment" >Pošaljite komentar</button>
                </div>             
            </div>
        </div>
        <footer class='page-footer p-0'>
            <div class='container-fluid'>
                <div class='row p-2'>
                    <div class="col-lg-4 col-sm-12 ml-0 mt-3">
                        <div class="footerSocial text-center">
                                <h3 class='text-light mt-4'>POVEŽITE SE</h3>
                                <p class='m-0 p-0'>
                                    <a href="#" class='m-1 d-inline-block'>
                                        <i class="fa fa-twitter fa-lg"></i>
                                    </a>
                                    <a href="#" class='m-1 d-inline-block'>
                                        <i class="fa fa-facebook fa-lg"></i>
                                    </a>
                                    <a href="#" class='m-1 d-inline-block'>
                                        <i class="fa fa-instagram fa-lg"></i>
                                    </a>
                                    <a href="#" class='m-1 d-inline-block'>
                                        <i class="fa fa-dribbble fa-lg"></i>
                                    </a>
                                </p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12 col-lg-4 mb-md-0 mt-3 mb-3">
                        <h5 class="text-uppercase text-warning underline1 pb-2">KONTAKT</h5>
                        <ul class="list-unstyled text-light p-2">
                            <li class="my-2">
                                <a href="#!"  class='text-light'><i class="fa fa-address-book text-warning fa-lg"></i> +31 416 652803</a>
                            </li>
                            <li class="my-2">
                                <a href="#!" class='text-light'><i class="fa fa-envelope text-warning fa-lh"></i> info@sportshop.com</a>
                            </li>
                            <li class="my-2">
                                <a href="#!"  class='text-light'><i class="fa fa-map-marker text-warning fa-lg"></i> Sportshop.com, Kralja Petra 1, Beograd 11000</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-12 col-lg-4 mb-md-0 mt-3 mb-3">
                        <h5 class="text-uppercase text-warning underline1 pb-2">INFORMACIJE</h5>
                        <ul class="list-unstyled text-light p-2">
                            <li class="my-2">
                                <a href="aboutUs.php#about"  class='text-light'><i class="fa fa-search text-warning fa-lg mr-1" aria-hidden="true"></i>O nama</a>
                            </li>
                            <li class="my-2">
                                <a href="aboutUs.php#questions" class='text-light'><i class="fa fa-question text-warning fa-lg mr-2" aria-hidden="true"></i> Često postavljana pitanja</a>
                            </li>
                            <li class="my-2">
                                <a href="aboutUs.php#write"  class='text-light'><i class="fa fa-comments text-warning fa-lg" aria-hidden="true"></i> Žalbe i sugestije</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright text-center py-3 text-warning m-0">© 2020 <br>Tamara Milić - Završni rad
            </div>
        </footer>
        <script>
            $("#sendComment").click(function(){
                name=document.getElementById("name").value;
                email=document.getElementById("email").value;
                comment=document.getElementById("comment").value;
                if(name.length!=0 && email.length!=0 && comment.length!=0){
                    $.post("tasks.php?task=sendComment",{name:name,email:email,comment:comment},function(e){
                        alert(e);
                        if(e=="Uspešno poslato!"){
                            document.getElementById("name").value="";
                            document.getElementById("email").value="";
                            document.getElementById("comment").value="";
                        }
                    })
                }
                else
                    alert("Niste uneli sve informacije!");
           });
            $(document).ready(function(){
                check=<?php  echo json_encode(isset($_COOKIE['Allowed']));  ?>;
                if(!check)
                    $("#modalCookies").modal("show");
            })
        </script>
    </div>
</body>
</html>