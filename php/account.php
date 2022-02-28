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
    <title>Nalog</title>
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
    <link rel="stylesheet" href= "../css/font-awesome-4.7.0/css/font-awesome.css">
    <script src= "../css/bootstrap-4.3.1-dist/js/bootstrap.js"> </script>
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
                            <input class="form-control mr-0 text-light" id='search'  name='search' style='background-color:transparent; width:20vmax' type="text" placeholder="Pretraži" aria-label="Search">   
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
            <a class="navbar-brand" href="index.php"><img src="../pictures/baner.png" height='70px' alt=""></a>
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
        <div class="tabs">
            <ul class="nav nav-tabs bg-dark">
                <?php
                    if(!isset($_SESSION['user']))
                        echo '<li class="nav-item"><a href="#menu1" class="nav-link active" data-toggle="tab">Uloguj se</a></li><li class="nav-item"><a href="#menu2" class="nav-link" data-toggle="tab">Napravi nalog</a></li>';
                    else 
                        echo '<li class="nav-item"><a href="#menu3" class="nav-link active" data-toggle="tab">Moj profil</a></li>';
                ?>
            </ul>
            <div class="tab-content">
                <?php
                    if(!isset($_SESSION['user']))
                        echo '<div class="tab-pane fade show active" id="menu1">';
                    else
                        echo '<div class="tab-pane fade" id="menu1">';
                ?>
                    <div class="p-2 m-2 " action="tasks.php?task=logIn" method='post'>
                        <div class='row m-2'>
                            <label for="usernameLog" class='p-2 col-12 col-sm-3'><span class='text-danger font-weight-bold'>* </span> Korisničko ime:</label>
                            <input type="text" class="p-2 col-12 col-sm-4" placeholder="Unesite korisničko ime" id="usernameLog" name="usernameLog"><br>
                        </div>
                        <div class='row m-2'>
                            <label for="passwordLog" class='p-2 col-12 col-sm-3'><span class='text-danger font-weight-bold'>* </span> Lozinka:</label>
                            <input type="password" class="p-2 col-12 col-sm-4" placeholder="Unesite lozinku" id="passwordLog" name='passwordLog'><br>
                        </div>
                        <button class="btn btn-warning btnShow m-2" id='logIn'>Uloguj se</button>
                    </div>
                </div>
                <div class="tab-pane fade" id="menu2">
                    <div class="p-2 text-left row"> 
                        <div class="col-12 col-sm-6">
                            <label for="firstName" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Ime:</label>&nbsp;
                            <input type="text" class="p-2 w-75" placeholder="Unesite ime" id="firstName" name="firstName">
                     
                            <label for="lastName" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Prezime:</label>&nbsp;
                            <input type="text" class="p-2 w-75" placeholder="Unesite prezime" id="lastName" name="lastName">
                        
                            <label for="address" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Adresa:</label>&nbsp;
                            <input type="text" class="p-2 w-75" placeholder="Unesite adresu" id="address" name="address">
                     
                            <label for="email" class='p-2 m-1 w-100 text-left'> E-mail:</label>&nbsp;
                            <input type="email" class="p-2 w-75" placeholder="Unesite e-mail" id="email" name="email">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="phone" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Broj telefona  :</label>&nbsp;
                            <input type="text" class="p-2 w-75" placeholder="06x/xxx-xxx(x)" id="phone" name="phone">
                      
                            <label for="usernameSign" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Korisničko ime:</label>&nbsp;
                            <input type="text" class="p-2 w-75" placeholder="Unesite korisničko ime" id="usernameSign" name="usernameSign">
                        
                            <label for="passwordSign" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Lozinka:</label>&nbsp;
                            <input type="password" class="p-2 w-75" placeholder="Unesite lozinku" id="passwordSign" name="passwordSign">
                        
                            <label for="passwordSignCheck" class='p-2 m-1 w-100 text-left'> <span class='text-danger font-weight-bold'>*</span> Potvrdi lozinku:</label>&nbsp;
                            <input type="password" class="p-2 w-75" placeholder="Potvrdi lozinku" id="passwordSignCheck" name="passwordSignCheck">
                        </div>
                    </div>
                    <input type="checkbox" id="chkSign" name='chkSign' class="p-2 m-2"><label for="chkSign">Slažem se sa <a href="termsAndConditions.php" class='text-info font-weight-bold'> uslovima korišćenja </a></label><br>
                    <button class="btn btn-warning m-2 btnShow" id='signIn'>Napravite nalog</button>
                </div>
                <?php
                    if(isset($_SESSION['user']))
                        echo '<div class="tab-pane fade show active" id="menu3">';
                    else
                        echo '<div class="tab-pane fade" id="menu3">';

                    $user=unserialize($_SESSION['user']);
                ?>
                    <h2 class='underline1 p-2' style='color: rgb(54, 201, 170)'>Dobro došli, <?php echo $user->username;?></h2>   
                    <form class="form-inline p-2 m-2 text-right">
                        <div class="form-group w-100">
                            <label for="firstNameAccount" class='p-2 m-2 w-50'> Ime:</label>&nbsp;
                            <input type="text" class="w-25 p-2" disabled id="firstNameAccount" value="<?php echo $user->firstName;?>">
                        </div>             
                        <div class="form-group w-100">
                            <label for="lastNameAccount" class='p-2 m-2 w-50'> Prezime:</label>&nbsp;
                            <input type="text" class="w-25 p-2" disabled value="<?php echo $user->lastName;?>" id="lastNameAccount">
                        </div>
                        <div class="form-group w-100">
                            <label for="addressAccount" class='p-2 m-2 w-50'> Adresa:</label>&nbsp;
                            <input type="text" class="w-25 p-2" disabled value="<?php echo $user->address;?>" id="addressAccount">
                        </div>
                        <div class="form-group w-100">
                            <label for="emailAccount" class='p-2 m-2 w-50'>E-mail:</label>&nbsp;
                            <input type="email" class="w-25 p-2" disabled value="<?php echo $user->email?>" id="emailAccount">
                        </div>
                        <div class="form-group w-100">
                            <label for="phoneAccount" class='p-2 m-2 w-50'>Broj telefona:</label>&nbsp;
                            <input type="phone" class="w-25 p-2" disabled value="<?php echo $user->phoneNumber;?>" id="phoneAccount">
                        </div>
                    </form>
                    <a class="btn btn-success font-weight-bold w-50 mt-3 p-2" data-toggle="collapse" href="#collapseAddress" role="button" aria-expanded="false" aria-controls="collapseAddress">
                        Promenite adresu dostave <i class='fa fa-arrow-down'></i>
                    </a>
                    <div class="collapse" id="collapseAddress">
                        <div class="card card-body w-50 p-2">
                            <label for="addressNew" class='p-0 m-0'> Nova adresa:</label>
                            <input type="text" id="addressNew" class='m-0'>
                            <button class='p-2 btn btn-warning m-2 font-weight-bold' id="changeAddress">PROMENI ADRESU</button>
                        </div>
                    </div>
                    <br>
                    <a class="btn btn-danger font-weight-bold w-50 my-2 p-2" data-toggle="collapse" href="#collapsePassword" role="button" aria-expanded="false" aria-controls="collapsePassword">
                        Promenite lozinku <i class='fa fa-arrow-down'></i>
                    </a>
                    <div class="collapse" id="collapsePassword">
                        <div class="card card-body w-50 p-2">
                            <label for="oldPasswordChange" class='p-0 m-0'>Stara lozinka:</label>
                            <input type="password" id="oldPasswordChange">
                            <label for="passwordChange" class='p-0 m-0 mt-1'>Nova lozinka:</label>
                            <input type="password" id="passwordChange">
                            <label for="passwordChangeCheck" class='p-0 m-0 mt-1'>Potvrdi lozinku:</label>
                            <input type="password" id="passwordChangeCheck">
                            <button class='p-2 btn btn-warning m-2 font-weight-bold' id="changePassword">PROMENI LOZINKU</button>
                        </div>
                    </div>
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
    </div>
    <script>
        $(document).ready(function(){
            check=<?php  echo json_encode(isset($_COOKIE['Allowed']));  ?>;
            if(!check)
                $("#modalCookies").modal("show");
            if(window.location.hash != "")
                $('a[href="' + window.location.hash + '"]').click();
        })
        $('#changeAddress').click(function() {
            address=document.getElementById("addressNew").value;
            $.post("tasks.php?task=changeAddress",{address:address},function(e){
                alert(e);
                if(e=='Uspešno promenjeno!'){
                    document.getElementById("addressAccount").value=address;
                    document.getElementById("addressNew").value="";
                }
            })
        })
        $("#logIn").click(function(){
            username=document.getElementById("usernameLog").value;
            password=document.getElementById("passwordLog").value;
            if(username.length!=0 && password.length!=0){
                $.post("tasks.php?task=logIn",{username:username, password:password},function(e){
                    if(e=='Success!')
                        window.location.assign("index.php");
                    else
                        alert(e);
                })
            }   
            else 
                alert("Niste uneli sve podatke!");
        })
        $("#signIn").click(function(){
            firstName=document.getElementById("firstName").value;
            lastName=document.getElementById("lastName").value;
            address=document.getElementById("address").value;
            email=document.getElementById("email").value;
            phone =document.getElementById("phone").value;
            usernameSign =document.getElementById("usernameSign").value;
            passwordSign=document.getElementById("passwordSign").value;
            passwordSignCheck=document.getElementById("passwordSignCheck").value;
            if(firstName.length!=0 && passwordSign.length!=0 && passwordSignCheck.length!=0 && usernameSign.length!=0 && lastName.length!=0&& address.length!=0 && phone.length!=0 && email.length!=0){
                if(document.getElementById("chkSign").checked){
                    $.post("tasks.php?task=signIn",{usernameSign:usernameSign, passwordSign:passwordSign, passwordSignCheck:passwordSignCheck, firstName:firstName, lastName:lastName, address:address, email:email, phone:phone},function(e){
                    if(e=='Success!')
                        window.location.assign("index.php");
                    else    
                        alert(e);
                    })
                }
                else 
                    alert("Niste prihvatili uslove korišćenja!");
            }   
            else 
                alert("Niste uneli sve podatke!");
        })
        $("#changePassword").click(function(){
            oldPasswordChange=document.getElementById("oldPasswordChange").value;
            passwordChange=document.getElementById("passwordChange").value;
            passwordChangeCheck=document.getElementById("passwordChangeCheck").value;

            if(oldPasswordChange.length!=0 && passwordChange.length!=0 && passwordChangeCheck.length!=0){
                if(passwordChangeCheck==passwordChange){
                    $.post("tasks.php?task=changePassword", {oldPasswordChange:oldPasswordChange, passwordChange:passwordChange, passwordChangeCheck:passwordChangeCheck},function(e){
                        alert(e);
                        if(e=='Uspešno promenjeno!'){
                            document.getElementById("oldPasswordChange").value="";
                            document.getElementById("passwordChange").value="";
                            document.getElementById("passwordChangeCheck").value="";
                        }
                    })
                }
                else 
                    alert("Niste uneli istu potvrdnu lozinku!");
            }   
            else 
                alert("Niste uneli sve podatke!");
        })
    </script>
</body>
</html>