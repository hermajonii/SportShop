<?php 
    require_once("functions.php");
    if(isset($_SESSION['user'])){
        $user=unserialize($_SESSION['user']);
        if($user->role=='administrator')
            header("Location: admin.php");  
    }
    if(!isset($_GET['item']))
        header('Location: index.php');
        
    $con=getConnection();
    if($con){
        $resultItem = mysqli_query($con,"call sp_showItem(".$_GET['item'].")");
        if(!mysqli_error($con))
            if(mysqli_num_rows($resultItem)<1)
                header("Location: index.php");
            else    
                $resultItem=mysqli_fetch_assoc($resultItem);
        else 
            echo "There has been a mistake!";
    }        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikal</title>
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
    <link rel="stylesheet" href= "../css/font-awesome-4.7.0/css/font-awesome.css">
    <script src= "../css/bootstrap-4.3.1-dist/js/bootstrap.min.js"> </script>
</head>
<body class='h-100'>
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
                                if($row['amount'])
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
        <div class='row m-1 mb-3'>
            <div class='col-12 col-md-8'>
                <div class='row'>
                    <div class='col-3 m-0 p-0' style='overflow-y: scroll; height:40vmax'>
                        <?php
                            $con=getConnection();
                            if($con){
                                $result = mysqli_query($con, "call sp_showPictures(".$_GET['item'].")");
                                if(!mysqli_error($con)){
                                    for($j=0;$j<mysqli_num_rows($result);$j++){
                                        $row=mysqli_fetch_assoc($result);   
                                        //podešavanje prve najveće slike
                                        if($j==0)
                                            $picture=$row['urlPicture'];
                                        echo "<img src='../pictures/".$row['urlPicture']."' alt='' class='img img-thumbnail w-100' onclick='changeMainPhoto(this.src)'>";
                                    }
                                }
                                else
                                    echo "There has been a mistake!";
                                closeConnection($con);
                            }
                        ?>
                    </div>
                    <div class='col-9 px-1'>
                       <?php echo  "<img src='../pictures/".$picture."' id='mainPhoto' alt='' class='img img-thumbnail w-100'>";?>
                    </div>
                </div>
            </div>
            <div class='col-12 col-md-4 px-0'>
                <?php
                    $con=getConnection();
                    if($con){
                        echo "<h2 class='p-2 underline1' style='color: #ffc107; text-shadow: 2px 2px 2px black'>".$resultItem['nameItem']."</h2>";
                        echo  "<div class='text-center'><a href='items.php?idBrand=".$resultItem['idBrand']."'><img src='../pictures/".$resultItem['pictureBrand']."' id='brandPhoto' alt='' class='img img-thumbnail w-50 mb-5 align-self-center'></a></div>";
                        if($resultItem['discount']>0){
                            echo '<h4 class="col-12 align-self-center border-0 pl-0"><del class="text-secondary">'.$resultItem['price'].' RSD </del><span class=" badge badge-2x font-weight-bold text-light bg-danger mt-4 mb-2 p-2">-'.$resultItem['discount'].'%</span><br><b>'.number_format($resultItem['price']*((100-intval($resultItem['discount']))/100),2,".","").' RSD </b></h4>';
                        }
                        else
                            echo '<h4 class="col-12 align-self-center border-0 pl-0"><b>'.number_format($resultItem['price'],2,".","").' RSD</b></h4>';
                        
                        echo "<p class='font-weight-bold mt-5 mb-2'>1. IZABERITE VAŠU VELIČINU: </p><select name='size' id='size' class='form-control' onchange='changeQuantity(this.value); enableAdding();'><option value='0'>--izaberite veličinu--</option>";
                        $sizes=[];
                        $result=mysqli_query($con, "call sp_showSizes(".$_GET['item'].")");
                        if(!mysqli_error($con)){
                            for($i=0;$i<mysqli_num_rows($result);$i++){
                                $resultSizes=mysqli_fetch_assoc($result);
                                array_push($sizes,$resultSizes);
                                echo "<option value='".$resultSizes['idSize']."' id='".$resultSizes['idSize']."'>".$resultSizes['nameSize']."</option>";
                            }
                            echo "</select>";
                        }
                        else
                            echo "There has been a mistake!";
                        closeConnection($con);
                    }
                ?>
                <p class='font-weight-bold mt-4 mb-2'>2. IZABERITE KOLIČINU: </p>
                <select name='quantity' id='quantity' class='form-control' onchange="enableAdding()">
                    <option value='0'>--izaberite količinu--</option>
                </select>
                    
                <?php
                    //da li je ulogovan korisnik
                    if(isset($_SESSION['user']))
                        echo "<button id='addToCart' onclick='addToCart(".$_GET['item'].")' disabled class='btn btnShop mt-4 float-right font-weight-bold'> <i class='fa fa-shopping-cart fa-lg'></i> DODAJ U KORPU</button>";
                    else    
                        echo "<button class='btn btn-warning mt-4 float-right btnShow font-weight-bold'><a href='account.php#menu1' class='text-dark'>PRIJAVI SE I NASTAVI KUPOVINU <i class='fa fa-arrow-right fa-lg'></i></a></button>";
                ?>
            </div>
        </div>
        <div class='row m-2 p-2'>
            <div class='col-12'><a href='items.php'>
                <button class='btn btnShow font-weight-bold btnShop'><i class="fa fa-arrow-left" aria-hidden="true"></i> NASTAVI SA KUPOVINOM</button></a>
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
    </div>
    <script type="text/javascript">
    var sizes = <?php echo json_encode($sizes); ?>;
    function changeQuantity(sizeValue){
        str="<option value='0'>--izaberite količinu--</option>";
        if(sizeValue!="0")
            for(i=0;i<sizes.length;i++)
                if(sizes[i]['idSize']==sizeValue){
                    for(j=1;j<=sizes[i]['amountAvailable'];j++)
                        str+="<option value='"+j+"'>"+j+"</option>";
                }
        document.getElementById("quantity").innerHTML=str;
    }
    function enableAdding(){
        if(document.getElementById('size').value!="0" && document.getElementById('quantity').value!="0"){
            document.getElementById("addToCart").disabled=false;
            document.getElementById("addToCart").classList.add("btnShow");
        }   
        else {
            document.getElementById("addToCart").disabled=true;
            document.getElementById("addToCart").classList.remove("btnShow");
        }    
    }
    function changeMainPhoto(photo){
        document.getElementById('mainPhoto').src="../pictures/"+photo.split('/')[photo.split('/').length-1]+"";
    }
    function addToCart(idItem){
        amount=document.getElementById("quantity").value;
        size=document.getElementById("size").value;
        $.post("tasks.php?task=addItemInCart",{idItem: idItem, amount: amount, size:size},function(e){
            alert(e);
        })
    }
    $(document).ready(function(){
        check=<?php  echo json_encode(isset($_COOKIE['Allowed']));  ?>;
        if(!check)
            $("#modalCookies").modal("show");
    })
    </script>
 
</body>
</html>