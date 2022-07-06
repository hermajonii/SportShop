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
    <title>SPORT SHOP</title>     
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
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
        <nav class="navbar navbar-expand-md navbar-dark text-dark m-0 p-0" style='z-index:1 !important'>
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
                                if($row['amount'])
                                    echo '<li class="nav-item slide p-1"><a class="nav-link m-0 px-0" href="items.php?category='.$row['idCategory'].'">'.strtoupper($row['nameCategory']).'</a></li>';
                            }
                    ?>
                    <li class="nav-item p-1"> 
                        <div class="nav-item dropdown position-static p-0" > 
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> BRENDOVI</a>                                
                            <div class="dropdown-menu m-0 p-0 w-100 shadow border-outline-success" aria-labelledby="navbarDropdown"> 
                                <div class='row col-12 px-0 mr-0 pl-3'>
                                    <?php
                                        $adidas=""; $nike=""; $puma="";
                                        $result1=getBrands();
                                        if($result1)
                                            for($i=0;$i<mysqli_num_rows($result1);$i++){
                                                $row=mysqli_fetch_assoc($result1);
                                                if(strcmp($row['nameBrand'],"Nike")==0)
                                                    $nike=$row['idBrand'];
                                                elseif(strcmp($row['nameBrand'],"Adidas")==0)
                                                    $adidas=$row['idBrand'];
                                                elseif(strcmp($row['nameBrand'],"Puma")==0)
                                                    $puma=$row['idBrand'];
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
        <div class='row m-0'>    
            <div id="carouselExampleSlidesOnly" class="carousel slide mb-5 col-12 p-0" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="../pictures/sale.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="../pictures/sale1.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="../pictures/sale2.jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class='row mt-5 mx-2'>
            <div class="col-md-4 col-sm-12 col-xs-12 mt-2 selection" style="height:300px; background-image:url('../pictures/women3.jpg');padding:0">
                <h2 class='text-warning'>ŽENE</h2>
                <div class="mt-3">
                    <?php
                        if($result){
                            mysqli_data_seek($result, 0);
                            for($i=0;$i<mysqli_num_rows($result);$i++){
                                $row=mysqli_fetch_assoc($result);
                                if($row['amount']>0)
                                    echo '<h5><a href="items.php?category='.$row['idCategory'].'&gender=female" class="btnShow">'.strtoupper($row['nameCategory']).'</a></h5>';
                            }
                        }
                    ?>
                    <a href="items.php?gender=female"><button class='btn btn-large btnShop text-dark mt-4 font-weight-bold btnShow'>VIDI SVE</button></a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 mt-2 selection" style="height:300px; background-image:url('../pictures/men.jpg');padding:0">
                <h2 class='text-warning'>MUŠKARCI</h2>
                <div class="mt-3">
                    <?php
                        if($result){
                            mysqli_data_seek($result, 0);
                            for($i=0;$i<mysqli_num_rows($result);$i++){
                                $row=mysqli_fetch_assoc($result);
                                if($row['amount']>0)
                                    echo '<h5><a href="items.php?category='.$row['idCategory'].'&gender=male" class="btnShow">'.strtoupper($row['nameCategory']).'</a></h5>';
                            }
                        }
                    ?>
                    <a href="items.php?gender=male"><button class='btn btn-large btnShop text-dark mt-4 font-weight-bold btnShow'>VIDI SVE</button></a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 mt-2 selection" style="height:300px; background-image:url('../pictures/kids.jpg');padding:0">
                <h2 class='text-warning'>DECA</h2>
                <div class="mt-3">
                    <?php
                        if($result){
                            mysqli_data_seek($result, 0);
                            for($i=0;$i<mysqli_num_rows($result);$i++){
                                $row=mysqli_fetch_assoc($result);
                                if($row['amount']>0)
                                    echo '<h5><a href="items.php?category='.$row['idCategory'].'&gender=children" class="btnShow">'.strtoupper($row['nameCategory']).'</a></h5>';
                            }
                        }
                    ?>
                    <a href="items.php?gender=children"><button class='btn btn-large btnShop text-dark mt-4 font-weight-bold btnShow'>VIDI SVE</button></a>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class="container-fluid text-center my-3">
                <?php
                    if($result1){
                        echo '<h3>BRENDOVI</h3>
                        <div id="recipeCarousel" class="carousel slide w-100 h-100" data-ride="carousel">
                            <div class="carousel-inner w-100 " role="listbox">';
                        $counter=0;
                        mysqli_data_seek($result1,0);
                        for($i=0;$i<mysqli_num_rows($result1);$i++){
                            $row=mysqli_fetch_assoc($result1);
                            if($i%4==0){
                                if($i==0)
                                    echo '<div class="carousel-item row no-gutters active">';
                                else    
                                    echo '</div><div class="carousel-item row no-gutters">';
                            }
                            echo '<div class="col-md-3 col-6 float-left"><a href="items.php?idBrand='.$row['idBrand'].'"><img class="img-fluid" src="../pictures/'.$row['pictureBrand'].'"></a></div>';
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
                            <h4>odaberite Vaš stil</h4>';
                    }
                ?>
            </div>
        </div>
        <div class='row m-2'>
            <div class="card-columns cardsIndex">
                <div class="card">
                    <img class="card-img-top img-thumbnail" src="../pictures/nike4.jpg" alt="Card image cap">
                    <div class="card-body">
                        <a href="items.php?idBrand=<?php echo $nike; ?>"><button class='btn btn-lg btn-dark font-weight-bold text-warning m-0 btn-block'>KUPI SAD</button></a> 
                    </div>
                </div>
                <div class="card p-3">
                    <blockquote class="blockquote mb-0">
                    <p>ONI KOJI TI KAŽU DA 'NE MOŽEŠ' SU SAMO UPLAŠENI DA 'HOĆEŠ'</p>
                    <footer class="blockquote-footer">
                        <small class="text-muted">
                            <cite title="Source Title">NIKE</cite>
                        </small>
                    </footer>
                    </blockquote>
                </div>
                <div class='card'>
                    <div>
                        <a href="items.php"><span class='text-light font-lg'>
                            <img class="img-responsive" src="../pictures/new.jpg" width='100%' height='300px' alt="">
                        </a>
                    </div>
                </div>
                <div class="card text-center p-3">
                    <blockquote class="blockquote mb-0">
                    <p>Adidas predstavlja ličnu inspiraciju za mene. Obogatio je moj kreativni život. To je razmena između različitih kultura, različitih ideja, i pre svega, to je timski rad..</p>
                    <footer class="blockquote-footer">
                        <small>
                            Yohji Yamamoto 
                            <cite title="Source Title">Adidas</cite>
                        </small>
                    </footer>
                    </blockquote>
                </div>
                <div class="card">
                    <img class="card-img-top img-thumbnail" src="../pictures/adidas.jpg" alt="Card image cap">
                    <div class="card-body">
                        <a href="items.php?idBrand=<?php echo $adidas; ?>"><button class='btn btn-lg btn-dark font-weight-bold text-warning m-0 btn-block'>KUPI SAD</button></a>
                    </div>
                </div>        
                <div class="card">
                    <div class="card-body">
                        <a href="items.php?idBrand=<?php echo $puma; ?>"><button class='btn btn-lg btn-dark font-weight-bold text-warning m-0 btn-block'>KUPI SAD</button></a> 
                    </div>
                        <img class="card-img img-thumbnail" src="../pictures/puma6.jpg" alt="Card image">
                </div>
                <div class="card p-3 text-right mt-2">
                    <blockquote class="blockquote mb-0">
                    <p>Puma je brend koji je duboko ukorenjen u sportski način života.</p>
                    <footer class="blockquote-footer">
                        <small class="text-muted">
                        Jochen Zeith <cite title="Source Title">o Pumi</cite>
                        </small>
                    </footer>
                    </blockquote>
                </div>
            </div>
        </div>
        <div class='row m-2 w-100'> 
            <div class="col-md-4 col-12">
                <div class="d-flex align-items-end" style="background-image:url('../pictures/men1.jpg');background-size:cover;height:20rem">
                    <a href="items.php?gender=male">
                        <button class='d-flex btn btn-large bg-warning text-dark mt-3 font-weight-bold btnShow'>POGLEDAJ PONUDU 
                            <i class='fa fa-arrow-right mt-1 ml-1'> </i>
                        </button>
                    </a>
                </div>
            </div>  
            <div class="col-md-4 col-12"> 
                <a href="items.php?discount=40,30,23,20,15">
                    <img class="img-responsive" src="../pictures/sale00.png" width='100%' height='300px' alt="">
                </a>
            </div>  
            <div class="col-md-4 col-12">
                <div class="d-flex align-items-end" style="background-image:url('../pictures/women2.jpg');background-size:cover;height:20rem">
                    <a href="items.php?gender=female">
                        <button class='btn btn-large bg-warning text-dark mt-3 font-weight-bold btnShow'>POGLEDAJ PONUDU
                            <i class='fa fa-arrow-right mt-1 ml-1'> </i>
                        </button>
                    </a>
                </div>
            </div> 
        </div>
    </div>
    <footer class='page-footer p-0 container-fluid'>
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
    $(document).ready(function(){
        check=<?php  echo json_encode(isset($_COOKIE['Allowed']));  ?>;
        if(!check)
            $("#modalCookies").modal("show");
    })
    </script>
</body>
</html>