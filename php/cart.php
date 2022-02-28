<?php 
    require_once("functions.php");
    if(!isset($_SESSION['user']))
        header("Location: index.php");  
    else{
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
    <title>Korpa</title>
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
    <link rel="stylesheet" href= "../css/font-awesome-4.7.0/css/font-awesome.css">
    <script src= "../css/bootstrap-4.3.1-dist/js/bootstrap.min.js"> </script> 
</head>
<body class='p-0'>
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
        <div class='row p-0 m-0'>
            <table class='table text-left col-12 p-0'>
                <tbody id='items'> 
                    <?php
                        $user=unserialize($_SESSION['user']);
                        $con=getConnection();
                        $totalPrice=0;
                        $items=[];
                        if($con){
                            $resultCart=mysqli_query($con,"call sp_showCartItems(".$user->idUser.")");
                            if(!mysqli_error($con)){
                                if(mysqli_num_rows($resultCart)>0)
                                    echo '<tr class="row underline text-left m-0 p-0 w-100"> <td class="col-3 col-lg-5 pl-0 border-0">ARTIKAL</td><td class="col-2 pl-0 border-0">CENA</td><td class="col-lg-1 col-3 pl-0 border-0">KOL.</td><td class="col-2 pl-0 border-0">UKUPNO</td></tr>';
                                for($i=0;$i<mysqli_num_rows($resultCart);$i++){
                                    $row=mysqli_fetch_assoc($resultCart);
                                    echo '<tr class="row underline w-100 m-0 p-0">
                                    <td class="col-3 col-lg-5 m-0 p-0 row border-0">
                                    <img src="../pictures/'.$row['urlPicture'].'" alt="" class="p-0 col-12 col-lg-4">
                                    <p class="col-lg-8 col-12 align-self-center p-0 pl-1"><a href="item.php?item='.$row['idItem'].'" class=" align-self-center text-dark" >'.$row['nameItem'].'</a>
                                    <br>Veličina: '.$row['size'].'</p>
                                    </td>';
                                    if($row['discount']>0){
                                        $price=$row['price']*((100-intval($row['discount']))/100);
                                        echo '<td class="m-0 p-0 pl-1 col-2 align-self-center border-0"><del>'.$row['price'].' </del> <span id="price'.$row['idItem'].'">'.$price.'</span>rsd</td>';
                                    }
                                    else{
                                        $price=$row['price'];
                                        echo '<td class="p-0 m-0 pl-1 col-2 align-self-center border-0"><span id="price'.$row['idItem'].'">'.$price.'</span>rsd</td>';
                                    }
                                    $price=$row['amount']*$price;
                                    $totalPrice+=$price;
                                    echo '<td class="col-3 col-lg-1 align-self-center border-0 p-0 m-0 text-left"><input type="number" onchange="changeAmountOfItem('.$row['idItem'].','.$row['idSize'].',this)" id="input'.$row['idItem'].'" class="text-center p-0 ml-4 ml-md-0" value="'.$row['amount'].'" max="'.$row['maximum'].'" min="1"></td><td class="m-0 p-0 col-3 align-self-center border-0"><span id="totalPrice'.$i.'">'.$price.'</span>rsd</td>
                                    <td class="col-1 p-0 m-0 align-self-center border-0"><a class="text-dark" onclick="deleteFromCart('.$row['idItem'].','.$row['idSize'].')"><i class="fa fa-trash fa-2x text-danger" aria-hidden="true""></i></a></td></tr>';
                                    
                                    array_push($items,$row);
                                    $items[$i]['idItem']=$row['idItem'];    
                                }
                                closeConnection($con);
                                if(mysqli_num_rows($resultCart)>0)
                                    echo '<tr class="row text-right mr-2"><td class="border-0 font-weight-bold col-12">UKUPNA CENA: <span id="totalPriceAll">'.$totalPrice.'</span>rsd</td></tr>';
                                else 
                                    echo '<div class="alert col-12 p-3 mt-3 alert-dismissible fade show" style="background-color: rgba(54, 201, 170,0.3)" role="alert"><strong>Vaša korpa je prazna!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';;
                            }
                            else 
                                echo "There has been a mistake!";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class='row m-2 p-2'>
            <div class='col-12'><a href='items.php'>
                <button class='btn btnShow font-weight-bold btnShop'><i class="fa fa-arrow-left" aria-hidden="true"></i> NAZAD NA KUPOVINU</button></a>
                <button class='btn font-weight-bold btnShop btnShow float-right' style="visibility:hidden" id='btnBuy' data-toggle="modal" data-target="#myModal">NARUČI SAD 
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </button>
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title">Da li ste sigurni da hoćete da kupite?</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body p-0">
                            <div class='container-fluid p-0'>
                                <table id='itemsModal'>
                                    <?php
                                        $totalPrice=0;
                                        for($i=0;$i<count($items);$i++){
                                            echo '
                                            <tr class="ml-0 pl-0 w-100 row">
                                                <td class="col-4 col-md-6 border-0 m-0 align-self-center">
                                                    <div class="row align-self-center">
                                                    <img src="../pictures/'.$items[$i]['urlPicture'].'" alt="" class="align-self-center p-0 col-12 col-md-4 img">
                                                    <span class="col-12 col-md-7 p-0 align-self-center text-center">
                                                        <small class="font-weight-bold">'.$items[$i]['nameItem'].'<br>
                                                        Veličina: '.$items[$i]['size'].'
                                                        </small>
                                                    </span>
                                                    </div>
                                                </td>';
                                            if($items[$i]['discount']>0){
                                                $price=$items[$i]['price']*((100-intval($items[$i]['discount']))/100);
                                                echo '<td class="m-0 p-0 col-3 col-md-2 border-0 align-self-center pb-4"><del>'.$items[$i]['price'].'</del><br> '.$price.'rsd</td>';
                                            }
                                            else{
                                                $price=$items[$i]['price'];
                                                echo '<td class="p-0 m-0 col-3 col-md-2 border-0 align-self-center">'.$price.'rsd</td>';
                                            }
                                            $price=$items[$i]['amount']*$price;
                                            $totalPrice+=$price;
                                            echo '<td class="col-2 border-0 p-0 m-0 text-left align-self-center"> 
                                                    <b>x'.$items[$i]['amount'].'=</b> 
                                                </td>
                                                <td class="m-0 p-0 col-2 border-0 align-self-center">'.$price.'rsd
                                                </td>
                                        </tr>';
                                        }
                                    ?>
                                </table>
                            </div>
                            <?php 
                                echo "<span class='float-right pr-2'><b>Ukupna cena: </b><span id='totalPriceModal'>".$totalPrice."</span>rsd</span>";
                            ?>
                            <div class='mt-5 pl-2'>
                                <b>Adresa dostave: </b>
                                <?php 
                                    $user=unserialize($_SESSION['user']);
                                    echo $user->address;
                                ?>
                                <br>
                                <b>Kontakt telefon: </b>
                                <?php 
                                    echo $user->phoneNumber;
                                ?>
                                <br><b>E-mail: </b>
                                <?php 
                                    echo $user->email;
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button class='btn btnShow font-weight-bold btnShop float-left' data-dismiss='modal'>
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> NASTAVI SA KUPOVINOM
                        </button>
                        <button class='btn font-weight-bold btn-danger btnShow float-right' id='purchase' data-dismiss='modal'>NARUČI SAD
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                        </div>
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
    <script type="text/javascript">
        var items = <?php echo json_encode($items); ?>;
        function changeAmountOfItem(idItem, size, obj){
            amount=obj.value;
            if(amount>0){
                $.post("tasks.php?task=changeAmountOfItem",{idItem: idItem, amount: amount, size: size},function(e){
                    if(e=="Success!")
                    for(i=0;i<items.length;i++)
                        if(items[i]['idItem']==idItem && items[i]['idSize']==size){
                            items[i]['amount']=amount;
                            document.getElementById("totalPrice"+i+"").innerHTML=items[i]['amount']*items[i]['price'];
                        }
                    listItems()
                }) 
            }
        }
        function totalPrice(){
            var totalPrice=0
            for(i=0;i<items.length;i++)
                totalPrice+=items[i]['amount']*(items[i]['price']- items[i]['price']/100*items[i]['discount']);
            document.getElementById('totalPriceAll').innerHTML=totalPrice
            document.getElementById('totalPriceModal').innerHTML=totalPrice
        }
        function deleteFromCart(idItem, idSize){
            $.post("tasks.php?task=delectItemFromCart",{idItem: idItem, idSize:idSize},function(e){
                alert(e);
                if(e=="Artikal je uspešno uklonjen iz korpe!")
                for(i=0;i<=items.length;i++){
                    if(items[i]!=undefined){
                        if(items[i]['idItem']==idItem && items[i]['idSize']==idSize)
                            items.splice(i,1);
                    }
                }
                listItems()
            })
        }
        function listItems(){
            str=""; strModal=""; 
                if(items.length>0)
                    str+='<tr class="row underline text-left m-0 p-0 w-100"> <td class="col-3 col-lg-5 pl-0 border-0">ARTIKAL</td><td class="col-2 pl-0 border-0">CENA</td><td class="col-lg-1 col-3 pl-0 border-0">KOL.</td><td class="col-2 pl-0 border-0">TOTAL</td></tr>';
                for(i=0;i<items.length;i++){
                    if(items[i]!=undefined){
                        str+='<tr class="row underline ml-0 pl-0 w-100"><td class="col-3 col-lg-5 m-0 p-0 row border-0"><img src="../pictures/'+items[i]['urlPicture']+'" alt="" class="p-0 col-12 col-lg-4">';
                        str+='<p class="col-lg-8 col-12 align-self-center p-0"><a href="item.php?item='+items[i]['idItem']+'" class="text-dark align-self-center">'+items[i]['nameItem']+'</a><br>Veličina: '+items[i]['size']+'</p></td>';
                        strModal+='<tr class="ml-0 pl-0 w-100 row"><td class="col-5 col-md-6 border-0 m-0 align-self-center"><div class="row align-self-center"><img src="../pictures/'+items[i]['urlPicture']+'" alt="" class="p-0 col-12 col-md-4 img align-self-center"><span class="col-12 col-md-7 text-center align-self-center"><small class="font-weight-bold">'+items[i]['nameItem']+'<br>Veličina: '+items[i]['size']+'</small></span></div></td>';
                        if(items[i]['discount']>0){
                            price=items[i]['price']*((100-parseInt(items[i]['discount']))/100);
                            str+='<td class="m-0 p-0 pl-1 col-2 align-self-center border-0"><del>'+items[i]['price']+'</del> <span id="price'+items[i]['idItem']+'">'+price+'</span>rsd</td>';
                            strModal+='<td class="m-0 p-0 col-2 border-0 align-self-center pb-4"><del>'+items[i]['price']+'</del><br> '+price+'rsd</td>';
                        }
                        else{
                            price=items[i]['price'];
                            str+='<td class="p-0 m-0 pl-1 col-2 align-self-center border-0"><span id="price'+items[i]['idItem']+'">'+price+'</span>rsd</td>';
                            strModal+='<td class="p-0 m-0 col-2 border-0 align-self-center">'+price+'rsd</td>';
                        }
                        price=items[i]['amount']*price;
                        str+='<td class="col-3 col-lg-1 text-left border-0 p-0 m-0 text-left align-self-center"><input type="number" onchange="changeAmountOfItem('+items[i]['idItem']+','+items[i]['idSize']+',this)" id="input'+items[i]['idItem']+'" class="text-center p-0 ml-md-0 ml-4" value="'+items[i]['amount']+'" max="'+items[i]['maximum']+'" min="1"></td><td class="m-0 p-0 col-3 align-self-center border-0"><span id="totalPrice'+i+'">'+price+'</span>rsd</td><td class="col-1 p-0 m-0 align-self-center border-0"><a class="text-dark" onclick="deleteFromCart('+items[i]['idItem']+','+items[i]['idSize']+')"><i class="fa fa-trash fa-2x text-danger" aria-hidden="true""></i></a></td></tr>';
                        strModal+='<td class="col-2 border-0 p-0 m-0 text-left align-self-center"><b>x'+items[i]['amount']+'=</b></td><td class="m-0 p-0 col-2 border-0 align-self-center">'+price+'rsd</td></tr>';      
                    }
                }
                if(items.length>0){
                    document.getElementById('items').innerHTML=str+'<tr class="row text-right mr-2"><td class="border-0 col-12 font-weight-bold">UKUPNA CENA: <span id="totalPriceAll"></span>rsd</td></tr>';
                    document.getElementById('itemsModal').innerHTML=strModal
                }
                else 
                    document.getElementById('items').innerHTML='<div class="alert col-12 p-3 mt-3 alert-dismissible fade show" style="background-color: rgba(54, 201, 170,0.3)" role="alert"><strong>Vaša korpa je prazna!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        
            enableShopping();
            if(items.length>0)    
                totalPrice();
        }
        function enableShopping(){
            if(items.length>0)
                document.getElementById("btnBuy").style.visibility="visible";
            else
                document.getElementById("btnBuy").style.visibility="hidden";   
        }
        $('#purchase').click(function(){
            $.post("tasks.php?task=purchase",function(e){
                alert(e);
                if(e=="Kupovina je uspešno obavljena!") {
                    items=[];
                    listItems()
                }
            })
        })
        $(document).ready(function(){
            check=<?php  echo json_encode(isset($_COOKIE['Allowed']));  ?>;
            if(!check)
                $("#modalCookies").modal("show");
        })
        enableShopping();
    </script> 
</body>
</html>