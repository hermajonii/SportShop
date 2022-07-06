<?php
    require_once('functions.php');

    $patternName='/^[A-ZČĆŠĐŽ]{1}[a-zčćžđš]{1,14}+(\ [A-ZČĆŠĐŽ]{1}[a-zčćžđš]{1,14})*$/';
    $patternUsernamePassword='/^[A-Za-z0-9ČĆŠĐŽčćžđš]{8,}$/';
    $patternAddress='#^[A-Za-z0-9\/\, ČĆŠĐŽčćžđš]{1,70}$#';
    $patternPhone='#^06[0-9]{1}\/[0-9]{3}-[0-9]{3,4}$#';
    $patternCategory='#^[A-Za-z ČĆŠĐŽčćžđš]{2,30}$#';
    $patternItemName='#^[A-Za-z 0-9-ČĆŠĐŽčćžđš\.]{2,50}$#';
    $patternComment='#^[A-Za-z\,\. 0-9--?!\.ČĆŠĐŽčćžđš]{2,100}$#';
    $cookie="";
    //account
    if($_GET['task']=='logIn'){
        $username=$_POST['username'];
        $password=$_POST['password'];
        if(preg_match($patternUsernamePassword,$username) && preg_match($patternUsernamePassword,$password)){
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'call sp_logIn("'.$username.'","'.$password.'")');
                if(!mysqli_error($con)){
                    if(mysqli_num_rows($res)){
                        $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
                        $user=new User($row["idUser"],$row["firstName"],$row["lastName"],$row['address'],$row["phoneNumber"],$row["role"],$row["username"], $row['password'], $row['email']);
                        $_SESSION["user"]=serialize($user);
                        echo "Success!";
                    }
                    else{
                        echo "Ne postoji korisnik sa ovim podacima!";
                    }
                }
                else
                    echo "There has been a mistake!".mysqli_error($con);
                closeConnection($con);
            }
        }
        else 
            echo "Niste uneli validne podatke! Korisničko ime i lozinka moraju imati bar 8 karaktera i mogu sadržati velika slova, mala slova i brojeve!";
    }
    elseif($_GET['task']=='logOut'){
        session_destroy();
        header("Location:index.php");
    }
    elseif($_GET['task']=='signIn'){
        $firstName=trim($_POST['firstName']);
        $lastName=trim($_POST['lastName']);
        $address=trim($_POST['address']);
        $email=trim($_POST['email']);
        $phone=trim($_POST['phone']);
        $usernameSign=$_POST['usernameSign'];
        $passwordSign=$_POST['passwordSign'];
        $passwordSignCheck=$_POST['passwordSignCheck'];

        if($passwordSign==$passwordSignCheck){
            if(preg_match($patternName,$firstName) && preg_match($patternName,$lastName)
            && preg_match($patternAddress,$address) && filter_var($email, FILTER_VALIDATE_EMAIL)
            && preg_match($patternPhone,$phone) && preg_match($patternUsernamePassword,$usernameSign) && preg_match($patternUsernamePassword,$passwordSign)){
                $con=getConnection();
                if($con){
                    $res=mysqli_query($con,'call sp_addNewUser("'.$firstName.'","'.$lastName.'","'.$address.'","'.$phone.'","'.$usernameSign.'","'.$passwordSign.'","'.$email.'")');
                    if(!mysqli_error($con)){
                        if(mysqli_num_rows($res)){
                            $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
                            //ako je uspesno napravljen profil, uloguje se 
                            if($row['message']=='Success'){
                                $con1=getConnection();
                                if($con1){
                                    $result=mysqli_query($con1,'call sp_logIn("'.$usernameSign.'","'.$passwordSign.'")');
                                    if(!mysqli_error($con1)){
                                        if(mysqli_num_rows($result)){
                                            $row1=mysqli_fetch_array($result,MYSQLI_ASSOC);
                                            $user=new User($row1["idUser"],$row1["firstName"],$row1["lastName"],$row1['address'],$row1["phoneNumber"],$row1["role"],$row1["username"], $row1['password'], $row1['email']);
                                            $_SESSION["user"]=serialize($user);
                                            echo "Success!";
                                        }
                                    }
                                    else
                                        echo "There has been a mistake!";
                                    closeConnection($con1);
                                }
                            }
                            else
                                echo "Korisničko ime je zauzeto!";
                        }
                    }
                    else    
                        echo "There has been a mistake!";
                    closeConnection($con);
                }
            }
            else
                echo "Unesite validne podatke!";
        }
        else
            echo "Lozinke se ne poklapaju!";
    }
    elseif($_GET['task']=='changeAddress'){
        $address=trim($_POST['address']);
        if(preg_match($patternAddress,$address)){
            $con=getConnection();
            if($con){
                $user=unserialize($_SESSION['user']);
                $res=mysqli_query($con,'call sp_changeAddress('.$user->idUser.',"'.$address.'")');
                if(!mysqli_error($con)){
                    $user->address=$address;
                    $_SESSION['user']=serialize($user);
                    echo "Uspešno promenjeno!";
                }
                else
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else 
            echo 'Unesite validnu adresu!';
    }
    elseif($_GET['task']=='changePassword'){
        $oldPasswordChange=$_POST['oldPasswordChange'];
        $passwordChange=$_POST['passwordChange'];
        $passwordChangeCheck=$_POST['passwordChangeCheck'];
        $user=unserialize($_SESSION['user']);
        if(strcmp($user->password,$oldPasswordChange)==0){
            if(strcmp($passwordChange,$passwordChangeCheck)==0){
                if(preg_match($patternUsernamePassword,$passwordChange)){
                    $con=getConnection();
                    if($con){
                        $res=mysqli_query($con,'UPDATE users SET password="'.$passwordChange.'" WHERE idUser='.$user->idUser.';');
                        if(!mysqli_error($con)){
                            $user->password=$passwordChange;
                            $_SESSION['user']=serialize($user);
                            echo "Uspešno promenjeno!";
                        }
                        else
                            echo "There has been a mistake!";
                        closeConnection($con);
                    }
                }
                else 
                    echo 'Unesite validnu lozinku! Lozinka može sadržati velika, mala slova i brojeve i mora imati makar 8 karaktera!';
            }
            else 
                echo "Niste uneli istu potvrdnu lozinku!";
        }
        else 
            echo "Niste uneli tačnu trenutnu lozinku!";
    }
    //aboutUs
    elseif($_GET['task']=='sendComment'){
        $name=trim($_POST['name']);
        $email=trim($_POST['email']);
        $comment=trim($_POST['comment']);
        $arr=['name'=>$name, 'email'=>$email, 'comment'=>$comment];
        if(preg_match($patternName,$name) && preg_match($patternComment,$comment) 
            && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $fileName="../suggestionsAndComplaints.txt";
            //broji koliko je redova u datoteci
            $count=0;
            if(file_exists($fileName)){
                $fp=fopen($fileName,'r');
                do{
                    $row=fgets($fp);
                    if($row!="")
                        $count++;
                }while(!feof($fp));
            }
            //upis u datoteku
            $fp=fopen($fileName,"a");
            if($count>0)
                fwrite($fp,",\r\n".json_encode($arr));
            else 
                fwrite($fp,json_encode($arr));

            fclose($fp);
            echo "Uspešno poslato!";
            
        }
        else 
            echo "Niste uneli validne podatke!";
    }
    //cart
    elseif($_GET['task']=='delectItemFromCart'){
        $idItem=$_POST['idItem'];
        $size=$_POST['idSize'];
        $user=unserialize($_SESSION['user']);
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'call sp_deleteItemFromCart('.$user->idUser.','.$idItem.','.$size.')');
            if(!mysqli_error($con))
                echo "Artikal je uspešno uklonjen iz korpe!";
            else
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='changeAmountOfItem'){
        $idItem=$_POST['idItem'];
        $amount=$_POST['amount'];
        $size=$_POST['size'];
        $user=unserialize($_SESSION['user']);
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'call sp_updateCartAmount('.$user->idUser.','.$idItem.','.$amount.','.$size.')');      
            if(!mysqli_error($con))
                    echo "Success!";
            else
                echo "There has been a mistake!";     
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='purchase'){
        if(isset($_SESSION['user'])){
            $user=unserialize($_SESSION['user']);
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'call sp_purchase('.$user->idUser.')');
                if(!mysqli_error($con))
                    echo "Kupovina je uspešno obavljena!";
                else
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else
            echo "Morate biti ulogovani da biste izvršili kupovinu!";
    }
    elseif($_GET['task']=='addItemInCart'){
        $idItem=$_POST['idItem'];
        $amount=$_POST['amount'];
        $size=$_POST['size'];
        if(isset($_SESSION['user'])){
            $user=unserialize($_SESSION['user']);
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'call sp_addItemInCart('.$amount.','.$idItem.','.$user->idUser.','.$size.')');
                if(!mysqli_error($con))
                    echo "Artikal je uspešno dodat u Vašu korpu!";
                else
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else
            echo "Morate biti ulogovani da biste dodali artikal u korpu!";
    }
    //nav i cookie
    elseif($_GET['task']=='search'){
        $search=trim($_POST['search']);
        header('Location: items.php?q='.$search.'');
    }
    elseif($_GET['task']=='setCookie'){
        $cookie=setcookie("Allowed","Yes", time()+3600*24*7);
        header("location:". $_SERVER['HTTP_REFERER']);
    }
    //admin     
    elseif($_GET['task']=='deleteCategory'){
        $idCategory=$_POST['idCategory'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'DELETE FROM categories WHERE idCategory='.$idCategory.' ');
            if(!mysqli_error($con))
                echo "Uspešno obrisano!";
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='addCategory'){
        $category=trim(strtoupper($_POST['category']));
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'INSERT INTO categories VALUES (NULL,"'.$category.'")');
            if(!mysqli_error($con)){
                echo "Uspešno dodato!~";
                $con1=getConnection();
                //ako je dodata, treba da se vrati nova kategorija
                if($con1){
                    $res=mysqli_query($con1,'SELECT * FROM view_showcategories WHERE nameCategory="'.$category.'"');
                    if(!mysqli_error($con1))
                        echo json_encode(mysqli_fetch_assoc($res));
                    else 
                        echo "There has been a mistake!";
                closeConnection($con1);
                }
            }
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='changeCategory'){
        $idCategory=$_POST['idCategory'];
        $nameCategory=trim(strtoupper($_POST['nameCategory']));
        if(preg_match($patternCategory,$nameCategory)){
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'UPDATE categories SET nameCategory="'.$nameCategory.'" WHERE idCategory='.$idCategory.' ');
                if(!mysqli_error($con))
                    echo "Uspešno promenjeno!";
                else 
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else 
            echo "Unesite validnu kategoriju!";
    }
    elseif($_GET['task']=='addSubcategory'){
        $subcategory=trim(strtoupper($_POST['subcategory']));
        $category=$_POST['category'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'INSERT INTO subcategories VALUES (NULL,"'.$subcategory.'",'.$category.')');
            if(!mysqli_error($con)){
                echo "Uspešno dodato!~";
                //ako je uspesno dodata treba da se vrati nova
                $con1=getConnection();
                if($con1){
                    $res=mysqli_query($con1,'SELECT * FROM view_showsubcategories WHERE nameSubcategory="'.$subcategory.'"');
                    if(!mysqli_error($con1))
                        echo json_encode(mysqli_fetch_assoc($res));
                    else 
                        echo "There has been a mistake!";
                    closeConnection($con1);
                }
            }
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='deleteSubcategory'){
        $idSubcategory=$_POST['idSubcategory'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'DELETE FROM subcategories WHERE idSubcategory='.$idSubcategory.' ');
            if(!mysqli_error($con))
                echo "Uspešno obrisano!";
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='changeSubcategory'){
        $idSubcategory=$_POST['idSubcategory'];
        $idCategory=$_POST['idCategory'];
        $nameSubcategory=trim(strtoupper($_POST['nameSubcategory']));
        if(preg_match($patternCategory,$nameSubcategory)){
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'UPDATE subcategories SET nameSubcategory="'.$nameSubcategory.'", idCategory='.$idCategory.' WHERE idSubcategory='.$idSubcategory.' ');
                if(!mysqli_error($con))
                    echo "Uspešno promenjeno!";
                else 
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else 
            echo "Unesite validnu potkategoriju!";
    }
    elseif($_GET['task']=='deleteBrand'){
        $idBrand=$_POST['idBrand'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'DELETE FROM brands WHERE idBrand='.$idBrand.' ');
            if(!mysqli_error($con))
                echo "Uspešno obrisano!";
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='addBrand'){
        $brand=trim(ucfirst($_POST['brand']));
        $img=$_POST['img'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'INSERT INTO brands VALUES (NULL,"'.$brand.'","'.$img.'")');
            if(!mysqli_error($con)){
                echo "Uspešno dodato!~";
                //ako je uspešno dodat treba da se vrati nov
                $con1=getConnection();
                if($con1){
                    $res=mysqli_query($con1,'SELECT * FROM view_showbrands WHERE nameBrand="'.$brand.'"');
                    if(!mysqli_error($con1))
                        echo json_encode(mysqli_fetch_assoc($res));
                    else 
                        echo "There has been a mistake!";
                    closeConnection($con1);
                }
            }
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='changeBrand'){
        $idBrand=$_POST['idBrand'];
        $pictureBrand=$_POST['img'];
        $nameBrand=trim(ucfirst($_POST['brand']));
        if(preg_match($patternCategory,$nameBrand)){
            $con=getConnection();
            if($con){
                if($pictureBrand!=" ")
                    $query='UPDATE brands SET nameBrand="'.$nameBrand.'", pictureBrand="'.$pictureBrand.'" WHERE idBrand='.$idBrand.' ';
                else 
                    $query='UPDATE brands SET nameBrand="'.$nameBrand.'" WHERE idBrand='.$idBrand.'; ';
                $res=mysqli_query($con,$query);
                if(!mysqli_error($con))
                    echo "Uspešno promenjeno!";
                else 
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else 
            echo "Unesite validan brend!";
    }
    elseif($_GET['task']=='getBillItems'){
        $idBill=$_POST['idBill'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,"SELECT * FROM carts WHERE idBill=".$idBill.";");
            if(!mysqli_error($con)){
                $arr=[];
                for($i=0;$i<mysqli_num_rows($res);$i++){
                    $row=mysqli_fetch_assoc($res);
                    $con1=getConnection();
                    if($con1){
                        $res1=mysqli_query($con1,"call sp_showitemBill(".$row['idItem'].",".$idBill.")");
                        if(!mysqli_error($con1)){
                            $row=array_merge($row,mysqli_fetch_assoc($res1));
                            array_push($arr,$row);
                        }
                        else 
                            echo "There has been a mistake!";
                        closeConnection($con1);
                    }
                }
                echo json_encode($arr);
            }
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='changeUser'){
        $idUser=$_POST['idUser'];
        $firstName=trim($_POST['firstName']);
        $lastName=trim($_POST['lastName']);
        $address=trim($_POST['address']);
        $email=trim($_POST['email']);
        $phoneNumber=trim($_POST['phoneNumber']);
        $role=$_POST['role'];
        $active=$_POST['active'];
        if(preg_match($patternName,$firstName) && preg_match($patternName,$lastName) && preg_match($patternAddress,$address) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match($patternPhone,$phoneNumber)){
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'UPDATE users SET firstName="'.$firstName.'", lastName="'.$lastName.'", address="'.$address.'", phoneNumber="'.$phoneNumber.'", role="'.$role.'", active="'.$active.'", email="'.$email.'" WHERE idUser='.$idUser.';');
                if(!mysqli_error($con))
                    echo "Uspešno promenjeno!";
                else 
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else
            echo "Unesite validne informacije!";
        
    }
    elseif($_GET['task']=='addUser'){
        $firstName=trim($_POST['firstName']);
        $lastName=trim($_POST['lastName']);
        $address=trim($_POST['address']);
        $email=filter_var(trim($_POST['email']),FILTER_SANITIZE_EMAIL);
        $phoneNumber=trim($_POST['phoneNumber']);
        $username=trim($_POST['username']);
        $password=trim($_POST['password']);
        $role=$_POST['role'];
        $active=$_POST['active'];
        if(preg_match($patternName,$firstName) && preg_match($patternName,$lastName) && preg_match($patternAddress,$address) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match($patternPhone,$phoneNumber) && preg_match($patternUsernamePassword,$username) && preg_match($patternUsernamePassword,$password)){
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'INSERT INTO users VALUES(NULL,"'.$firstName.'","'.$lastName.'", "'.$address.'","'.$phoneNumber.'","'.$role.'","'.$username.'","'.$password.'","'.$email.'","'.$active.'");');
                if(!mysqli_error($con)){
                    echo "Uspešno dodato!~";
                    //ako je uspesno dodat potrebno je vratiti novog
                    $con1=getConnection();
                    if($con1){
                        $res=mysqli_query($con1,'SELECT * FROM users WHERE firstName="'.$firstName.'" AND lastName="'.$lastName.'" AND  address="'.$address.'" AND phoneNumber="'.$phoneNumber.'" AND role="'.$role.'" AND username="'.$username.'" AND password="'.$password.'" AND email="'.$email.'" AND active="'.$active.'";');
                        if(!mysqli_error($con1)){
                            echo json_encode(mysqli_fetch_array($res));
                        }
                        closeConnection($con1);
                    }
                }
                else 
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else
            echo "Unesite validne informacije!";
        
    }
    elseif($_GET['task']=='addItem'){
        $nameItem=trim(strtoupper($_POST['item']));
        $price=$_POST['price'];
        $discount=$_POST['discount'];
        $brand=$_POST['brand'];
        $gender=$_POST['gender'];
        $images=$_POST['images'];
        $active=$_POST['active'];
        if(preg_match($patternItemName,$nameItem)){
            $con=getConnection();
            if($con){
                $res=mysqli_query($con,'INSERT INTO items VALUES (NULL,"'.$nameItem.'",'.$price.','.$discount.',"'.$gender.'",'.$brand.','.$active.')');
                if(!mysqli_error($con)){
                    //ako je uspesno dodat potrebno je vratiti novog
                    $con1=getConnection();
                    if($con1){
                        $items=mysqli_query($con1,'SELECT * FROM items;'); $item=[];
                        for($i=0;$i<mysqli_num_rows($items);$i++){
                            $row=mysqli_fetch_assoc($items);
                            if(strcmp($row['nameItem'],$nameItem)==0 && $row['price']==$price && $row['discount']==$discount && strcmp($row['gender'],$gender)==0 && $row['idBrand']==$brand)
                                $item=$row;
                        }
                        if(!mysqli_error($con1)){
                            if(count($images)){
                                $values="";
                                $item['images']=[];
                                $item['subcategories']=[];
                                for($i=0;$i<count($images);$i++){
                                    if($i==0)
                                        $values.= "('".$images[$i]."',".$item['idItem'].")";
                                    else
                                        $values.= ",('".$images[$i]."',".$item['idItem'].")";
                                }
                                $con2=getConnection();
                                if($con2){
                                    $res=mysqli_query($con2,'INSERT INTO pictures (urlPicture,idItem) VALUES '.$values.'');
                                    if(!mysqli_error($con2)){
                                        echo "Uspešno dodato!~";
                                        $con3=getConnection();
                                        if($con3){
                                            $res=mysqli_query($con3,'SELECT * FROM pictures WHERE idItem='.$item['idItem'].';');
                                            if(!mysqli_error($con3)){
                                                $arr=[];
                                                for($i=0;$i<mysqli_num_rows($res);$i++)
                                                    array_push($arr,mysqli_fetch_assoc($res));
                                                $item['images']=$arr;
                                                echo json_encode($item);
                                            }
                                            else 
                                                echo "There has been a mistake!";
                                        closeConnection($con3);
                                        }
                                    }
                                    else 
                                        echo "There has been a mistake!";
                                    closeConnection($con2);
                                }
                                else 
                                    echo "There has been a mistake!";
                            }
                        }
                        else 
                            echo "There has been a mistake!";
                        closeConnection($con1);
                    }
                }
                else 
                    echo "There has been a mistake!";
                closeConnection($con);
            }
        }
        else
            echo "Unesite validno ime!";
    }
    elseif($_GET['task']=='changeItem'){
        $idItem=intval($_POST['idItem']);
        $nameItem=trim($_POST['nameItem']);
        $price=$_POST['price'];
        $discount=$_POST['discount'];
        $active=$_POST['active'];
        $gender=$_POST['gender'];
        $idBrand=$_POST['idBrand'];

        if(preg_match($patternItemName,$nameItem)){
            $con=getConnection();
            if($con){
                $query='UPDATE items SET nameItem="'.$nameItem.'", price='.$price.', discount='.$discount.', active='.$active.', idBrand='.$idBrand.', gender="'.$gender.'" WHERE idItem=1; ';
                $res=mysqli_query($con,$query);
                if(!mysqli_error($con)){
                    //ako je dodato još slika
                    if(isset($_POST['img'])){
                        $images=[];
                        $images=$_POST['img'];
                        $values="";
                        for($i=0;$i<count($images);$i++){
                            if($i==0)
                                $values.= "('".$images[$i]."',".$idItem.")";
                            else
                                $values.= ",('".$images[$i]."',".$idItem.")";
                        }
                        $con2=getConnection();
                        if($con2){
                            $res=mysqli_query($con2,'INSERT INTO pictures (urlPicture,idItem) VALUES '.$values.'');
                            if(!mysqli_error($con2)){
                                echo "Uspešno promenjeno!~";
                                //ako su uspesno dodate treba da se vrate sve
                                $con3=getConnection();
                                if($con3){
                                    $res=mysqli_query($con3,'SELECT * FROM pictures WHERE idItem='.$idItem.';');
                                    if(!mysqli_error($con3)){
                                        $arr=[];
                                        for($i=0;$i<mysqli_num_rows($res);$i++)
                                            array_push($arr,mysqli_fetch_assoc($res));
                                        echo json_encode($arr);
                                    }
                                    else 
                                        echo "There has been a mistake!";
                                    closeConnection($con3);
                                }
                            }
                            else 
                                echo "There has been a mistake!";
                            closeConnection($con2);
                        }
                    }
                    else
                        echo "Uspešno promenjeno!";
                }
                else 
                    echo "There has been a mistake!".mysqli_error($con);
                closeConnection($con);
            }
        }
        else 
            echo "Unesite validan naziv!";
    }
    elseif($_GET['task']=='addSize'){
        $idItem=$_POST['idItem'];
        $idSize=$_POST['idSize'];
        $amount=$_POST['amount'];
        
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,"call sp_addSize(".$idItem.",".$idSize.",".$amount.")");
            if(!mysqli_error($con))
                echo "Uspešno dodato!";
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='changeSubcategoriesOfItem'){
        $idItem=$_POST['idItem'];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,"DELETE FROM belonging_items_subcategories WHERE idItem=".$idItem.";");
            if(!mysqli_error($con)){
                if(isset($_POST['subcategories'])){
                    $subcategories=$_POST['subcategories'];
                    $values="";
                    for($i=0;$i<count($subcategories);$i++)
                        if($i==0)
                            $values.="(".$idItem.",'".$subcategories[$i]."')";
                        else
                            $values.=",(".$idItem.",'".$subcategories[$i]."')";
                    $con1=getConnection();
                    if($con1){
                        $res=mysqli_query($con,"INSERT INTO belonging_items_subcategories VALUES ".$values.";");
                        if(!mysqli_error($con1))
                            echo "Uspešno promenjeno!";
                        else
                            echo "There has been a mistake!";
                        closeConnection($con1);
                    }
                }
                else 
                    echo "Uspešno promenjeno!";
            }
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
    elseif($_GET['task']=='deleteImages'){
        $idItem=$_POST['idItem'];
        $ids=$_POST['ids'];
        $picturesIds="";
        for($i=0;$i<count($ids);$i++)
            if($i==0)
                $picturesIds.=$ids[$i];
            else 
                $picturesIds.=",".$ids[$i];
        $con=getConnection();
        if($con){
            $res=mysqli_query($con,'DELETE FROM pictures WHERE idItem='.$idItem.' AND idPicture IN ('.$picturesIds.');');
            if(!mysqli_error($con))
                echo "Uspešno obrisano!";
            else 
                echo "There has been a mistake!";
            closeConnection($con);
        }
    }
?>