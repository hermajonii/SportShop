
<?php 
    require_once("functions.php");
    if(!isset($_SESSION['user'])){
        header("Location: index.php");
    }
    else{ 
        $user=unserialize($_SESSION['user']);
        if($user->role!='administrator')
            header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="../css/bootstrap-4.3.1-dist/css/bootstrap.css">
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href= "../css/style.css?ts=<?=time()?>">
    <link rel="stylesheet" href= "../css/font-awesome-4.7.0/css/font-awesome.css">
    <script src= "../css/bootstrap-4.3.1-dist/js/bootstrap.js"> </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row navbar m-0" style='z-index:2 !important'>
            <ul class="ml-auto nav-flex-icons m-0 ">
                <?php
                    if(isset($_SESSION['user'])){
                        echo '<li class="nav-item list-inline-item dropdown m-0"><a class="nav-link dropdown-toggle p-2" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-lg"></i></a><div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333"><a class="dropdown-item nav-link" href="tasks.php?task=logOut">Odjavi se</a></div></li>';
                    }
                ?>
            </ul>
        </div>
        <div class="tabs mb-5 px-0 tabsAdmin">
            <ul class="nav nav-tabs bg-dark">
                <li class="nav-item"><a href="#menu1" class="nav-link active" data-toggle="tab">Korisnici</a></li>
                <li class="nav-item"><a href="#menu2" class="nav-link" data-toggle="tab">Artikli</a></li>
                <li class="nav-item"><a href="#menu3" class="nav-link" data-toggle="tab">Računi</a></li>
                <li class="nav-item"><a href="#menu4" class="nav-link" data-toggle="tab">Brendovi</a></li>
                <li class="nav-item"><a href="#menu5" class="nav-link" data-toggle="tab">Kategorije</a></li>
                <li class="nav-item"><a href="#menu6" class="nav-link" data-toggle="tab">Potkategorije</a></li>
            </ul>
            <div class="tab-content ml-1">
                <div class="tab-pane fade show active ml-2" id="menu1">
                    <div class='row'>
                        <div class='col-12 col-md-4 p-1'>
                            <div class='p-2'>
                                <label for="searchUsers" class='p-2 m-2'>Korisnik:</label>
                                <input type='text' id='searchUsers' class='p-1'>
                                <button class='btn btn-warning' onclick="searchUsers()">Pretraži</button>
                                <button class='btn btn-block btnShop' onclick="writeUsers(users); clearInputsUsers();">RESETUJ</button>
                                <h2>Izaberi korisnika:</h2>
                            </div>
                            <div style="overflow: scroll; height:30rem">
                                <?php
                                    $con=getConnection(); $users=[];
                                    if($con){
                                        $rows=mysqli_query($con, "SELECT * FROM users");
                                        if(!mysqli_error($con)){
                                            echo "<select id='selectUsers' name='selectUsers' style='overflow-y: auto; overflow-x:scroll' size='".(mysqli_num_rows($rows)+1)."' class='w-100' onchange='changeSelectUsers(this)'>";
                                            for($i=0;$i<mysqli_num_rows($rows);$i++){
                                                $row=mysqli_fetch_assoc($rows);
                                                array_push($users, $row);
                                                echo '<option value="'.$row['idUser'].'">Korisničko ime: '.$row['username'].'; Ime: '.$row['firstName'].' '.$row['lastName'].'</option>';
                                            }  
                                            echo "</select>";
                                        }
                                        else 
                                            echo "There has been a mistake!";
                                        closeConnection($con);
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 mt-0 pl-0">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#changeUsr" class="nav-link active" data-toggle="tab">Ažuriraj korisnika</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#addUsr" class="nav-link" data-toggle="tab">Dodaj korisnika</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="changeUsr">
                                    <label for="username" class='p-2 m-2 w-25'>Korisničko ime:</label> 
                                    <input class='p-1' type="text" id='username' disabled>
                                    <br>
                                    <label for="password" class='p-2 m-2 w-25'>Lozinka:</label> 
                                    <input class='p-1' type="password" id='password' disabled> <input type="checkbox" onclick='changeVisibility(this)'>
                                    <br>
                                    <label for="firstName" class='p-2 m-2 w-25'>Ime:</label> 
                                    <input class='p-1' type="text" id='firstName'>
                                    <br>
                                    <label for="lastName" class='p-2 m-2 w-25'>Prezime:</label> 
                                    <input class='p-1' type="text" id='lastName'>
                                    <br>
                                    <label for="address" class='p-2 m-2 w-25'>Adresa:</label> 
                                    <input class='p-1' type="text" id='address'>
                                    <br>
                                    <label for="email" class='p-2 m-2 w-25'>E-mail:</label> 
                                    <input class='p-1' type="email" id='email'>
                                    <br>
                                    <label for="phone" class='p-2 m-2 w-25'>Broj telefona:</label> 
                                    <input class='p-1' type="phone" id='phone'>
                                    <br>
                                    <label for="role" class='p-2 m-2 w-25'>Uloga:</label> 
                                    <select name="role" id="role">
                                        <option value="0">--izaberi ulogu--</option>
                                        <option value="user">korisnik</option>
                                        <option value="administrator">administrator</option>
                                    </select>
                                    <br>
                                    <label for="active" class='p-2 m-2 w-25'>Aktivan:</label>
                                    <select name="active" id="active">
                                        <option value="-1">--izaberi aktivnost--</option>
                                        <option value="1">Da</option>
                                        <option value="0">Ne</option>
                                    </select>
                                    <br>
                                    <button class="btn btn-warning" onclick='changeSelectedUser()'>Promeni selektovanog korisnika</button>
                                </div>
                                <div class="tab-pane fade show" id="addUsr">
                                    <label for="firstNameAdd" class='p-2 m-2 w-25'>Ime:</label> 
                                    <input class='p-1' type="text" id='firstNameAdd'>
                                    <br>
                                    <label for="lastNameAdd" class='p-2 m-2 w-25'>Prezime:</label> 
                                    <input class='p-1' type="text" id='lastNameAdd'>
                                    <br>
                                    <label for="addressAdd" class='p-2 m-2 w-25'>Adresa:</label> 
                                    <input class='p-1' type="text" id='addressAdd'>
                                    <br>
                                    <label for="emailAdd" class='p-2 m-2 w-25'>E-mail:</label> 
                                    <input class='p-1' type="email" id='emailAdd'>
                                    <br>
                                    <label for="phoneAdd" class='p-2 m-2 w-25'>Broj telefona:</label> 
                                    <input class='p-1' type="phone" id='phoneAdd' placeholder="06x/xxx-xxx(x)">
                                    <br>
                                    <label for="usernameAdd" class='p-2 m-2 w-25'>Korisničko ime:</label> 
                                    <input class='p-1' type="text" id='usernameAdd'>
                                    <br>
                                    <label for="passwordAdd" class='p-2 m-2 w-25'>Lozinka:</label> 
                                    <input class='p-1' type="text" id='passwordAdd'>
                                    <br>
                                    <label for="roleAdd" class='p-2 m-2 w-25'>Uloga:</label> 
                                    <select name="roleAdd" id="roleAdd">
                                        <option value="0">--izaberi ulogu--</option>
                                        <option value="user">korisnik</option>
                                        <option value="administrator">administrator</option>
                                    </select>
                                    <br>
                                    <label for="activeAdd" class='p-2 m-2 w-25'>Aktivan:</label>
                                    <select name="activeAdd" id="activeAdd">
                                        <option value="-1">--izaberi aktivnost  --</option>
                                        <option value="1">Da</option>
                                        <option value="0">Ne</option>
                                    </select>
                                    <br>
                                    <button class="btn btn-success" onclick='addUser()'>Dodaj korisnika</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="menu2">
                    <div class='row m-0'>
                        <div class='col-12 col-md-4 p-1'>
                            <div class='p-1'>
                                <label for="searchItems" class='p-2 m-2'>Naziv artikla:</label>
                                <input type='text' id='searchItems' class='p-1'> 
                                <button class='btn btn-warning' onclick='searchItems(this)'>Pretraži</button>
                                <button class='btn btn-block btnShop' onclick="clearInputsItems(); writeItems(items); ">RESETUJ</button>
                                <h2>Izaberi artikal:</h2>
                            </div>
                            <div style="overflow-y: scroll; height:30rem">
                                <?php
                                    $con=getConnection(); $items=[];
                                    if($con){
                                        $rows=mysqli_query($con, "select * from items");
                                        if(!mysqli_error($con)){
                                            echo "<select id='selectItems' name='selectItems' style='overflow-y: auto; overflow-x:scroll' size='".(mysqli_num_rows($rows)+1)."' onchange='changeSelectItems(this)'>";
                                            for($i=0;$i<mysqli_num_rows($rows);$i++){
                                                $row=mysqli_fetch_assoc($rows);
                                                array_push($items, $row);
                                                echo '<option value="'.$row['idItem'].'">Naziv: '.$row['nameItem'].'; Cena: '.$row['price'].'; Pol: '.$row['gender'].'</option>';
                                            }  
                                            echo "</select>";
                                        }
                                        else 
                                            echo "There has been a mistake!";
                                        closeConnection($con);
                                    }
                                    //sve fotografije za svaki artikal
                                    for($i=0;$i<count($items);$i++){
                                        $items[$i]['images']=[];
                                        $con=getConnection();
                                        if($con){
                                            $rows=mysqli_query($con,'select * from pictures where idItem='.$items[$i]['idItem'].';');
                                            if(!mysqli_error($con)){        
                                                for($j=0;$j<mysqli_num_rows($rows);$j++){
                                                    $row=mysqli_fetch_assoc($rows);
                                                    array_push($items[$i]['images'],['idPicture'=>$row['idPicture'], 'urlPicture'=>$row['urlPicture']]);
                                                }
                                            } 
                                            else 
                                                echo "There has been a mistake!";
                                            closeConnection($con);
                                        }
                                    }    
                                    //sve potkategorije za svaki artikal
                                    for($i=0;$i<count($items);$i++){
                                        $items[$i]['subcategories']=[];
                                        $con=getConnection();
                                        if($con){
                                            $rows=mysqli_query($con,'select * from belonging_items_subcategories where idItem='.$items[$i]['idItem'].';');
                                            if(!mysqli_error($con)){        
                                                for($j=0;$j<mysqli_num_rows($rows);$j++){
                                                    $row=mysqli_fetch_assoc($rows);
                                                    array_push($items[$i]['subcategories'],$row['idSubcategory']);
                                                }
                                            } 
                                            else 
                                                echo "There has been a mistake!";
                                            closeConnection($con);
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-8 mt-0 px-0'>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="#item" class="nav-link active" data-toggle="tab">Ažuriraj artikal</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#size" class="nav-link" data-toggle="tab">Dodaj količinu</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#changeSubcategory" class="nav-link" data-toggle="tab">Promeni potkategorije</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active col-12 mx-0 px-0" id="item">
                                    <label for="itemName" class='p-2 m-2 w-25'>Naziv artikla:</label> 
                                    <input class='p-1 w-50' type="text" id='itemName'>
                                    <br>
                                    <label for="itemPrice" class='p-2 m-2 w-25'>Cena:</label> 
                                    <input class='p-1' type="number" min=1 id='itemPrice'>
                                    <br>
                                    <label for="itemDiscount" class='p-2 m-2 w-25'>Popust:</label> 
                                    <input class='p-1' type="number" min='1' max='40' id='itemDiscount'>
                                    <br>
                                    <label for="selectGender" class='p-2 m-2 w-25'>Pol:</label>&nbsp;
                                    <select name="selectGender" id="selectGender" class='p-1'>
                                        <option value="0">--izaberi pol--</option>
                                        <option value="female">ženski</option>
                                        <option value="male">muški</option>
                                        <option value="unisex">unisex</option>
                                        <option value="children">deca</option>
                                    </select>
                                    <br>
                                    <label for="selectBrand" class='p-2 m-2 w-25'>Brend:</label>&nbsp;
                                    <select name="selectBrand" id="selectBrand" class='p-0 py-2 m-0'>
                                        <option value="0">--izaberi brend--</option>
                                        <?php
                                            $rows=getBrands(); $brands=[];
                                            if($rows)
                                                for($i=0;$i<mysqli_num_rows($rows);$i++){
                                                    $row=mysqli_fetch_assoc($rows);
                                                    echo '<option value="'.$row['idBrand'].'">'.$row['nameBrand'].' - '.$row['pictureBrand'].' ('.$row['amount'].')</option>';
                                                    array_push($brands,$row);
                                                }
                                        ?>
                                    </select>
                                    <br>
                                    <label for="selectActive" class='p-2 m-2 w-25'>Aktivan:</label>&nbsp;
                                    <select name="selectActive" id="selectActive" class='p-1'>
                                        <option value="-1">--izaberi da li je artikal aktivan--</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <br>
                                    <label for="itemImg" class='p-2 m-2 w-25'>Dodaj sliku:</label> 
                                    <input type="file" id='itemImg' name='itemImg' class='ml-1 mb-4' accept="image/png, image/jpeg, image/png, image/jpg" multiple>
                                    <br>
                                    <button class="btn btn-warning" onclick="changeSelectedItem()">Promeni selektovani artikal</button>
                                    <button class="btn btn-success" onclick="addItem()">Dodaj artikal</button>
                                    <div class="alert alert-danger alert-dismissible fade show mt-2 mb-0 font-weight-bold" role="alert">
                                        Oprez! Slike koje dodajete moraju biti u '../pictures/' folderu
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class='col-12 mt-2'>
                                        <div class='row' id='itemImgs'>
                                        </div>
                                    </div>
                                    <button class="btn btn-danger" onclick="deleteSelectedImages()" id='deleteImages' style="visibility:hidden">Obriši selektovane slike</button>
                                </div>
                                <div class="tab-pane fade" id="size">
                                    <label for="selectSize" class='p-2 m-2 w-25'>Izaberi veličinu:</label>&nbsp;
                                    <select name="selectSize" id="selectSize" class='p-1'>
                                        <option value="-1">--izaberi--</option>
                                        <?php
                                            $con=getConnection();
                                            if($con){
                                                $rows=mysqli_query($con, "select * from sizes");
                                                if(!mysqli_error($con)){
                                                    for($i=0;$i<mysqli_num_rows($rows);$i++){
                                                        $row=mysqli_fetch_assoc($rows);
                                                        echo '<option value="'.$row['idSize'].'">'.$row['nameSize'].'</option>';
                                                    } 
                                                }
                                                else 
                                                    echo "There has been a mistake!";
                                                closeConnection($con);
                                            }
                                        ?>
                                    </select>
                                    <br>
                                    <label for="sizeAmount" class='p-2 m-2 w-25'>Količina:</label> 
                                    <input type="number" class='ml-1' id='sizeAmount' min=1>
                                    <br>
                                    <button class="btn btn-success" onclick="addSize()">Dodaj količinu</button>
                                </div>
                                <div class="tab-pane fade" id="changeSubcategory">
                                    <div id='changeSubcategories'>
                                    <?php
                                        $con=getConnection();
                                        if($con){
                                            $rows=mysqli_query($con, "SELECT * FROM view_showsubcategories");
                                            if(!mysqli_error($con)){
                                                $subcategories=[];
                                                for($i=0;$i<mysqli_num_rows($rows);$i++){
                                                    $row=mysqli_fetch_assoc($rows);
                                                    echo '<input type="checkbox" id="subcategory'.$row['idSubcategory'].'" value="'.$row['idSubcategory'].'" class="m-2 p-2"> <span class="m-2">'.$row['nameSubcategory'].'</span><br>';
                                                    array_push($subcategories,$row);
                                                }
                                            }
                                            else 
                                                echo "There has been a mistake!";
                                            closeConnection($con);
                                        }
                                    ?>
                                    </div>
                                    <button class='btn btn-success mt-2' onclick="changeSubcategoriesOfItem()">Sačuvaj promene</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade ml-2" id="menu3">
                    <div class='row'>
                        <div class='col-12 col-md-4 p-1'>
                            <div class='pl-3 row'> 
                                <label for="fromDate" class='col-6 pt-2'>Datum od:</label> <input type='date' id='fromDate' class='col-6 py-1'> <br>
                                <label for="toDate" class='col-6 pt-2'>Datum do:</label> <input type='date' id='toDate' class='col-6 mb-3 py-1'> <br>
                                <label for="fromTime" class='col-6 col-md-3 mb-3 pt-2'>Vreme od:</label> <input type='time' id='fromTime' class='col-6 col-md-3 mb-3 py-1'>
                                <label for="toTime" class='col-6 col-md-3 mb-3 pt-2'>Vreme do:</label> <input type='time' id='toTime' class='col-6 col-md-3 mb-3 py-1'> <br>
                                <button class='btn btn-warning mb-3 ml-1' onclick='searchBills()'>Pretraži</button>
                                <button class='btn btn-block btnShop ml-1' onclick="writeBills(bills)">RESETUJ</button>
                                <h2>Izaberi račun:</h2>
                            </div>
                            <div style="overflow: scroll; height:30rem">
                                <?php
                                    $con=getConnection(); $bills=[];
                                    if($con){
                                        $rows=mysqli_query($con, "SELECT * FROM bills");
                                        if(!mysqli_error($con)){
                                            echo "<select id='selectBills' name='selectBills' style='overflow-y: auto; overflow-x:scroll' size='".(mysqli_num_rows($rows)+1)."' class='w-100' onchange='changeSelectBills(this)'>";
                                            for($i=0;$i<mysqli_num_rows($rows);$i++){
                                                $row=mysqli_fetch_assoc($rows);
                                                array_push($bills, $row);
                                                echo '<option value="'.$row['idBill'].'">Datum: '.$row['billDate'].'; Vreme: '.$row['billTime'].'h; Cena: '.$row['totalPrice'].'rsd</option>';
                                            }  
                                            echo "</select>";
                                        }
                                        else 
                                            echo "There has been a mistake!";
                                        closeConnection($con);
                                    }
                                ?>
                            </div>
                        </div>
                        <div class='col-12 col-md-8'>
                            <h2>Izaberi račun i vidi njegove stavke..</h2>
                            <div id='billItems' style='overflow-y:scroll; height:55vmax; overflow-x:hidden '>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade ml-2" id="menu4">
                    <div class='row'>
                        <div class='col-12 col-md-4 p-1'>
                            <div class='p-2'>
                                <label for="searchBrands" class='p-2 m-2'>Brend:</label>
                                <input type='text' id='searchBrands' class='p-1'> 
                                <button class='btn btn-warning' onclick='searchBrands()'>Pretraži</button>
                                <button class='btn btn-block btnShop' onclick="clearInputsBrands(); writeBrands(brands)">RESETUJ</button>
                                <h2>Izaberi brend:</h2>
                            </div>
                            <div style="overflow: scroll; height:30rem">
                                <?php
                                    echo "<select id='selectBrands' name='selectBrands' style='overflow-y: auto' size='".(count($brands)+1)."' class='w-100' onchange='changeSelectBrands(this)'>";
                                    for($i=0;$i<count($brands);$i++)
                                        echo '<option value="'.$brands[$i]['idBrand'].'">'.$brands[$i]['nameBrand'].' - '.$brands[$i]['pictureBrand'].' ('.$brands[$i]['amount'].')</option>';
                                    echo "</select>";
                                ?>
                            </div>
                        </div>
                        <div class="text-left col-12 col-md-8 m-0 p-1">
                            <label for="brandName" class='p-2 m-2 w-25'>Brend:</label>
                            <input type="text" class="p-1" id='brandName'><br>
                            <label for="brandImg" class='p-2 m-2 w-25'>Postavi sliku:</label> <input type="file" id='brandImg' name='brandImg' class='ml-1 mb-3' accept="image/png, image/jpeg, image/jpg">
                            <br>
                            <button class="btn btn-warning" onclick='changeSelectedBrand()'>Promeni selektovani brend</button>
                            <button class="btn btn-danger text-ligth"onclick='deleteSelectedBrand()'>Obriši selektovani brend</button>
                            <button class="btn btn-success" onclick='addBrand()'>Dodaj brend</button> <br>
                            <div class="alert alert-danger alert-dismissible fade show mt-5 mr-5 font-weight-bold" role="alert">
                               Oprez! Slika koju dodate mora biti u '../pictures/' folderu
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade ml-2" id="menu5">
                    <div class='row'>
                        <div class='col-12 col-md-4 p-1'>
                            <div class='p-2'>
                                <label for="searchCategories" class='p-2 m-2'>Kategorija:</label>
                                <input type='text' id='searchCategories' class='p-1'>
                                <button class='btn btn-warning' onclick='searchCategories()'>Pretraži</button>
                                <button class='btn btn-block btnShop' onclick="clearInputsCategories(); writeCategories(categories)">RESETUJ</button>
                                <h2>Izaberi kategoriju:</h2>
                            </div>
                            <div style="overflow: scroll; height:30rem">
                                <?php   
                                    $rows=getCategories(); $categories=[];
                                    if($rows){
                                        echo "<select id='selectCategories' name='selectCategories' style='overflow-y: auto' size='".(mysqli_num_rows($rows)+1)."' class='w-100' onchange='changeSelectCategories(this)'>";
                                        for($i=0;$i<mysqli_num_rows($rows);$i++){
                                            $row=mysqli_fetch_assoc($rows);
                                            echo '<option value="'.$row['idCategory'].'">'.$row['nameCategory'].' ('.$row['amount'].')</option>';
                                            array_push($categories,$row);
                                        }  
                                        echo "</select>"; 
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="text-left col-12 col-md-8 m-0 p-1">
                            <label for="categoryName" class='p-2 m-2 w-25'>Kategorija:</label>&nbsp;
                            <input type="text" class="p-1" id="categoryName">
                            <br>
                            <button class="btn btn-warning" onclick='changeSelectedCategory()'>Promeni selektovanu kategoriju</button>
                            <button class="btn btn-danger text-ligth" onclick='deleteSelectedCategory()'>Obriši selektovanu kategoriju</button>
                            <button class="btn btn-success" onclick='addCategory()'>Dodaj kategoriju</button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade ml-2" id="menu6">
                    <div class='row'>
                        <div class='col-12 col-md-4 p-1'>
                            <div class='p-2'>
                                <label for="searchSubcategories" class='p-2 m-2'>Potkategorija:</label>
                                <input type='text' id='searchSubcategories' class='p-1'></span>
                                <button class='btn btn-warning' onclick='searchSubcategories()'>Pretraži</button>
                                <button class='btn btn-block btnShop' onclick="clearInputsSubcategories();writeSubcategories(subcategories)">RESETUJ</button>
                                <h2>Izaberi potkategoriju:</h2>
                            </div>
                            <div style="overflow: scroll; height:30rem">
                                <?php
                                    if(count($subcategories)!=0){
                                        echo "<select id='selectSubcategories' name='selectSubcategories' style='overflow-y: auto' size='".(count($subcategories)+1)."' class='w-100' onchange='changeSelectSubcategories(this)'>";
                                        for($i=0;$i<count($subcategories);$i++)
                                            echo '<option value="'.$subcategories[$i]['idSubcategory'].'">'.$subcategories[$i]['nameSubcategory'].' ('.$subcategories[$i]['amount'].')</option>';    
                                        echo "</select>";
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="text-left col-12 col-md-8 m-0 p-1">
                            <label for="subcategoryName" class='p-0'>Potkategorija: </label>&nbsp;
                            <input type="text" class="p-1" id='subcategoryName'>
                            <br>
                            <label for="selectCategory" class='p-2'>Kategorija:</label>&nbsp;
                            <select name="selectCategory" id="selectCategory" class='p-0 ml-1'>
                                <option value="0">--izaberi kategoriju--</option>
                                <?php
                                    for($i=0;$i<count($categories);$i++)
                                        echo '<option value="'.$categories[$i]['idCategory'].'">'.$categories[$i]['nameCategory'].' ('.$categories[$i]['amount'].')</option>';
                                ?>
                            </select>
                            <br>
                            <button class="btn btn-warning" onclick="changeSelectedSubcategory()">Promeni selektovanu potkategoriju</button>
                            <button class="btn btn-danger text-ligth" onclick="deleteSelectedSubcategory()">Obriši selektovanu potkategoriju</button>
                            <button class="btn btn-success" onclick="addSubcategory()">Dodaj potkategoriju</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function validateFileInput(files){
        for(i=0;i<files.length;i++)
            if(files[i].name.split(".")[1]!="jpg" && files[i].name.split(".")[1]!="png" && files[i].name.split(".")[1]!="jpeg")
                return 0;
        return 1;
    }
    
    //users
    var users= <?php echo json_encode($users);?>;
    function changeSelectUsers(obj){
        for(i=0;i<users.length;i++)
            if(users[i]['idUser']==obj.value){
                document.getElementById("firstName").value=users[i]['firstName'];
                document.getElementById("lastName").value=users[i]['lastName'];
                document.getElementById("address").value=users[i]['address'];
                document.getElementById("phone").value=users[i]['phoneNumber'];
                document.getElementById("role").value=users[i]['role'];
                document.getElementById("username").value=users[i]['username'];
                document.getElementById("email").value=users[i]['email'];
                document.getElementById("active").value=users[i]['active'];
                document.getElementById("password").value=users[i]['password'];
            }  
    }
    function searchUsers(){
        pattern = document.getElementById('searchUsers').value;
        newUsers=[];
        for(i=0;i<users.length;i++)
            if(users[i]['firstName'].toUpperCase().includes(pattern.toUpperCase()) || users[i]['lastName'].toUpperCase().includes(pattern.toUpperCase()) || users[i]['username'].toUpperCase().includes(pattern.toUpperCase()))
                newUsers.push(users[i]);
        clearInputsUsers()     
        writeUsers(newUsers);
    }
    function writeUsers(newUsers){
        str="";
        for(i=0;i<newUsers.length;i++)
            str+='<option value="'+newUsers[i]['idUser']+'">Korisničko ime: '+newUsers[i]['username']+'; Ime: '+newUsers[i]['firstName']+' '+newUsers[i]['lastName']+'</option>';
        document.getElementById("selectUsers").innerHTML=str;
        document.getElementById("selectUsers").size=newUsers.length+1
    }
    function clearInputsUsers(){
        document.getElementById("firstName").value="";
        document.getElementById("lastName").value="";
        document.getElementById("address").value="";
        document.getElementById("phone").value="";
        document.getElementById("role").value="0";
        document.getElementById("username").value="";
        document.getElementById("password").value="";
        document.getElementById("email").value="";   
        document.getElementById("active").value="-1";   
        document.getElementById("searchUsers").value="";   
    }
    function changeSelectedUser(){
        idUser=document.getElementById("selectUsers").value;
        firstName=document.getElementById("firstName").value;
        lastName=document.getElementById("lastName").value;
        address=document.getElementById("address").value;
        email=document.getElementById("email").value;
        phoneNumber=document.getElementById("phone").value;
        username=document.getElementById("username").value;
        role=document.getElementById("role").value;
        active=document.getElementById("active").value;
        if(idUser!=""){
            if(firstName.length!=0 && lastName.length!=0 && address.length!=0 && email.length!=0 && phoneNumber.length!=0 && username.length!=0 && role!="0" && active!="-1"){
                check=1;index=-1
                for(i=0;i<users.length;i++){
                    if(users[i]['username']==username && users[i]['idUser']!=idUser)
                        check=0;
                    if(users[i]['idUser']==idUser)
                        index=i;
                }
                if(users[index]['username']==username && users[index]['firstName']==firstName && users[index]['lastName']==lastName && users[index]['address']==address && users[index]['phoneNumber']==phoneNumber && users[index]['role']==role && users[index]['active']==active)
                    alert("Niste promenili selektovanog korisnika!");
                else{
                    if(check==1){
                        $.post("tasks.php?task=changeUser",{idUser:idUser, firstName: firstName, lastName: lastName, address: address, email: email, phoneNumber: phoneNumber, username: username, role: role, active: active},function(e){
                            alert(e);
                            if(e=='Uspešno promenjeno!'){
                                clearInputsUsers();
                                for(i=0;i<users.length;i++)
                                    if(users[i]['idUser']==idUser){
                                        users[i]['firstName']=firstName;
                                        users[i]['lastName']=lastName;
                                        users[i]['address']=address;
                                        users[i]['email']=email;
                                        users[i]['username']=username;
                                        users[i]['role']=role;
                                        users[i]['phoneNumber']=phoneNumber;
                                        users[i]['active']=active;
                                        writeUsers(users);
                                    }
                            }
                        })
                    }
                    else
                        alert("Postoji korisnik sa ovim korisničkim imenom!");
                }
            }
            else 
                alert("Niste uneli sve informacije!");
        }
        else 
            alert ("Niste izabrali korisnika!");
    }
    function addUser(){
        firstName=document.getElementById("firstNameAdd").value;
        lastName=document.getElementById("lastNameAdd").value;
        email=document.getElementById("emailAdd").value;
        phoneNumber=document.getElementById("phoneAdd").value;
        address=document.getElementById("addressAdd").value;
        role=document.getElementById("roleAdd").value;
        active=document.getElementById("activeAdd").value;
        username=document.getElementById("usernameAdd").value;
        password=document.getElementById("passwordAdd").value;
        if(firstName.length!=0 && lastName.length!=0 && email.length!=0 && phoneNumber.length!=0 && address.length!=0 && role!='0' && active!='-1' && username.length!=0 && password.length!=0){
            amount=0
            for(i=0;i<users.length;i++)
                if(users[i]['username'].toUpperCase()==username.toUpperCase())
                    amount++;
            if(amount==0){
                $.post("tasks.php?task=addUser",{firstName: firstName, lastName: lastName, address: address, phoneNumber: phoneNumber, role: role, active: active, username: username, email: email, password: password},function(e){
                    alert(e.split("~")[0]);
                    if(e.split("~")[0]=='Uspešno dodato!'){
                        document.getElementById("firstNameAdd").value="";
                        document.getElementById("lastNameAdd").value="";
                        document.getElementById("emailAdd").value="";
                        document.getElementById("addressAdd").value="";
                        document.getElementById("phoneAdd").value="";
                        document.getElementById("usernameAdd").value="";
                        document.getElementById("passwordAdd").value="";
                        document.getElementById("roleAdd").value="0";
                        document.getElementById("activeAdd").value="-1";
                        users.push(JSON.parse(e.split("~")[1]));
                        writeUsers(users);
                    }
                })
            }
            else 
                alert("Postoji korisnik sa ovim korisničkim imenom!")
        }
        else 
            alert("Niste uneli sve podatke!");
    }
    function changeVisibility(obj){
        if(obj.checked)
            document.getElementById("password").type='text';
        else
            document.getElementById("password").type='password';
    }

    //items    
    var items= <?php echo json_encode($items);?>;
    function changeSelectItems(obj){
        clearInputsItems();
        for(i=0;i<items.length;i++)
            if(items[i]['idItem']==obj.value){
                document.getElementById("itemName").value=items[i]['nameItem'];
                document.getElementById("itemPrice").value=items[i]['price'];
                document.getElementById("itemDiscount").value=items[i]['discount'];
                document.getElementById("selectGender").value=items[i]['gender'];
                document.getElementById("selectBrand").value=items[i]['idBrand'];
                document.getElementById("selectActive").value=items[i]['active'];
                document.getElementById("deleteImages").style.visibility="visible";

                for(j=0;j<items[i]['subcategories'].length;j++){
                    document.getElementById('subcategory'+items[i]['subcategories'][j]).checked=true;
                }
                writeImagesOfSelectedItem(obj.value);
            }
    }
    function writeImagesOfSelectedItem(val){
        for(i=0;i<items.length;i++)
            if(items[i]['idItem']==val){
                str="";
                for(j=0;j<items[i]['images'].length;j++){
                    str+='<div class="col-md-3 col-12 p-2 border border-info m-2"><input type="checkbox" class="align-self-center mr-2" id="checkboxImg'+items[i]['images'][j]['idPicture']+'" value="'+items[i]['images'][j]['idPicture']+'"><img src="../pictures/'+items[i]['images'][j]['urlPicture']+'" class="w-75"  style="height:10rem"></div>';
                }
                document.getElementById("itemImgs").innerHTML=str;
            }
    }
    function clearInputsItems(){
        document.getElementById("itemName").value="";
        document.getElementById("itemPrice").value="";
        document.getElementById("itemDiscount").value="";
        document.getElementById("selectGender").value="0";
        document.getElementById("selectBrand").value="0";
        document.getElementById("itemImgs").innerHTML="";
        document.getElementById("selectActive").value="-1";
        document.getElementById("itemImg").value='';
        document.getElementById("searchItems").value='';
        document.getElementById("deleteImages").style.visibility="hidden";
        chks=$("input[id^='subcategory']");
        for(i=0;i<chks.length;i++)
            chks[i].checked=false;
        document.getElementById("sizeAmount").value="";
        document.getElementById("selectSize").value="-1";
    }
    function searchItems(){
        pattern = document.getElementById('searchItems').value;
        newItems=[];
        for(i=0;i<items.length;i++)
            if(items[i]['nameItem'].toUpperCase().includes(pattern.toUpperCase()) || items[i]['gender'].toUpperCase().includes(pattern.toUpperCase()))
                newItems.push(items[i]);
        
        clearInputsItems()
        writeItems(newItems);
    }
    function writeItems(newItems){
        str="";
        for(i=0;i<newItems.length;i++)
            str+='<option value='+newItems[i]['idItem']+'>Naziv: '+newItems[i]['nameItem']+'; Cena: '+newItems[i]['price']+'; Pol: '+newItems[i]['gender']+'</option>'
        document.getElementById("selectItems").innerHTML=str;
        document.getElementById("selectItems").size=newItems.length+1
    }
    function addItem(){
        item=document.getElementById("itemName").value;
        price=document.getElementById("itemPrice").value;
        discount=document.getElementById("itemDiscount").value;
        brand=document.getElementById("selectBrand").value;
        gender=document.getElementById("selectGender").value;
        active=document.getElementById("selectActive").value;
        if(item.length!=0 && price>0 && discount>=0 && brand!="0" && gender!="0" && active!="-1"){
            amount=0
            for(i=0;i<items.length;i++)
                if(items[i]['nameItem'].toUpperCase().trim()==item.toUpperCase().trim())
                    amount++;
            if(amount==0){
                inputImages=document.getElementById('itemImg');
                if(inputImages.files.length==0)
                    alert("Niste dodali nijednu sliku!");
                else {
                    if(validateFileInput(inputImages.files)){
                        images=[];
                        for(i=0;i<inputImages.files.length;i++)
                            images.push(inputImages.files[i].name);
                        $.post("tasks.php?task=addItem", {item: item, price: price, discount: discount, brand: brand, gender: gender, images:images, active:active},function(e){
                            alert(e.split("~")[0]);
                            if(e!='There has been a mistake!'){
                                clearInputsItems();
                                items.push(JSON.parse(e.split("~")[1]));
                                writeItems(items);
                            }
                        })
                    }
                    else
                        alert('Nisu validni svi formati slika!');
                }
            }
            else 
                alert("Već postoji artikal sa ovim nazivom!")
        }
        else 
            alert("Niste napisali sve validne informacije!");
    }
    function changeSelectedItem(){
        idItem=document.getElementById("selectItems").value;
        nameItem=document.getElementById("itemName").value;
        price=document.getElementById("itemPrice").value;
        discount=document.getElementById("itemDiscount").value;
        idBrand=document.getElementById("selectBrand").value;
        gender=document.getElementById("selectGender").value;
        active=document.getElementById("selectActive").value;
        if(idItem!=""){
            if(nameItem.length!=0 && price>0 && discount>=0 && idBrand!="0" && gender!="0" && active!="-1"){
                amount=0;index=-1;
                for(i=0;i<items.length;i++){
                    if(items[i]['nameItem'].toUpperCase().trim()==nameItem.toUpperCase().trim() && items[i]['idItem']!=idItem)
                        amount++;
                    if(items[i]['idItem']==idItem)
                        index=i;
                }
                if(amount==0){
                    if(items[index]['nameItem']==nameItem && items[index]['price']==price && items[index]['discount']==discount && items[index]['gender']==gender && items[index]['idBrand']==idBrand && items[index]['active']==active && document.getElementById("itemImg").files.length==0)
                        alert("Niste promenili selektovani artikal!");
                    else{
                        data={idItem: idItem, nameItem: nameItem, price: price, discount: discount, gender: gender, idBrand: idBrand, active: active};
                        if(validateFileInput(document.getElementById("itemImg").files)){
                            if(document.getElementById("itemImg").files.length!=0){
                                img=[]
                                for(i=0;i<document.getElementById("itemImg").files.length;i++)
                                    img.push(document.getElementById("itemImg").files[i].name);
                                
                                data['img']=img;
                            }
                            $.post("tasks.php?task=changeItem", data, function(e){
                                alert(e.split("~")[0]);
                                if(e.split("~")[0]=='Uspešno promenjeno!'){
                                    clearInputsItems()
                                    for(i=0;i<items.length;i++)
                                        if(items[i]['idItem']==idItem){
                                            items[i]['nameItem']=nameItem;
                                            items[i]['price']=price;
                                            items[i]['discount']=discount;
                                            items[i]['idBrand']=idBrand;
                                            items[i]['gender']=gender;
                                            items[i]['active']=active;
                                            if(e.split("~")[1]!=undefined){
                                                imgs=JSON.parse(e.split("~")[1]);
                                                for(j=0;j<imgs.length;j++){
                                                    chk=0;
                                                    for(k=0;k<items[i]['images'].length;k++)
                                                        if(items[i]['images'][k]['idPicture']==imgs[j]['idPicture'])
                                                            chk=1;
                                                    if(chk==0)
                                                        items[i]['images'].push(imgs[j]);
                                                }
                                            }
                                            writeItems(items);
                                        }
                                }
                            })
                        }
                        else 
                            alert("Nisu validni svi formati slika!");
                    }
                }
                else 
                    alert("Već postoji artikal sa ovim nazivom!");
            }
            else 
                alert("Niste uneli sve validne informacije!");
        }
        else 
            alert("Niste izabrali artikal!");
    }
    function addSize(){
        idItem=document.getElementById("selectItems").value;
        idSize=document.getElementById("selectSize").value;
        amount=document.getElementById("sizeAmount").value;
        if(idItem!=""){
            if(idSize!="-1" && amount.length>0 && amount>0){
                $.post("tasks.php?task=addSize",{idItem:idItem,idSize:idSize,amount:amount},function(e){
                    alert(e);
                    if(e=='Uspešno dodato!'){
                        document.getElementById("selectSize").value="-1";
                        document.getElementById("sizeAmount").value="";
                    }
                })
            }
            else    
                alert("Niste uneli sve validne informacije!");
        }
        else
            alert("Niste izabrali artikal!");
    }
    function changeSubcategoriesOfItem(){
        idItem=document.getElementById("selectItems").value;
        if(idItem!=""){
            subs=[];
            chks=$("input[id^='subcategory']");
            for(i=0;i<chks.length;i++)
                if(chks[i].checked)
                    subs.push(chks[i].value);
            item=[];
            for(i=0;i<items.length;i++)
                if(items[i]['idItem']==idItem)
                    item=items[i]['subcategories'];
            if(JSON.stringify(item.sort()) !== JSON.stringify(subs.sort())){
                $.post("tasks.php?task=changeSubcategoriesOfItem",{idItem:idItem,subcategories:subs},function(e){
                    alert(e);
                    if(e=='Uspešno promenjeno!'){
                        subsBefore=[]
                        for(i=0;i<items.length;i++)
                            if(items[i]['idItem']==idItem){
                                subsBefore=items[i]['subcategories'];
                                items[i]['subcategories']=subs;
                            }
                        //update kolicine u potkategorijama
                        for(i=0;i<subsBefore.length;i++)
                            if(!subs.includes(subsBefore[i])){
                                for(j=0;j<subcategories.length;j++)
                                    if(subcategories[j]['idSubcategory']==subsBefore[i])
                                        subcategories[j]['amount']= parseInt(subcategories[j]['amount'])-1;
                            }
                        for(i=0;i<subs.length;i++)
                            if(!subsBefore.includes(subs[i])){
                                for(j=0;j<subcategories.length;j++)
                                    if(subcategories[j]['idSubcategory']==subs[i])
                                        subcategories[j]['amount']=parseInt(subcategories[j]['amount'])+1;
                            }
                        writeSubcategories(subcategories,true);
                        writeItems(items);
                        document.getElementById('searchItems').value="";
                    }
                })
            }
            else 
                alert("Niste napravili nikakvu promenu!");
        }
        else 
            alert("Niste izabrali artikal!");
        
    }
    function deleteSelectedImages(){
        chks=$("input[id^='checkboxImg']");
        idItem=document.getElementById("selectItems").value;
        if(idItem!=""){
            ids=[];
            for(i=0;i<chks.length;i++)
                if(chks[i].checked)
                    ids.push(chks[i].value);
            if(ids.length>0){
                $.post("tasks.php?task=deleteImages",{idItem: idItem, ids:ids},function(e){
                    alert(e);
                    if(e=='Uspešno obrisano!'){
                        indexs=[];
                        for(i=0;i<items.length;i++)
                            if(items[i]['idItem']==idItem){
                                for(j=0;j<items[i]['images'].length;j++)
                                    for(k=0;k<ids.length;k++)
                                        if(items[i]['images'][j]['idPicture']==ids[k])
                                            indexs.push(j)
                                for(j=0;j<indexs.length;j++)
                                    items[i]['images'].splice(indexs[j]-j,1);
                            }
                        writeImagesOfSelectedItem(idItem);
                    }
                })
            }
            else 
                alert("Niste izabrali za brisanje nijednu od ponudjenih slika!");
        }
        else 
            alert("Niste izabrali artikal!");
    }

    //bills
    var bills= <?php echo json_encode($bills);?>;
    function changeSelectBills(obj){
        idBill=document.getElementById("selectBills").value;
        $.post("tasks.php?task=getBillItems",{idBill: idBill},function(e){
            if(e!='There has been a mistake!'){
                billItems=JSON.parse(e);
                console.log(billItems);
                str="";
                for(i=0;i<billItems.length;i++){
                    str+=`<div class="row"><img src="../pictures/`+billItems[i]['urlPicture']+`" alt="" class="col-4">
                    <div class="col-8 align-self-center"><span class="font-weight-bold">`+billItems[i]['nameItem']+`</span><br> Price: `+billItems[i]['price']+`rsd`;
                    price=billItems[i]['price']-billItems[i]['price']/100*billItems[i]['discount'];
                    if(billItems[i]['discount']>0)
                        str+=`<span class='badge badge-danger m-2 p-2'><h6 class='m-0'> -`+billItems[i]['discount']+`%</h6></span>  = `+price+`rsd&nbsp;`;
                    
                    str+=`<b> x`+billItems[i]['amount']+`=&nbsp;`+(billItems[i]['amount']*price)+`rsd </b></div> </div>`;
                }
                document.getElementById('billItems').innerHTML=str;
            }
            else 
                alert(e);
        })
    }
    function searchBills(){
        dateFrom=document.getElementById("fromDate").value;
        dateTo=document.getElementById("toDate").value;
        timeFrom=document.getElementById("fromTime").value;
        timeTo=document.getElementById("toTime").value;
        
        if(dateFrom!="" && dateTo!="" && timeFrom!="" && timeTo!=""){
            newBills=[];
            for(i=0;i<bills.length;i++)
                if(bills[i]['billDate']>=dateFrom && bills[i]['billDate']<=dateTo && bills[i]['billTime']>=timeFrom && bills[i]['billTime']<=timeTo)
                    newBills.push(bills[i]);
                    
            writeBills(newBills);
        }
        else    
            alert("Niste uneli sve podatke za pretragu!");
    }
    function writeBills(newBills){
        str="";
        for(i=0;i<newBills.length;i++)
            str+='<option value="'+newBills[i]['idBill']+'">Datum: '+newBills[i]['billDate']+'; Vreme: '+newBills[i]['billTime']+'h; Cena: '+newBills[i]['totalPrice']+'rsd</option>';
        document.getElementById("selectBills").innerHTML=str;
        document.getElementById("selectBills").size=newBills.length+1
        document.getElementById("searchBills").size=newBills.length+1
        document.getElementById('billItems').innerHTML="";
    }

    //brands 
    var brands= <?php echo json_encode($brands);?>;
    function changeSelectBrands(obj){
        for(i=0;i<brands.length;i++)
            if(brands[i]['idBrand']==obj.value){
                document.getElementById("brandName").value=brands[i]['nameBrand'];
                document.getElementById("brandImg").files.name=brands[i]['pictureBrand'];
            }
    }
    function searchBrands(){
        pattern = document.getElementById('searchBrands').value;
        newBrands=[];
        for(i=0;i<brands.length;i++)
            if(brands[i]['nameBrand'].toUpperCase().includes(pattern.toUpperCase()) || brands[i]['pictureBrand'].toUpperCase().includes(pattern.toUpperCase()))
                newBrands.push(brands[i]);

        clearInputsBrands();
        writeBrands(newBrands, false);
    }
    function writeBrands(newBrands, items){
        str="";
        for(i=0;i<newBrands.length;i++)
            str+='<option value="'+newBrands[i]['idBrand']+'">'+newBrands[i]['nameBrand']+' - '+newBrands[i]['pictureBrand']+'  ('+newBrands[i]['amount']+')</option>';
        document.getElementById("selectBrands").innerHTML=str;
        if(items)
            document.getElementById("selectBrand").innerHTML='<option value="0">--izaberi brend--</option>'+str;
        document.getElementById("selectBrands").size=newBrands.length+1
    }
    function changeSelectedBrand(){
        idBrand=document.getElementById("selectBrands").value;
        brand=document.getElementById("brandName").value;
        if(idBrand!=""){
            if(brand.length!=0){
                index=-1
                for(i=0;i<brands.length;i++)
                    if(brands[i]['idBrand']==idBrand)
                        index=i;
                if(brands[index]['nameBrand'].toUpperCase()==brand.toUpperCase() && document.getElementById('brandImg').files.length==0)
                    alert("Niste promenili selektovani brend!");
                else{
                    if(document.getElementById('brandImg').files.length>0)
                        img=document.getElementById('brandImg').files[0].name;
                    else
                        img=" ";
                    if(validateFileInput(document.getElementById('brandImg').files)){
                        $.post("tasks.php?task=changeBrand",{idBrand: idBrand, brand: brand, img: img},function(e){
                            alert(e);
                            if(e=='Uspešno promenjeno!'){
                                document.getElementById('brandName').value="";
                                document.getElementById('brandImg').value='';
                                for(i=0;i<brands.length;i++)
                                    if(brands[i]['idBrand']==idBrand){
                                        brands[i]['nameBrand']=brand;
                                        if(img!=" ")
                                            brands[i]['pictureBrand']=img;
                                        writeBrands(brands, true);
                                    }
                            }
                        })
                    }
                    else 
                        alert("Nije validan format slike!");
                }
            }
            else 
                alert("Niste napisali naziv brenda!");
        }
        else 
            alert("Niste izabrali brend!");
    }
    function deleteSelectedBrand(){
        idBrand=document.getElementById("selectBrands").value;
        if(idBrand!=""){
            amount='';
            for(i=0;i<brands.length;i++)
                if(brands[i]['idBrand']==idBrand)
                    amount=brands[i]['amount'];
            if(amount==0){
                $.post("tasks.php?task=deleteBrand",{idBrand: idBrand},function(e){
                    alert(e);
                    if(e=='Uspešno obrisano!'){
                        document.getElementById('brandName').value="";
                        document.getElementById('brandImg').value='';
                        for(i=0;i<brands.length;i++)
                            if(brands[i]['idBrand']==idBrand){
                                brands.splice(i,1);
                                writeBrands(brands, true);
                            }
                    }
                })
            }
            else 
                alert("Postoje artikli sa ovim brendom, ne možete obrisati ovaj brend!")
        }
        else 
            alert("Niste izabrali brend!");
    }
    function addBrand(){
        brand=document.getElementById("brandName").value;
        if(brand.length!=0){
            amount=0
            for(i=0;i<brands.length;i++)
                if(brands[i]['nameBrand'].toUpperCase()==brand.toUpperCase())
                    amount++;
            if(amount==0){
                if(document.getElementById('brandImg').files.length!=0){
                    if(validateFileInput(document.getElementById('brandImg').files)){
                        $.post("tasks.php?task=addBrand",{brand: brand, img: document.getElementById('brandImg').files[0].name},function(e){
                            alert(e.split("~")[0]);
                            if(e!='There has been a mistake!'){
                                document.getElementById('brandName').value="";
                                document.getElementById('brandImg').value='';
                                brands.push(JSON.parse(e.split("~")[1]));
                                writeBrands(brands, true);
                            }
                        })
                    }
                    else 
                        alert("Nisu validni svi formati slika!");
                }
                else
                    alert("Niste izabrali sliku!");
            }
            else 
                alert("Postoji ovakav brend!")
        }
        else 
            alert("Niste napisali naziv brenda!");
    }
    function clearInputsBrands(){
        document.getElementById('brandName').value="";
        document.getElementById('brandImg').value='';
        document.getElementById('searchBrands').value='';
    }

    //categories
    var categories=<?php echo json_encode($categories);?>;
    function changeSelectCategories(obj){
        for(i=0;i<categories.length;i++)
            if(categories[i]['idCategory']==obj.value)    
                document.getElementById("categoryName").value=categories[i]['nameCategory'];
    }
    function searchCategories(){
        pattern = document.getElementById('searchCategories').value;
        newCategories=[];
        for(i=0;i<categories.length;i++)
            if(categories[i]['nameCategory'].toUpperCase().includes(pattern.toUpperCase()))
                newCategories.push(categories[i]);

        clearInputsCategories();
        writeCategories(newCategories, false);
    }
    function writeCategories(newCategories, subcategory){
        str="";
        for(i=0;i<newCategories.length;i++)
            str+='<option value="'+newCategories[i]['idCategory']+'">'+newCategories[i]['nameCategory']+' ('+newCategories[i]['amount']+')</option>';
        
            document.getElementById("selectCategories").innerHTML=str;
        if(subcategory)
            document.getElementById("selectCategory").innerHTML='<option value="0">--izaberi kategoriju--</option>'+str;
        document.getElementById("selectCategories").size=newCategories.length+1
    }
    function changeSelectedCategory(){
        idCategory=document.getElementById("selectCategories").value;
        nameCategory=document.getElementById("categoryName").value;
        if(idCategory!=""){
            if(nameCategory.length!=0){
                index=-1;
                for(i=0;i<categories.length;i++)
                    if(categories[i]['idCategory']==idCategory)
                        index=i;
                if(nameCategory.toUpperCase()!=categories[index]['nameCategory'].toUpperCase()){
                    $.post("tasks.php?task=changeCategory",{idCategory: idCategory, nameCategory: nameCategory},function(e){
                        alert(e);
                        if(e=='Uspešno promenjeno!'){
                            clearInputsCategories();
                            for(i=0;i<categories.length;i++)
                                if(categories[i]['idCategory']==idCategory){
                                    categories[i]['nameCategory']=nameCategory.toUpperCase();
                                    writeCategories(categories, true);
                                }
                        }
                    })
                }
                else    
                    alert("Niste promenili selektovanu kategoriju!");
            }
            else 
                alert("Niste napisali naziv kategorije!");
        }
        else 
            alert("Niste izabrali kategoriju!");
    }
    function deleteSelectedCategory(){
        category=document.getElementById("selectCategories").value;
        if(category!=""){
            amount='';
            for(i=0;i<categories.length;i++)
                if(categories[i]['idCategory']==category)
                    amount=categories[i]['amount'];
            if(amount==0){
                $.post("tasks.php?task=deleteCategory",{idCategory: category},function(e){
                    alert(e);
                    if(e=='Uspešno obrisano!'){
                        clearInputsCategories();
                        for(i=0;i<categories.length;i++)
                            if(categories[i]['idCategory']==category){
                                categories.splice(i,1);
                                writeCategories(categories, true);
                            }
                    }
                })
            }
            else 
                alert("Postoje artikli u ovoj kategoriji, ne možete je obrisati!")
        }
        else 
            alert("Niste izabrale kategoriju!");
    }
    function addCategory(){
        category=document.getElementById("categoryName").value;
        if(category.length!=0){
            amount=0
            for(i=0;i<categories.length;i++)
                if(categories[i]['nameCategory'].toUpperCase()==category.toUpperCase())
                    amount++;
            if(amount==0){
                $.post("tasks.php?task=addCategory",{category: category},function(e){
                    alert(e.split("~")[0]);
                    if(e!='There has been a mistake!'){
                        clearInputsCategories();
                        categories.push(JSON.parse(e.split("~")[1]));
                        writeCategories(categories, true);
                    }
                })
            }
            else 
                alert("Postoji ovakva kategorija!")
        }
        else 
            alert("Niste napisali naziv kategorije!");
    }
    function clearInputsCategories(){   
        document.getElementById('categoryName').value="";
        document.getElementById('searchCategories').value="";
    }

    //subcategories 
    var subcategories= <?php echo json_encode($subcategories);?>;
    function changeSelectSubcategories(obj){
        for(i=0;i<subcategories.length;i++)
            if(subcategories[i]['idSubcategory']==obj.value){
                document.getElementById("subcategoryName").value=subcategories[i]['nameSubcategory'];
                document.getElementById("selectCategory").value=subcategories[i]['idCategory'];
            }
    }
    function searchSubcategories(){
        pattern = document.getElementById('searchSubcategories').value;
        newSubcategories=[];
        for(i=0;i<subcategories.length;i++)
            if(subcategories[i]['nameSubcategory'].toUpperCase().includes(pattern.toUpperCase()))
                newSubcategories.push(subcategories[i]);
        
        clearInputsSubcategories();
        writeSubcategories(newSubcategories, false);
    }
    function writeSubcategories(newSubcategories, items){
        str="";str1="";
        for(i=0;i<newSubcategories.length;i++){
            str+='<option value="'+newSubcategories[i]['idSubcategory']+'">'+newSubcategories[i]['nameSubcategory']+' ('+newSubcategories[i]['amount']+')</option>';
            str1+='<input type="checkbox" id="subcategory'+newSubcategories[i]['idSubcategory']+'" value="'+newSubcategories[i]['idSubcategory']+'" class="m-2 p-2"> <span class="m-2">'+newSubcategories[i]['nameSubcategory']+'</span><br>';
        }
        document.getElementById("selectSubcategories").innerHTML=str;
        if(items)
            document.getElementById("changeSubcategories").innerHTML=str1;
        document.getElementById("selectSubcategories").size=newSubcategories.length+1
    }
    function changeSelectedSubcategory(){
        idSubcategory=document.getElementById("selectSubcategories").value;
        nameSubcategory=document.getElementById("subcategoryName").value;
        idCategory=document.getElementById("selectCategory").value;
        if(idSubcategory!=""){
            if(nameSubcategory.length!=0){
                if(idCategory!="0"){
                    index=-1
                    for(i=0;i<subcategories.length;i++)
                        if(subcategories[i]['idSubcategory']==idSubcategory)
                            index=i;
                    
                    if(subcategories[index]['nameSubcategory'].toUpperCase()==nameSubcategory.toUpperCase() && subcategories[index]['idCategory']==idCategory)
                        alert("Niste promenili selektovanu potkategoriju!");
                    else{
                        $.post("tasks.php?task=changeSubcategory",{idSubcategory: idSubcategory, nameSubcategory: nameSubcategory, idCategory: idCategory},function(e){
                            alert(e);
                            if(e=='Uspešno promenjeno!'){
                                //menjanje kolicine u kategoriji
                                if(subcategories[index]['idCategory']!=idCategory){
                                    for(i=0;i<categories.length;i++){
                                        if(categories[i]['idCategory']==idCategory)
                                            categories[i]['amount']=parseInt(categories[i]['amount'])+1;
                                        if(categories[i]['idCategory']==subcategories[index]['idCategory'])
                                            categories[i]['amount']=parseInt(categories[i]['amount'])-1;
                                    }
                                }
                                writeCategories(categories,true);
                                document.getElementById('subcategoryName').value="";
                                document.getElementById('selectCategory').value="0";
                                for(i=0;i<subcategories.length;i++)
                                    if(subcategories[i]['idSubcategory']==idSubcategory){
                                        subcategories[i]['nameSubcategory']=nameSubcategory.toUpperCase();
                                        subcategories[i]['idCategory']=idCategory;
                                        writeSubcategories(subcategories, true);
                                    }
                            }
                        })
                    }
                }
                else 
                    alert("Niste izabrali kategoriju!");
            }
            else 
                alert("Niste napisali naziv potkategorije!");
        }
        else 
            alert("Niste izabrali potkategoriju!");
    }
    function deleteSelectedSubcategory(){
        subcategory=document.getElementById("selectSubcategories").value;
        if(subcategory!=""){
            amount='';
            for(i=0;i<subcategories.length;i++)
                if(subcategories[i]['idSubcategory']==subcategory)
                    amount=subcategories[i]['amount'];
            if(amount==0){
                $.post("tasks.php?task=deleteSubcategory",{idSubcategory: subcategory},function(e){
                    alert(e);
                    if(e=='Uspešno obrisano!'){
                        //menjanje kolicine u kategoriji
                        for(i=0;i<categories.length;i++)
                                if(categories[i]['idCategory']==document.getElementById('selectCategory').value)
                                    categories[i]['amount']= parseInt(categories[i]['amount'])-1;
                        
                        writeCategories(categories, true);
                        clearInputsSubcategories();
                        for(i=0;i<subcategories.length;i++)
                            if(subcategories[i]['idSubcategory']==subcategory){
                                subcategories.splice(i,1);
                                writeSubcategories(subcategories, true);
                            }
                    }
                })
            }
            else 
                alert("Postoje artikli u ovoj potkategoriji, ne možete je obrisati!")
        }
        else 
            alert("Niste izabrali potkategoriju!");
    }
    function addSubcategory(){
        subcategory=document.getElementById("subcategoryName").value;
        if(subcategory.length!=0){
            amount=0
            for(i=0;i<subcategories.length;i++)
                if(subcategories[i]['nameSubcategory'].toUpperCase()==subcategory.toUpperCase())
                    amount++;
            if(amount==0){
                if(document.getElementById('selectCategory').value!="0"){
                    $.post("tasks.php?task=addSubcategory",{subcategory: subcategory, category: document.getElementById('selectCategory').value},function(e){
                        alert(e.split("~")[0]);
                        if(e!='There has been a mistake!'){
                            subcategories.push(JSON.parse(e.split("~")[1]));
                            //menjanje kolicine u kategoriji
                            for(i=0;i<categories.length;i++)
                                if(categories[i]['idCategory']==document.getElementById('selectCategory').value)
                                    categories[i]['amount']= parseInt(categories[i]['amount'])+1;
                            writeSubcategories(subcategories, true);
                            writeCategories(categories, true);
                            clearInputsSubcategories();
                        }
                    })
                }
                else
                    alert("Niste izabrali kategoriju!");
            }
            else 
                alert("Postoji ovakva potkategorija!")
        }
        else 
            alert("Niste napisali naziv potkategorije!");
    }
    function clearInputsSubcategories(){   
        document.getElementById('subcategoryName').value="";
        document.getElementById('selectCategory').value="0";
        document.getElementById('searchSubcategories').value="";
    }
    </script>
</body>
</html>