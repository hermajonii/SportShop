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
    <title>Artikli</title>
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
    <link rel="stylesheet" href= "../css/font-awesome-4.7.0/css/font-awesome.css">
    <script src= "../css/bootstrap-4.3.1-dist/js/bootstrap.bundle.js"> </script>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class='container-fluid wrapper flex-grow-1'>
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
                        $result=getCategories(); $categories=[];
                        if($result)
                            for($i=0;$i<mysqli_num_rows($result);$i++){
                                $row=mysqli_fetch_assoc($result);
                                if($row['amount']>0){
                                    array_push($categories,$row);
                                    echo '<li class="nav-item slide p-1"><a class="nav-link m-0 px-0" href="items.php?category='.$row['idCategory'].'">'.strtoupper($row['nameCategory']).'</a></li>';
                                }
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
        <div class='row'>
            <div class='col-12 col-lg-2' style='overflow-y:scroll; height:50vh'>
                <div class='row mt-3'>
                    <div id="accordion1" class='w-100 col-lg-12'>
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h6 class="mb-0 w-100" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Kategorija
                                </h6>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion1">
                                <div class="card-body p-0">
                                    <?php
                                        $subcategories=[];
                                        for($i=0;$i<count($categories);$i++){
                                            $row=$categories[$i];
                                            echo '<div class="card"><div class="card-header p-1 text-left" id="heading'.$row['idCategory'].'"><p class="text-left mb-0 p-2"><a href="items.php?category='.$row['idCategory'].'" class="text-dark">'.$row['nameCategory'].' ('.$row['amount'].')</a><button class="btn btn-link pt-0 float-right align-self-center" data-toggle="collapse" data-target="#collapse'.$row['idCategory'].'" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-angle-down text-dark"></i></button></p></div><div id="collapse'.$row['idCategory'].'" class="collapse" aria-labelledby="headingOne" data-parent="#collapseOne"><div class="card-body p-0 pl-1"><ul class="text-decoration-none list-unstyled ml-1">';
                                        
                                            $con=getConnection();
                                            if($con){
                                                $resultSub=mysqli_query($con, "call sp_showSubcategory(".$row['idCategory'].")");
                                                if(!mysqli_error($con)){
                                                    if(mysqli_num_rows($resultSub)>0){
                                                        for($j=0;$j<mysqli_num_rows($resultSub);$j++){
                                                            $rowSub=mysqli_fetch_assoc($resultSub);
                                                            if($rowSub['amount']>0){
                                                                array_push($subcategories,$rowSub);
                                                                echo "<li><a class='text-decoration-none text-dark' href='items.php?subcategory=".$rowSub['idSubcategory']."'>".$rowSub['nameSubcategory']." (".$rowSub['amount'].")</a></li>";
                                                            }
                                                        }
                                                    }
                                                }
                                                closeConnection($con);
                                            }
                                            echo "</ul></div></div></div>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="accordion2" class='w-100 col-lg-12'>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="mb-0 w-100" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    Pol
                                </h6>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion2">
                                <div class="card-body p-0">
                                    <ul class="text-decoration-none list-unstyled ml-1 m-0" id='listGender'>
                                        <li class='p-2'><input type='checkbox' id="male" value="male"> Muškarci</a></li>
                                        <li class='p-2'><input type='checkbox' id="female" value="female"> Žene</a></li>
                                        <li class='p-2'><input type='checkbox' id="unisex" value="unisex"> Unisex</a></li>
                                        <li class='p-2'><input type='checkbox' id="children" value="children"> Deca</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="accordion3" class='w-100 col-lg-12'>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="mb-0 w-100" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    Brend
                                </h6>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion3">
                                <div class="card-body p-0">
                                    <ul class="text-decoration-none list-unstyled ml-1 m-0" id='listBrand'>
                                        <?php
                                            $resultBrands=getBrands();
                                            if($resultBrands)
                                                for($i=0;$i<mysqli_num_rows($resultBrands);$i++){
                                                    $row=mysqli_fetch_assoc($resultBrands);
                                                    echo "<li class='p-2'><input type='checkbox' id='".$row['idBrand']."' value='".$row['idBrand']."'> ".$row['nameBrand']."</a></li>";    
                                                }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="accordion4" class='w-100 col-lg-12'>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="mb-0 w-100" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                    Popust
                                </h6>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion4">
                                <div class="card-body p-0">
                                    <ul class="text-decoration-none list-unstyled ml-1 m-0" id='listDiscount'>
                                        <?php
                                            $con=getConnection();
                                            if($con){
                                                $resultDiscounts=mysqli_query($con,"select * from view_showdiscounts");
                                                if(!mysqli_error($con)){
                                                    for($i=0;$i<mysqli_num_rows($resultDiscounts);$i++){
                                                        $row=mysqli_fetch_assoc($resultDiscounts);
                                                        echo "<li class='p-2'><input type='checkbox' id='".$row['discount']."' value='".$row['discount']."'> ".$row['discount']."%</a></li>";    
                                                    }
                                                }
                                                else 
                                                    echo "There has been a mistake!";
                                                closeConnection($con);
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="accordion5" class='w-100 col-lg-12'>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h6 class="mb-0 w-100" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                    Veličina
                                </h6>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion5">
                                <div class="card-body p-0">
                                    <ul class="text-decoration-none list-unstyled ml-1 m-0" id='listSize'>
                                        <?php
                                            $con=getConnection();
                                            if($con){
                                                $resultSizes=mysqli_query($con,"select * from sizes");
                                                if(!mysqli_error($con)){
                                                    for($i=0;$i<mysqli_num_rows($resultSizes);$i++){
                                                        $row=mysqli_fetch_assoc($resultSizes);
                                                        echo "<li class='p-2'><input type='checkbox' id='size".$row['idSize']."' value='".$row['idSize']."'> ".$row['nameSize']."</a></li>";    
                                                    }
                                                }
                                                else 
                                                    echo "There has been a mistake!";
                                                closeConnection($con);
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class='btn-block btn font-weight-bold'>
                        <a href="items.php" class='text-dark'>Poništi filtere</a>
                    </button>
                </div>
            </div>
            <div class='col-12 col-lg-10'>
                <?php
                    if(isset($_GET['category'])){
                        for($i=0;$i<count($categories);$i++)
                            if($categories[$i]['idCategory']==$_GET['category'])
                                echo "<h2 class='p-2'>".strtoupper($categories[$i]['nameCategory'])."</h2>";
                    }
                    elseif(isset($_GET['subcategory'])){
                        for($i=0;$i<count($subcategories);$i++)
                            if($subcategories[$i]['idSubcategory']==$_GET['subcategory'])
                                echo "<h2 class='p-2'>".strtoupper($subcategories[$i]['nameSubcategory'])."</h2>";
                    }
                ?>
                <p class='p-2' > Sortiraj po ceni
                    <select name="selectPrice" id="selectPrice" class='p-2'>
                        <option value="0">RSD</option>
                        <option value="1">rastuće</option>
                        <option value="2">opadajuće</option>
                    </select>
                </p>
                <div class='pages text-right m-0 p-0' id='pages' tabindex="-1"></div>
                <div class='row mx-1 mt-3' id="items" >
                    <?php
                        $con=getConnection();
                        if($con){
                            $cat=-1; $str="";
                            if(isset($_GET['category'])){
                                $cat=$_GET['category'];
                                $str="call sp_showItems(".$cat.",-1)";
                            }
                            elseif(isset($_GET['subcategory'])){
                                $subcat=$_GET['subcategory'];
                                $str="call sp_showItems(-1,".$subcat.")";
                            }
                            elseif (!isset($_GET['category']) && !isset($_GET['subcategory'])) 
                                $str="call sp_showItems(-1,-1)";
                            //getting items from url
                            $items=[];
                            $resultItems=mysqli_query($con, $str);
                            if(!mysqli_error($con)){
                                for($i=0;$i<mysqli_num_rows($resultItems);$i++){
                                    $resultItem=mysqli_fetch_assoc($resultItems);
                                    array_push($items,$resultItem);
                                }
                            }
                            else 
                                echo "There has been a mistake!";

                            //adding sizes to every item
                            for($i=0;$i<count($items);$i++){
                                $con1=getConnection();
                                if($con1){
                                    $rows=mysqli_query($con1,"SELECT idSize FROM belonging_sizes_items WHERE idItem=".$items[$i]['idItem'].";");
                                    if(!mysqli_error($con1)){
                                        $items[$i]['sizes']=[];
                                        for($j=0;$j<mysqli_num_rows($rows);$j++){
                                            $row=mysqli_fetch_assoc($rows);
                                            array_push($items[$i]['sizes'],$row['idSize']);
                                        }
                                    }
                                    else 
                                        echo "There has been a mistake!";
                                    closeConnection($con1);
                                }
                            }
                            closeConnection($con);
                        }
                    ?>
                </div>
                
                <p id='found' class='text-center small'></p>
                <div class='pages text-right m-0 p-0 mb-4'></div>
            </div>
        </div>
    </div>
    <footer class='page-footer mx-3'>
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
    <script type="text/javascript">
        items= <?php  echo json_encode($items);  ?>;
        items1= <?php  echo json_encode($items);  ?>;
        filters={'gender':[],'idBrand':[],'discount':[],'size':[],'category':[],'subcategory':[],'q':[]} 
        //prva tri filtera se mogu zajedno primenjivati
        var userFilters=3;
        var itemsPerPage=8;
        var page=1;
        $(document).ready(function(){
            takeUrlAndFilterItems();
            $("[data-toggle='tooltip']").tooltip();
            check=<?php  echo json_encode(isset($_COOKIE['Allowed']));  ?>;
            if(!check)
                $("#modalCookies").modal("show");
        })
        $('input[type="checkbox"]').click(function(){
            //ubacuje u filtere čekirani checkbox
            if(this.parentNode.parentNode.id=='listGender'){
                if(this.checked){
                    if(filters['gender'].indexOf(this.value)==-1)
                        filters['gender'].push(this.value)
                }
                else
                    filters['gender'].pop(this.value)
            }
            else if(this.parentNode.parentNode.id=='listBrand'){
                if(this.checked){
                    if(filters['idBrand'].indexOf(this.value)==-1)
                        filters['idBrand'].push(this.value)
                }
                else
                    filters['idBrand'].pop(this.value)
            }
            else if(this.parentNode.parentNode.id=='listDiscount'){
                if(this.checked){
                    if(filters['discount'].indexOf(this.value)==-1)
                        filters['discount'].push(this.value)
                }
                else
                    filters['discount'].pop(this.value)
            }
            else if(this.parentNode.parentNode.id=='listSize'){
                if(this.checked){
                    if(filters['size'].indexOf(this.value)==-1)
                        filters['size'].push(this.value)
                }
                else
                    filters['size'].pop(this.value)
            }
            makeNewUrl();
        })
        $('#selectPrice').change(function(){
            //sortiranje artikala
            option=document.getElementById("selectPrice").value;
            if(option!="0"){
                for(i=0;i<items1.length-1;i++){
                    for(j=i+1;j<items1.length;j++){ 
                        priceFirst=parseFloat(items1[i]['price'])- parseFloat(items1[i]['price'])/100*parseFloat(items1[i]['discount']); 
                        priceSecond=parseFloat(items1[j]['price'])- parseFloat(items1[j]['price'])/100*parseFloat(items1[j]['discount']);
                        if(option=="1"){
                            if(parseFloat(priceFirst)>parseFloat(priceSecond)){
                                var item=[]
                                item=items1[j];
                                items1[j]=items1[i];
                                items1[i]=item;
                            }
                        }
                        if(option=="2"){
                           if(parseFloat(priceFirst)<parseFloat(priceSecond)){
                                var item=[]
                                item=items1[j];
                                items1[j]=items1[i];
                                items1[i]=item;
                            }
                        }
                    }
                }
                writeItems(items1,1);
            }
        })
        function makeNewUrl(){
            //novi url od filtera
            beginingUrl=window.location.href.split("?")[0];
            for(i=0;i<Object.keys(filters).length;i++){
                if(Object.values(filters)[i].length>0){
                    if(beginingUrl.indexOf("?")==-1)
                        beginingUrl+="?"+ Object.keys(filters)[i]+"="+Object.values(filters)[i]
                    else
                        beginingUrl+="&"+ Object.keys(filters)[i]+"="+Object.values(filters)[i]
                }
            }
            //promena trenutnog url-a
            window.history.pushState('items', 'ITEMS', beginingUrl);
            takeUrlAndFilterItems()
        }
        function takeUrlAndFilterItems(){
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            //promena filtera od url parametara
            for(i=0;i<Object.keys(filters).length;i++){
                if(urlParams.get(Object.keys(filters)[i])!=null){
                    arr=urlParams.get(Object.keys(filters)[i]).split(',');
                    for(j=0;j<arr.length;j++){
                        if(filters[Object.keys(filters)[i]].indexOf(arr[j])==-1)
                            filters[Object.keys(filters)[i]].push(arr[j]);
                    }
                }
            }
            var check=[]; items1=[];
            //nova lista za filtriranje
            for(i=0;i<items.length;i++)
                items1.push(items[i]);
            //filtriranje
            for(i=0;i<items1.length;i++){
                chk=0
                //user filteri
                for(j=0;j<userFilters;j++){
                    if(Object.values(filters)[j].length>0){
                        if(Object.values(filters)[j].length>1){
                            for(k=0;k<Object.values(filters)[j].length;k++){
                                document.getElementById(Object.values(filters)[j][k]).checked=true;
                                if(items1[i][Object.keys(filters)[j]] == Object.values(filters)[j][k])
                                    chk++;
                            }
                        }   
                        else{
                            document.getElementById(Object.values(filters)[j][0]).checked=true;
                            if(items1[i][Object.keys(filters)[j]] == Object.values(filters)[j][0])
                                chk++;
                        }
                    }
                    else
                        chk++;
                }
                if(chk<userFilters)
                    check.push(i);
                //search
                if(filters['q'].length>0){
                    name=items1[i]['nameItem'];
                    if(name.indexOf(filters['q'][0].toUpperCase())==-1)
                        if(check.indexOf(i)==-1)
                            check.push(i);
                }
                //sizes
                if(filters['size'].length>0){
                    chkSize=0;
                    for(j=0;j<items[i]['sizes'].length;j++)
                        for(k=0;k<filters['size'].length;k++){
                            document.getElementById("size"+filters['size'][k]).checked=true;
                            if(items[i]['sizes'][j]==filters['size'][k])
                                chkSize=1;
                        }   
                    if(chkSize==0)
                        if(check.indexOf(i)==-1)
                            check.push(i);
                }
            }
            //izbacivanje onih koji ne odgovaraju
            for(i=0;i<check.length;i++)
                items1.splice(check[i]-i,1);
                
            writeItems(items1,1);
        }
        function writeItems(items1, page){
            //izlistavanje proizvoda
            if(((itemsPerPage*(page-1))+itemsPerPage)<items1.length)
                count=((itemsPerPage*(page-1))+itemsPerPage);
            else 
                count=items1.length;
            str="";
            for(i=(itemsPerPage*(page-1));i<count;i++){
                str+='<div class="card col-12 col-sm-4 col-md-3 text-center p-0 mb-3 mt-0 item"><a href="item.php?item='+items1[i]['idItem']+'" class="text-dark"><div class="card-body p-1 mb-2">';
                if(items1[i]['discount']>0)
                    if(items1[i]['discount']>20)
                        str+='<h3 class="text-dark bg-danger position-absolute t-0 p-1"><span class="badge p-2 text-light">-'+items1[i]['discount']+'%</span></h3>';
                    else 
                    str+='<h3 class="text-dark badgeBlue position-absolute t-0 p-1"><span class="badge p-2 text-light">-'+items1[i]['discount']+'%</span></h3>';
                str+='<img class="card-img-top p-3" src="../pictures/'+items1[i]['urlPicture']+'" alt="Card image cap" style="height:60%"><h6 class="card-title font-weight-bold p-2 mb-0" style="height:3rem; color: rgb(54, 201, 170)">'+items1[i]['nameItem']+'</h6></a><a href="items.php?idBrand='+items1[i]['idBrand']+'" class="col-12"><img src="../pictures/'+items1[i]['pictureBrand']+'" class="w-25"></a>';  
                if(items1[i]['discount']>0)
                    str+='<h6 class="col-12 border-0 pl-0 pt-4"><del class="text-secondary">'+items1[i]['price']+'RSD</del> <b> '+(items1[i]['price']*((100-parseInt(items1[i]['discount']))/100)).toFixed(2)+' RSD </b></h6>';  
                else
                    str+='<h6 class="col-12 border-0 pl-0 pt-4"><b>'+parseFloat(items1[i]['price']).toFixed(2)+' RSD</b></h6>';
                str+='</div></div>';
            }
            document.getElementById('items').innerHTML=str;
            if(items1.length==1)
                document.getElementById('found').innerHTML=items1.length+" proizvod pronađen";
            else    
                document.getElementById('found').innerHTML=items1.length+" proizvoda pronađeno";

            writePages(page);
        }
        function writePages(currPage){
            str="";
            if(Math.ceil(items1.length/itemsPerPage)>1){
                //strelice unazad
                if(currPage!=1){
                    str+="<button class='bg-danger text-light py-2' onclick='firstPage()' data-toggle='tooltip' title='Prva strana'><i class='fa fa-angle-double-left fa-lg' aria-hidden='true'></i></button>"
                    if(currPage!=2)
                        str+="<button class='bg-danger text-light py-2' onclick='previousPage()' data-toggle='tooltip' title='Prethodna strana'><i class='fa fa-angle-left fa-lg' aria-hidden='true'></i></button>"
                }
                //da se vidi i jedna stranica pre, ako je poslednja, dve stranice pre
                if(currPage==1)
                    newCurr=currPage;
                else if(currPage==Math.ceil(items1.length/itemsPerPage))
                    newCurr=currPage-2;
                else 
                    newCurr=currPage-1;
                //ispis stranica
                for(i=newCurr;i<newCurr+3;i++)
                    if(Math.ceil(items1.length/itemsPerPage)>=i)
                        if(i==currPage)
                            str+="<button class='py-2 font-weight-bold pagesActive'>"+i+"</button>";
                        else
                            str+="<button class='bg-danger text-light py-2 font-weight-bold' onclick='setPage("+i+")'>"+i+"</button>";
                //strelice unapred
                if(currPage!=Math.ceil(items1.length/itemsPerPage)){
                    if(currPage!=Math.ceil(items1.length/itemsPerPage)-1)
                        str+="<button class='bg-danger text-light py-2' onclick='nextPage()' data-toggle='tooltip' title='Sledeca strana'><i class='fa fa-angle-right fa-lg' aria-hidden='true'></i></button>"          
                    str+="<button class='bg-danger text-light py-2'  onclick='lastPage()' data-toggle='tooltip' title='Poslednja strana'><i class='fa fa-angle-double-right fa-lg' aria-hidden='true'></i></button>"
                }
            }
            els=document.getElementsByClassName("pages");
            for(i=0;i<els.length;i++)
                els[i].innerHTML=str;
            
            document.getElementById("pages").focus();
        }
        function nextPage(){
            page=page+1;
            writeItems(items1,page);
        }
        function previousPage(){
            page=page-1;
            writeItems(items1,page);
        }
        function lastPage(){
            page=Math.ceil(items1.length/itemsPerPage);
            writeItems(items1,page);
        }
        function firstPage(){
            page=1;
            writeItems(items1,1);
        }
        function setPage(newPage){
            page=newPage;
            writeItems(items1,page);
        }
    </script> 
</body>
</html>
