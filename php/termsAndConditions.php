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
    <title>Uslovi kupovine</title>
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
        <div class='row m-3 text-justify'>
            <h1 class="text-warning underline mb-3"> Opšti uslovi korišćenja usluga </h1>
            <p class="col-12 p-0">
                N SPORT D.O.O. ZA SPOLJNU I UNUTRAŠNJU TRGOVINU BEOGRAD <br>
                Auto put Beograd - Novi sad 150a, matični broj: 17067648, PIB: 100572465 <br>

                Na osnovu Zakona o privrednim društvima, Dalibor Šarić direktor N Sport d.o.o. Beograd, doneo je dana 23.10.2019. godine u Beogradu ove

                <br><b> OPŠTE USLOVE KORIŠĆENJA USLUGA N SPORT D.O.O. BEOGRAD SA ODREDBAMA O ZAŠTITI PODATAKA O LIČNOSTI</b>
            </p>
            I OSNOVNE ODREDBE

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 1.</p>
            Ovim opštim uslovima o korišćenju usluga Internet prodaje (u daljem tekstu: Opšti uslovi) na jedinstven način utvrđuju se uslovi pod kojima N Sport d.o.o. Beograd (u daljem tekstu: N Sport) pruža usluge Internet prodaje – elektronske prodavnice – korisniku (u daljem tekstu: usluge) i utvrđuje postupak za ostvarivanje međusobnih prava i obaveza N Sport i korisnika usluga (u daljem tekstu: korisnik).

            N Sport vrši elektronsku trgovinu kao prodaju robe/usluga preko sopstvene elektronske prodavnice, u skladu sa članom 17 stavom 3 tačkom 1) Zakona o trgovini.

            Opšti uslovi su obavezujući za N Sport i sve korisnike i primenjuju se na sve njihove međusobne ugovorne odnose, izuzev ako posebnim ugovorom N Sport i korisnik ne ugovore drugačije.

            <br><br> II ZASNIVANJE KORISNIČKOG ODNOSA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 2.</p>
            Korisnik usluga N Sport može postati svako zainteresovano poslovno sposobno, domaće ili strano, fizičko ili pravno lice odnosno preduzetnik.

            Korisnikom usluga N Sport se postaje samim pristupom Internet servisu odnosno korišćenjem usluga N Sport, pod uslovima i na način iz ovih Opštih uslova. Svakim pojedinim pristupom Internet servisu korisnik potvrđuje da je upoznat sa odredbama ovih Opštih uslova i da je u potpunosti saglasan sa njima. Ovi Opšti uslovi primenjuju se na svaki pojedini pristup svakog pojedinačnog lica (korisnika).

            Za korišćenje usluga korisnik navodi samo one podatke o sebi koji su neophodni za izvršenje tih usluga za čije je korišćenje zainteresovan. N Sport lične podatke prikuplja i koristi isključivo u skladu sa Pravilnikom o zaštiti podataka o ličnosti koji su sastavni deo ovih Opštih uslova.

            Ukoliko korisnik to želi, može postati Registrovani korisnik, tako što će podneti zahtev za zasnivanje korisničkog odnosa. Zahetv se podnosi pisanim putem u elektronskom obliku od strane korisnika ili lica koje on ovlasti, putem formulara na Internet servisu N Sport-a.

            Registrovani korisnik se predstavlja N Sport Internet servisu svojom potvrđenom (verifikovanom) adresom elektronske pošte.

            Registrovani korisnik se obavezuje da u slučaju promene podataka značajnih za identifikaciju odnosno izvršenje usluga, o istom bez odlaganja obavesti N Sport.

            Potvrdom zahteva za zasnivanje korisničkog odnosa korisnik potvrđuje da je upoznat sa odredbama ovih Opštih uslova i da je u potpunosti saglasan sa njima.

            <br><br> III POČETAK I TRAJANJE KORISNIČKOG ODNOSA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 3.</p>
            Stupanje u korisnički odnos smatra se zaključenjem ugovora koji stupa na snagu momentom davanja saglasnosti na odredbe ovih Opšte uslova. Saglasnost se daje konkludentnom radnjom – pristupanjem Internet servisu.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 4.</p>
            Korisnički odnos između Registrovanih korisnika i N Sport-a se zaključuje na neodređeno vreme.

            <br><br> IV NEMOGUĆNOST PRENOSA PRAVA KORIŠĆENJA USLUGA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 5.</p>
            Pravo na korišćenje usluga N Sport koje korisnik stekne u skladu sa ovim Opštim uslovima ne može se preneti na treće lice.

            <br><br>  V CENE USLUGA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 6.</p>
            N Sport samostalno utvrđuje cene i druge komercijalne uslove za pružanje usluga, u skladu sa svojom poslovnom politikom.

            U skladu sa zakonom, sve navedene cene su sa uračunatim pripadajućim PDV-om.

            Cene i drugi komercijalni uslovi pružanja usluga dostupni su korisniku u svakom trenutku putem Internet servisa N Sport-a.

            N Sport zadržava pravo na izmenu cena i drugih komercijalnih uslova pružanja usluga, o čemu putem Internet servisa obaveštava korisnika.

            N Sport zadržava pravo da, s obzirom na tehnička ograničenja koja se odnose na merenje stanja zaliha u realnom vremenu, prilikom Internet prodaje prikaže i cenu artikla koji se momentu prikazivanja ne nalazi na zalihama. U navedenom smislu, N Sport ne garantuje za stanje zaliha u svakom momentu.

            Kako N Sport obavlja elektronsku trgovinu koja je istovremeno usmerena na potrošače u Republici Srbiji i na potrošače u inostranstvu, N Sport zadržava pravo da cenu istakne i u stranoj valuti na način kojim se potrošaču daje mogućnost da izabere valutu u kojoj će se prikazati prodajna cena robe/usluge iz celokupne ponude N Sporta, s tim da se potrošaču koji elektronskoj trgovini pristupa iz Republike Srbije cena prikazuje prvo u dinarima, u skladu sa postojećim tehničkim mogućnostima.

            <br><br> VI PLAĆANJA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 7.</p>
            Predračuni i računi sadrže, između ostalog, obračunati iznos za korišćenje usluga N Sporta i obaveze po osnovu javnih prihoda (porezi, takse i dugo), kao i informacije o načinu i roku plaćanja, izjavu reklamacije i obaveštenje o mogućnosti jednostranog raskida, u skladu sa Zakonom.

            Račune za pružene usluge N Sport dostavlja:
            - Registrovanom korisniku putem elektronske pošte, na registrovanu adresu korisnika, nakon prijema narudžbine, u toku trajanja korisničkog odnosa.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 8.</p>
            Prilikom plaćanja korišćenjem platnih kartica Visa, MasterCard, Maestro i American Express. Korisnik plaća obračunati iznos, dok troškove transakcije (bankarska provizija) snosi N Sport.

            Prilikom plaćanja pouzećem, korisnik plaća obračunati iznos Kurirskoj službi, prema dostavljenom računu.

            Prilikom plaćanja uplatnicom, korisnik plaća obračunati iznos u roku od 48 časova, prema podacima za uplatu.
            Korisnik se obavezuje da plaćanje izvrši na način koji sam odabere. Jednom odabrani način plaćanja, po pojedinačnoj transakciji, Korisnik ne može menjati dok se transakcija ne izvrši na odabrani način. Ovo naročito važi za slučaj kada se Korisnik opredelio da naručeni artikal preuzme u N Sport prodajnom objektu (Radi dobrog razumevanja: ukoliko je Korisnik odabrao da plaćanje izvrši gotovinski, nije moguće da u prodajnom objektu plaćanje izvrši na drugi način npr. platnom karticom ili čekom, i obrnuto).

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 9.</p>
            Predračuni se korisniku dostavljaju u elektronskom obliku na odgovarajućoj stranici Internet servisa.

            Dostavu računa u štampanom obliku N Sport vrši putem obične pošte, na poseban zahtev Registrovanog korisnika, za šta može Registrovanom korisniku naplatiti takve troškove poštanskih usluga.

            Podaci o računima Registrovanih korisnika dostupni su i putem Internet servisa N Sporta samo tom Registrovanom korisniku.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 10.</p>

            N Sport nije odgovoran u slučaju da korisnik ne primi blagovremeno predračun ili račun (usled kvara na računarskoj mreži Registrovanog korisnika ili propusta u radu poštanske službe).

            Korisnik je dužan da odmah po isteku uobičajenog roka za prijem predračuna ili računa obavesti N Sport o izostanku i zatraži slanje duplikata.

            <br><br>  VII REKLAMACIJE I PRAVO NA RASKID UGOVORA O KUPOVINI

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 11.</p>

            N Sport ima zakonsku odgovornosti zbog nesaobraznosti robe ili usluge ugovoru.

            Na sve reklamacije, primenjuje se važeći Pravilnik o rešavanju reklamacija N Sport d.o.o. Beograd i Zakon o zaštiti potrošača.

            Ukoliko korisnik smatra da obračun usluga nije pravilno izvršen može podneti N Sportu reklamaciju u pisanom obliku, putem elektronske pošte ili faksom, u roku od 24 časa od prijema predračuna ili računa, s tim što je dužan da uplati iznos koji reklamacijom nije osporen.

            Korisnik može podneti zahtev za reklamaciju na isti način ukoliko je došlo do greške u izboru usluga ili postoji drugi razlog zbog koga korisnik nije u mogućnosti da koristi uslugu, pod uslovom da korisnik već nije započeo korišćenje usluga.

            Reklamacija mora da sadrži precizan opis nepravilnosti u obračunu ili drugog razloga za reklamaciju i mora biti potpisana od strane korisnika ili lica koje je ovlašćeno za zastupanje korisnika.

            Ukoliko je plaćanje obavljeno korišćenjem platnih kartica Visa, MasterCard, Maestro i American Express. Korisnik u zahtevu za reklamaciju obavezno navodi i podatke iz potvrde o uspešno obavljenom plaćanju (TRANSACTION_ID, AUTH_CODE).

            N Sport je dužan da u roku od 8 dana po prijemu reklamacije obavesti korisnika o tome da li je reklamacija usvojena.

            N Sport neće uvažiti reklamacije koje su nejasne, nepotpune i podnete neblagovremeno, kao ni reklamacije koje nisu podnete od strane korisnika ili lica ovlašćenog za zastupanje korisnika.

            U slučaju usvajanja reklamacije za nepravilno obračunate usluge, N Sport će korisniku izdati novi predračun ili račun sa naznačenim rokom uplate, umanjen za uplaćeni iznos koji reklamacijom nije osporen.

            U slučaju usvajanja reklamacije za pogrešno izabrane usluge, N Sport će korisniku izdati predračun ili račun ispravljen u skladu sa zahtevima korisnika, umanjen za uplaćeni iznos ili obaviti povraćaj uplaćenog viška.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 12.</p>

            Korisnik koji je robu kupio putem Interneta odnosno ukoliko mu je roba dostavljena kurirskom službom (ugovor na daljinu) ima pravo da odustane od ugovora zaključenog na daljinu, u roku od 14 dana, bez navođenja razloga i dodatnih troškova, osim troškova vraćanja robe, koje u ovom slučaju snosi korisnik. Rok od 14 dana računa se od trenutka kada roba dospe u državinu korisnika, odnosno trećeg lica koje je odredio Korisnik, a koje nije prevoznik. Istekom roka iz ovog stava, prestaje pravo korisnika na odustanak od ugovora.

            Korisnik ostvaruje pravo na odustanak od ugovora izjavom koja mu se dostavlja prilikom registracije, u elektronskom obliku i moguće je koristiti je prilikom svake pojedine kupovine. Ova izjava dostavlja se N Sport-u putem elektronske pošte, a o prijemu iste korisnik će biti obavešten takođe putem elektronske pošte.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 13.</p>

            U slučaju odustanka od Ugovora korisnik je dužan da vrati robu N SPORT- u, bez odlaganja, a najkasnije u roku od 14 dana od dana kada je poslao obrazac za odustanak. Roba se vraća o trošku korisnika, slanjem robe poštom na adresu N Sport d.o.o. Beograd, Autoput Beograd-Novi Sad 150a, 11080 Beograd - Zemun. Korisnik je isključivo odgovoran za umanjenu vrednost robe koja nastane kao posledica rukovanja robom na način koji nije adekvatan, odnosno prevazilazi ono što je neophodno da bi se ustanovili priroda, karakteristike i funkcionalnost robe. Korisnik je obavezan da proizvod vrati kao nekorišćeni, neoštećeni i u originalnoj ambalaži, odnosno u istom stanju u kakvom je i isporučen, bez ikakvih mehaničkih ili bilo kakvih drugih oštećenja.

            N Sport d.o.o. je u slučaju zakonitog odustanka od ugovora dužan da korisniku vrati iznos koji je korisnik platio po osnovu ugovora i to u roku od 14 dana od dana kada je primio obrazac za odustanak. N Sport d.o.o može da odloži povraćaj sredstava dok ne dobije robu koja se vraća, ili dok Kupac ne dostavi dokaz da je poslao robu Prodavcu u zavisnosti od toga šta nastupa prvo.

            Korisniku koji je plaćanje izvršio nekom od platnih kartica, delimično ili u celosti, a bez obzira na razlog vraćanja, N Sport se obavezuje da povraćaj vrši isključivo preko VISA, EC/MC, Maestro i American Express metoda plaćanja, što znači da će banka na zahtev prodavca obaviti povraćaj sredstava na račun korisnika kartice.

            Korisnik se pristankom na ove Uslove korišćenja daje izričitu saglasnost (u smislu člana 34. stava 2 Zakona o zaštiti potrošača) N Sportu da povraćaj uplata koje je primio od Korisnika koji je robu platio pouzećem, N Sport izvrši na tekući račun Korisnika bez dodatnih troškova po Korisnika, te se Korisnik obavezuje da u navedenom slučaju dostavi N Sportu podatke tekućem računu na koji želi da bude izvršen povraćaj uplata.

            Sva plaćanja biće izvršena u lokalnoj valuti Republike Srbije – dinar (RSD). Za informativni prikaz cena u drugim valutama koristi se srednji kurs Narodne Banke Srbije. Iznos za koji će biti zadužena platna kartica biće izražen u lokalnoj valuti korisnika, kroz konverziju u istu po kursu koji koriste kartičarske organizacije, a koji Kompaniji u trenutku transakcije ne može biti poznat. Kao rezultat ove konverzije postoji mogućnost neznatne razlike od originalne cene navedene na sajtu N Sporta.

            Korisnik je isključivo odgovoran za umanjenu vrednost robe koja nastane kao posledica rukovanja robom na način koji nije adekvatan (u smislu člana 35. stava 4 Zakona o zaštiti potrošača), odnosno prevazilazi ono što je neophodno da bi se ustanovili priroda, karakteristike i funkcionalnost robe. Nakon prijema robe Korisnik ima pravo da istu proba, ali ukoliko je upotrebljava na način koji nije adekvatan, odnosno prevazilazi ono što je neophodno da bi se ustanovili priroda, karakteristike i funkcionalnost robe, Korisnik je isključivo odgovoran za umanjenu vrednost robe i N Sport može da tu umanjenu vrednost robe naplati Korisniku. Radi dobrog razumevanja, N Sport zadržava pravo da ne uvaži reklamaciju odnosno ne pristane na povraćaj cene ukoliko je Korisnik robu, pored toga što ju je probao, i koristio nakon što je probanjem ustanovio prirodu, karakteristike i funkcionalnost robe (npr. robu nosio na ulici, ili koristio za svoje potrebe, ili pohabao, ili roba ima vidljive ili funkcionalne tragove upotrebe, ili sl.).

            <br><br> VIII KORIŠĆENJE USLUGA I DOSTAVA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 14.</p>

            N Sport se obavezuje da korisniku obezbedi korišćenje svojih Internet servisa, u okviru svojih tehničkih mogućnosti, s tim što zadržava pravo da, bez posebne najave, zbog radova na sistemu ili drugih potreba održavanja privremeno obustavi pružanje usluga, delimično ili u celosti, sve dok za tim postoji opravdana potreba.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 15.</p>

            N Sport se obavezuje da će na prikladan način informisati korisnika o relevantnoj domaćoj zakonskoj regulativi iz oblasti Internet trgovine, te opšteprihvaćenim preporukama i kodeksima koji se odnose na dozvoljeno i prihvatljivo korišćenje servisa za Internet trgovinu.

            Korisnik se obavezuje da će servise N Sporta koristiti u skladu sa zakonskom regulativom, preporukama i kodeksima iz prethodnog stava.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 16.</p>

            Svojim Internet servisom N Sport omogućuje korisniku da naruči ponuđenu robu iz “N Selection”, “N Sport” i “N Fashion” prodajnog asortimana.

            Momentom naručivanja robe smatraju se:

            - potvrda narudžbine na Internet servisu N Sporta kada se roba plaća pouzećem;
            - prijem iznosa iz narudžbenice kada se roba plaća na drugi način.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 17.</p>

            N Sport se obavezuje da robu naručenu u periodu od ponedeljka u 00 časova do petka u 10 časova dostavi kurirskoj službi u roku od 72 časa.

            N Sport se obavezuje da robu naručenu u periodu od petka u 10 časova do nedelje u 24 časa dostavi kurirskoj službi najkasnije u četvrtak.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 18.</p>

            Kurirska služba dostavlja naručenu robu u roku od jednog dana.

            Kurirska služba ima isključivu odgovornost za isporuku naručene robe korisniku.

            Kurirska služba pošiljku donosi na adresu za isporuku korisniku u periodu od 8-16 h. Korisnik se obavezuje da u tom periodu obezbedi da na adresi bude lice koje može preuzeti pošiljku. Kurirska služba pokušava da uruči pošiljku u dva navrata. Ukoliko korisnik ili lice koje može preuzeti pošiljku ne bude na adresi dostave, Kurirska služba će kontaktirati korisnika na telefon korisnika i dogovori novi termin isporuke. Ukoliko korisnik ni tada ne bude pronađen na adresi dostave, pošiljka će se vratiti Kurirskoj službi. Po povratku pošiljke, korisnik će biti kontaktiran kako bi se ustanovio razlog neuručenja i kako bi se dogovorilo ponovno slanje.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 19.</p>

            Prilikom preuzimanja pošiljke potrebno je da korisnik vizuelno pregleda paket da slučajno ne postoje neka vidna oštećenja. Ukoliko korisnik primeti da je transportna kutija oštećenja i posumnja da je i proizvod možda oštećen, korisnik je ovlašćen da odbije prijem pošiljke.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 20.</p>

            Korisnik se obavezuje da neoštećenu pošiljku preuzme i potpiše dokaz o prijemu pošiljke - adresnicu Kurirskoj službi.
            

            <br><br> IX PRAVILNIK O ZAŠTITI PODATAKA O LIČNOSTI

            <br><br>  I  OBAVEŠTENJE O PRIKUPLJANJU I OBRADI PODATAKA O LIČNOSTI I OPŠTE NAPOMENE

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 21.</p>

            N Sport poštuje privatnost Korisnika. Ovo obaveštenje će Vam pomoći da razumete naša pravila privatnosti i molimo Vas da ga detaljno pročitate. Ukoliko niste saglasni sa bilo kojom odredbom Pravilnika o zaštiti podataka o ličnosti (u daljem tekstu: Pravilnik), molimo Vas da se uzdržite od korišćenja usluga N Sporta i da nam ne dostavljate Vaše podatke o ličnosti.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 22.</p>

            U cilju zaštite Vaše privatnosti, N Sport primenjuje odgovarajuće tehničke, organizacione i kadrovske mere i konstatno radi na njihovom unapređenju kako bi obezbedio da se sa Vašim podacima postupa u skladu sa primenjivim propisima i Vašim pristankom (onda kada je taj pristanak potreban u skladu sa zakonom). U vezi sa tim, N Sport može povremeno menjati i unapređivati ovaj Pravilnik, u kom slučaju će obezbediti da Vam ažurirana verzija bude dostupna na internet stranici, sa jasno naznačenim početkom primene. Pozivamo Vas da prilikom posete naših internet stranica proverite da li je nakon Vaše poslednje posete došlo do izmene Pravilnika, te ukoliko jeste da se upoznate sa sadržinom novog Pravilnika. Ukoliko ne budete saglasni sa novim pravilima, molimo Vas da se uzdržite od korišćenja naših internet stranica i dostavljanja Vaših podataka na bilo koji način, odnosno povučete saglasnost za obradu podataka o ličnosti u skladu sa Pravilnikom.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 23.</p>

            Do teksta važećeg Pravilnika možete doći putem linka u podnožju naših internet stranica uvek kada imate pristup internetu. Nastavkom korišćenja internet stranica smatra se da ste pročitali i razumeli važeći Pravilnik, i da ste nakon čitanja i razumevanja pristali na njegovu primenu.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 24.</p>

            Na ovaj Pravilnik i njegove moguće izmene i dopune primenjuju se propisi Republike Srbije.

            <br><br> II  PODACI O RUKOVAOCU

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 25.</p>

            Identitet i kontakt podacima Rukovaoca, odnosno pravnog lica koje je odgovorno za upravljanje Vašim podacima je definisan ovim članom

            Rukovalac vašim podacima je: <br>
            Poslovno ime: N SPORT DOO BEOGRAD <br>
            Matični broj: 17067648 <br>
            PIB: 100572465 <br>
            Adresa sedišta: Auto put Beograd - Novi sad 150 a, 11080 Beograd-Zemun, Srbija <br>
            E-pošta: info@n-sport.net <br>
            Šifra delatnosti: 4778 - Ostala trgovina na malo novim proizvodima u spec. prodavnicama <br>

            Kontakt podaci rukovaoca za ostvarivanje prava na zaštitu podataka o ličnosti su: <br>
            N SPORT DOO BEOGRAD <br>
            Auto put Beograd - Novi sad 150 a, 11080 Beograd-Zemun, Srbija <br>
            Radno vreme: od ponedeljka do petka od 8:00h do 16:00h <br>
            Tel: +381 11 3778 901 <br>
            Faks: +381 11 3778 902 <br>
            Email: zastitapodataka@n-sport.net

            <br><br> III NAČIN PRIKUPLJANJA PODATAKA
                
            <br><br><p class='col-12 p-0 font-weight-bold'>Član 26.</p>

            Vaše podatke o ličnosti prikupljamo sledeće načine:

            - direktno od Vas, kada nam neposredno dostavite Vaše podatke. Na primer, kada nam se obratite poštom, elektrnonskom poštom, odnosno telefonskim putem ili
            - automatski, prilikom korišćenja naših internet stranica, u kom slučaju mi ili naše partnerske treće strane u naše ime, upotrebljavamo tzv. „cookie“ tehnologije za adekvatno prikazivanje sadržaja i praćenje količine poseta i kretanja po našim stranicama i slično (u daljem tekstu “cookie”. Molimo Vas da više informacija o tome kako koristimo “cookie” pročitate u posebnom odeljku ovog Pravilnika.

            <br><br> IV PODACI KOJE RUKOVALAC OBRAĐUJE, NAČIN I OSNOV KORIŠĆENJA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 27.</p>

            N Sport prikuplja podatke koji su primereni, bitni i ograničeni na ono što je neophodno u odnosu na konkretnu svrhu obrade.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 28.</p>

            Vaše podatke koristimo ili možemo da koristimo za postizanje više svrha koje su unapred određene u ovom Pravilniku. Za svaku obradu koju preduzimamo postoji odgovarajući osnov koji je propisan Zakonom o zaštiti podataka o ličnosti i drugim zakonima Republike Srbije.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 29.</p>

            Podatke koje neposredno prikupljamo možemo upoređivati i kombinovati sa podacima koje smo prikupili automatski, te na taj način podaci koji su prikupljeni kao anonimni (na primer, podatak o učestalosti posete određenom sadržaju na našoj internet stranici) mogu postati podaci o ličnosti (na primer, koliko često Vi posećujete određeni sadržaj na našoj internet stranici). To nam omogućava da Vam pružimo personalizovano iskustvo prilikom posete internet stranici.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 30.</p>

            Takođe, određene podatke možemo da anonimizujemo i koristimo za statističke i analitičke svrhe, a da se pritom pojedinačna lica na koja se ti podaci odnose ne mogu ni posredno ni neposredno identifikovati. Tako, na primer, možemo da dođemo do podatka o procentu posetilaca koji su pogledali određeni video materijal ili posetili drugi sadržaj na internet stranici.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 31.</p>

            N Sport obrađuje ili može da obrađuje neke ili sve podatke koji su navedeni ovde. Mi konstantno radimo na razvoju internet stranice i unapređivanju dostupnih servisa, usled čega je moguće da obrada pojedinih podataka koji su niže navedeni započne kasnije, kako se funkcionalnosti internet stranice budu povećavale. Takođe, obim podataka koje obrađujemo zavisi, na primer, i od toga na koji način i iz kojih razloga nas kontaktirate ili na koji način koristite internet stranica.
            

            Podatak koji se obrađuje

            Svrha obrade

            Pravni osnov obrade

            Ime, prezime, elektronska adresa, i/ili poštanska adresa (mesto, poštanski broj, ulica, kućni broj), i/ili broj telefona

            Komunikacija sa Vama – odgovaranje na Vaša pitanja i zahteve koje nam šaljete putem kontakt forme na internet stranici ili drugih kanala komunikacije (elektronske ili redovne pošte ili telefona)

            Naš legitimni interes da upravljamo servisom za korisnike i da na adekvatan način odgovaramo na pitanja i zahteve korisnika

            Korisničko ime, elektronska adresa i lozinka za Vaš profil na internet stranici

            Kreiranje korisničkog profila na internet stranici putem kojeg može biti omogućena elektronska kupoprodaja proizvoda

            Naš legitimni interes da obezbedimo personalizovano korisničko iskustvo, jeste zaključenje ugovora o kupoprodaji proizvoda na Vaš zahtev

            Ime, prezime, poštanska adresa (mesto, poštanski broj, ulica, kućni broj), podaci o plaćanju (broj platne kartice i poslovna banka kod koje se vodi račun)

            Realizacija elektronske kupoprodaje proizvoda

            Izvršenje ugovora o kupoprodaji proizvoda i poštovanje naših pravnih obaveza (vođenje evidencije prometa u smislu propisa o računovodstvu)

            Ime, prezime, kontakt podaci (poštanska adresa – mesto, poštanski broj, ulica i kućni broj, i/ili elektronska adresa, i/ili broj telefona), podaci o kupljenom proizvodu, datumu kupovine, načinu rešavanja reklamacije

            Rešavanje reklamacije u pogledu saobraznosti proizvoda kupljenih putem elektronske prodavnice na internet stranici

            Poštovanje naših pravnih obaveza iz oblasti zaštite potrošača

            Podatak o izabranim podešavanjima prema kojima se prikazuje internet stranica

            Obezbeđivanje funkcionalnosti internet stranice u pogledu privremenog “pamćenja” odabranih podešavanja prikazivanja sadržaja internet stranice

            Naš legitimni interes da posetiocima internet stranice obezbedimo da im se sadržina internet stranice prikazuje na odabranom jeziku prilikom svake posete u određenom vremenskom sistemu (obično tokom jednog dana)

            Identifikacioni podaci Vašeg uređaja putem kojeg pristupate internet stranici (tzv. Jedinstveni identifikator uređaja), vrsta uređaja (računar, tablet, pametni telefon), IP adresa uređaja, operativni sistem uređaja, veb-pregledač koji koristite za pristup internet stranici

            Tehničko administriranje internet stranice i obezbeđivanje da se sadržaj internet stranice optimalno prikazuje na uređaju koji koristite

            Naš legitimni interes da obezbedimo adekvatno korišćenja internet stranice u skladu sa tehničkim funkcionalnostima internet stranice i da isti prikažemo na najoptimalniji način.

            Podaci o načinu na koji koristite internet stranica (na primer, koje stranice u okviru internet stranice ste posetili, koliko puta, kada ste se ulogovali, na koje sadržaje ste “kliknuli” i slično)

            Razumevanje sklonosti i interesovanja posetilaca internet stranice, poboljšavanje korisničkog iskustva i unapređivanje internet stranice, naših proizvoda i servisa, statističke i analitičke svrhe

            Vaš pristanak

            Podaci o online marketinškim sadržajima kojima ste pristupili pre pristupanja internet stranici

            Utvrđivanje trendova i unapređivanje internet stranice, naših proizvoda i servisa statističke i analitičke svrhe

            Vaš pristanak


            Detaljne informacije o podacima koje automatski prikupljamo putem “cookie-ja”, naći ćete u posebnom odeljku ovog Pravilnika.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 32.</p>

            Ukoliko se naknadno pojavi potreba za obradom Vaših podataka u drugu svrhu koja je različita od one za koju su podaci prikupljeni, N Sport će Vam, pre započinjanja dalje obrade, pružiti informacije o toj drugoj svrsi, kao i sve ostale bitne informacije u skladu sa zakonom.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 33.</p>

            N Sport neće nikada namerno niti ciljno prikupljati i obrađivati podatke o maloletnim licima, ali ne možemo isključiti mogućnost davanja netačnih podataka od strane lica koja nas kontaktiraju. Ukoliko ste roditelj ili zakonski zastupnik maloletnog lica i poznato Vam je da su nam dostavljeni podaci tog lica, molimo Vas da nas o tome bez odlaganja obavestite kako bismo preduzeli odgovarajuće mere i prekinuli dalju obradu ovih podataka.

            <h3 id="cookies" class='col-12 p-0 mt-3'>„COOKIE“ PODACI - KOLAČIĆI</h3>

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 34.</p>

            Naša internet stranica koristi “cookie” da bismo Vam obezbedili najbolje korisničko iskustvo. Oni nam omogućavaju da, na primer, prikažemo sadržaj internet stranice na adekvatan način na svakom Vašem uređaju odnosno internet-pregledaču („web browser“) putem kojeg mu pristupate, ili da prilagodimo sadržaj koji plasiramo na internet stranici Vašim interesovanjima. Informacije koje na taj način prikupljamo u nekim slučajevima mogu biti podaci o ličnosti, ali i ne moraju. Budući da N Sport poštuje Vašu privatnost, u ovom odeljku želimo da Vam objasnimo šta su i na koji način koristimo “cookie” podatke, kao i da Vam predočimo da u određenim slučajevima možete da upravljate načinom na koji koristimo “cookie-je”, a u vezi sa Vašom upotrebom internet stranice. Kada Vam internet stranica omogući da ne izaberete odnosno isključite određene “cookie-je” i Vi se odlučite za tu opciju, to može umanjiti funkcionalnost internet stranice. Takođe, imajte u vidu da ćete postavke koje ste odlučili da isključite, morati da isključite i za svaki drugi uređaj ili veb-pregledač posebno.

            Kolačići su jednostavne tekstualne datoteke koje se čuvaju u veb-pregledaču korisnika, odnosno na njegovom uređaju. Zadatak “cookie-ja” je pre svega da omogući internet stranici da „prepozna“ korisnika kada mu ovaj naredni put pristupi. U toj situaciji, internet stranica koristi podatke sačuvane u “cookie-ju” i tako automatski dobija informaciju o prethodnoj aktivnosti korisnika na internet stranici. Kolačići ne mogu da pristupe Vašim podacima koje čuvate na uređajima, ali mogu da prikupljaju podatke o Vašim online aktivnostima.

            Kolačići nisu štetni po korisnika ili njegov uređaj i ne treba ih mešati sa virusima. Kolačići ne sadrže viruse niti drugi zlonamerni kod.

            U zavisnosti od toga koliko dugo se zadržavaju u veb-pregledaču korisnika, razlikujemo “cookie” sesije (zadržavaju se samo u toku konkretne sesije pretraživanja i po njenom okončanju se automatski brišu, a koriste se da bi Vam omogućili pristup konkretnom sadržaju) ili trajne “cookie” (duži ili kraći period, „pamte“ informacije za naredne posete internet stranici, zadržavaju se dok ih ne izbrišite ručno ili dok ne isteknu prema predviđenom vremenu trajanja, npr. “cookie” koji omogućavaju da internet stranica „pamti“ podatke za prijavljivanje u slučaju kreiranja naloga ili “cookie” za praćenje).

            Prema tome kome pripadaju, postoje “cookie” prve strane (“cookie” koje je postavio N Sport kao vlasnik internet stranica koji pretražujete) i “cookie” treće strane (“cookie” koje postavljaju naše odabrane partnerske kompanije koje nam pružaju razne usluge, npr. analitičke jer prikupljaju i obrađuju određene podatke o načinu korišćenja internet stranica i dostavljaju nam informacije u anonimiziranom obliku, ili ovi “cookie-ji” omogućavaju da Vam se na njihovim internet stranicama ili drugim lokacijama na Internetu prikazuje povezani sadržaj).

            Kolačići mogu da sadrže različite informacije i da se koriste za različite namene. N Sport koristi sledeće vrste “cookie-ja”:

           <br> OBAVEZNI – apsolutno neophodni “cookie-ji” - Obavezni kolačići čine stranicu upotrebljivom omogućavajući osnovne funkcije kao što su navigacija stranicom i pristup zaštićenim sadržajima. N Sport koristi kolačiće koji su neophodni za ispravno funkcionisanje naše web stranice, kako bismo omogućili pojedine tehničke funkcije i tako Vam osigurali pozitivno korisničko iskustvo.

           <br> TRAJNI – neophodni radi olakšanog pristupa - Ovi kolačići obično imaju datum isteka daleko u budućnosti i kao takvi će ostati u Vašem veb-pregledaču, dok ne isteknu, ili dok ih ručno ne izbrišete. Koristimo trajne kolačiće za funkcionalnosti kao što su “Ostanite prijavljeni”, što korisniku olakšava pristup kao registrovanom korisniku. Takođe, koristimo trajne kolačiće kako bismo bolje razumeli navike korisnika, da možemo da poboljšamo web stranicu prema Vašim navikama. Ova informacija je anonimna – ne vidimo individualne podatke korisnika.

           <br> STATISTIKA -  statistički “cookie-ji” - Statistički kolačići anonimnim prikupljanjem i slanjem podataka pomažu vlasnicima stranice da shvate na koji način posetioci komuniciraju sa stranicom. Radi se o kolačićima koji N Sportu omogućuju web analitiku, tj. analizu upotrebe naših stranica i merenje posećenosti, koju N Sport sprovodi kako bi poboljšao kvalitet i sadržaj ponuđenih usluga.

           <br> MARKETING -  “cookie” podaci za marketinške svrhe - Marketinški kolačići koriste se za praćenje posetilaca kroz web-stranice. Koriste se kako bi se korisnicima prikazivali relevantni oglasi i podstakli ih na učestvovanje, što je bitno za izdavače i oglašavače trećih strana.
            Možete upravljati korišćenjem “cookie-ja” odabirom odgovarajućih podešavanja u Vašem veb-pregledaču. Više informacija možete naći na sledećim linkovima:
            

           <br> Chrome	Sprečavanje instaliranja i brisanje postojećih “cookie-ja”	srpski	https://support.google.com/chrome/answer/95647?hl=sr
            engleski	https://support.google.com/chrome/answer/95647?hl=en
           <br> Firefox	Sprečavanje instaliranja “cookie-ja”	sprski	https://support.mozilla.org/sr/kb/omogucavanje-i-onemogucavanje-kolacica
            engleski	https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences
            <br>  Firefox	Brisanje postojećih “cookie-ja”	srpski	https://support.mozilla.org/sr/kb/brisae-kolachi?redirectlocale=en-US&redirectslug=delete-cookies-remove-info-websites-stored
            engleski	https://support.mozilla.org/en-US/kb/clear-cookies-and-site-data-firefox?redirectlocale=en-US&redirectslug=delete-cookies-remove-info-websites-stored
           <br> Internet Explorer	Sprečavanje instaliranja i brisanje postojećih “cookie-ja”	srpski	https://support.microsoft.com/sr-latn-rs/help/17442/windows-internet-explorer-delete-manage-cookies
            engleski	https://support.microsoft.com/en-gb/help/17442/windows-internet-explorer-delete-manage-cookies
           <br> Safari	Sprečavanje instaliranja i brisanje postojećih “cookie-ja”	engleski	https://help.apple.com/safari/mac/8.0/#/sfri11471

           <br><br> VI PROFILISANJE

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 35.</p>

            Profilisanje je oblik automatizovane obrade podataka koji se koristi da bi se ocenilo određeno svojstvo ličnosti, posebno u cilju analize ili predviđanja ličnih sklonosti, interesa, ponašanja i dr. Profilisanje putem “cookie-ja” (cookie profiling ili web profiling) podrazumeva upotrebu trajnih “cookie-ja” za praćenje online aktivnosti korisnika. Na osnovu tako dobijenih podataka možemo profilisati posetioce internet stranice i/ili korisnike naših proizvoda, kako bismo na osnovu toga prilagođavali sadržaj i komunikaciju prema njima.

            <br><br> VII PODACI KOJE N SPORT NE OBRAĐUJE

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 36.</p>

            N Sport ne prikuplja i ne obrađuje posebne vrste podataka o ličnosti, kao što su podaci koji se odnose na rasno ili etničko poreklo, političko mišljenje, versko ili filozofsko uverenje ili članstvo u sindikatu, genetski podaci, biometrijski podaci u cilju jedinstvene identifikacije lica, podaci o zdravstvenom stanju ili podaci o seksualnom životu ili seksualnoj orijentaciji, kao ni podatke koji se odnose na krivične presude, kažnjiva dela i mere bezbednosti.

            Molimo Vas da nam ne otkrivate i ne šaljete ove podatke.

           <br><br> VIII POSTOJANJE OBAVEZE DAVANJA PODATAKA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 37.</p>

            U najvećem broju slučajeva, niste dužni da nam dostavite Vaše podatke već to činite na dobrovoljnoj osnovi. To će, na primer, biti slučaj kada nas kontaktirate i dostavite Vaše kontakt podatke kako bismo mogli da odgovorimo na Vaše pitanje ili zahtev ili kada koristite našu internet stranicu.

            U nekim slučajevima može postojata obaveza dostavljanja Vaših podataka. Na primer, u vezi sa elektronskom prodajom naših proizvoda, biće potrebno da nam dostavite Vaše podatke u cilju izvršenja ugovora ili prilikom izjavljivanja reklamacije kako bismo mogli da je obradimo u skladu sa zakonom.

            <br><br> IX PRIMAOCI PODATAKA O LIČNOSTI

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 38.</p>

            Vaše podatke obrađuje i koristi prevashodno N Sport.

            U nekim slučajevima može biti potrebno da Vaše podatke učinimo dostupnim trećim stranama. To mogu biti članice grupacije kompanija kojoj N Sport pripada, a koje nam pomažu da odgovorimo na Vaša pitanja ili zahteve ili naši poslovni partneri koji nam pomažu da održimo funkcionalnost internet stranice ili nam pružaju analitičke i druge usluge. Da bismo obezbedili da se sa Vašim podacima postupa na zakonit način, sa trećim stranama smo zaključili odgovarajuće ugovore koji se odnose na obradu podataka o ličnosti kojima smo, između ostalog, predvideli njihovu obavezu da sa podacima postupaju isključivo u skladu sa našim instrukcijama i ovim Pravilnikom, tako da Vama, bez obzira na takvu obradu pripadaju prava ustanovljena Zakonom i ovim Pravilnikom.

            Takođe, u određenim pravnim situacijama može postojati zakonska obaveza da Vaše podatke učinimo dostupnim nadležnim organima (npr. sudu ili tužilaštvu i slično).

            <br><br> X  IZNOŠENJE PODATAKA U INOSTRANSTVO

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 39.</p>

            Vaše podatke koristimo na teritoriji Republike Srbije.

            Pored toga, podatke koje prikupljamo čuvamo na serverima koji se nalaze u Republici Srbiji, a koji su u svojini i pod kontrolom domaće hosting kompanije.U skladu sa politikom hosting kompanije, obezbeđen je visok stepen tehničke i softverske zaštite ovih servera, dok pristup samim podacima koji su na njima pohranjeni nije dozvoljen.

            U slučaju potrebe da Vaše podatke učinimo dostupnim primaocima u državama za koje se smatra da ne obezbeđuju primereni nivo zaštite podataka o ličnosti, obezbedićemo odgovarajući osnov za takav prenos, uključujući moguću primenu odgovarajućih standardnih ugovornih klauzula izrađenim od strane nadležnog organa nadzora, odnosno drugih odgovarajućih mera zaštite, kojima će primalac biti obavezan da zaštiti Vaše podatke na način koji je u skladu sa standardima zaštite utvrđenim propisima Republike Srbije.

            <br><br>  XI PERIOD ČUVANJA PODATAKA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 40.</p>

            Vaše podatke čuvamo samo onoliko koliko je neophodno za ostvarenje svrhe za koju su ti podaci prikupljeni, a nakon toga ćemo ih bezbedno ukloniti iz naših sistema i obrisati.

            Vaše podatke na koje se odnosi ovaj Pravilnik po pravilu ćemo čuvati dve godine osim kada nas propisi obavezuju da podatke čuvamo duži ili kraći vremenski period ili kada su u pitanju “cookie” (više informacija o “cookie”ma naći ćete u posebnom odeljku ovog Pravilnika).

            <br><br> XII VAŠA PRAVA U VEZI SA OBRADOM PODATAKA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 41.</p>

            Važno nam je da znate da Vi pod određenim uslovima imate prava u vezi sa obradom Vaših podataka koja su garantovana zakonom:

            Pravo na pristup – imate pravo da zahtevate informaciju o tome da li obrađujemo Vaše podatke o ličnosti, kao i pristup tim podacima. Na Vaš zahtev dostavićemo Vam kopiju Vaših podataka koje obrađujemo.

            Pravo na ispravku i dopunu – imate pravo da se netačni podaci koji se odnose na Vas isprave bez nepotrebnog odlaganja, kao i da se nepotpuni podaci dopune, što uključuje i davanje dodatne izjave.

            Pravo na brisanje podataka – imate pravo da zahtevate brisanje Vaših podataka, a naročito: <br>
            (a)    ako podaci više nisu neophodni za ostvarivanje svrhe zbog koje su prikupljeni ili na drugi način obrađivani; <br>
            (b)    ako ste opozvali pristanak na osnovu kojeg se obrada vršila, a nema drugog pravnog osnova za obradu; <br>
            (c)    ako ste podneli prigovor na obradu u skladu sa zakonom. <br>

            Pravo na ograničenje obrade – imate pravo da se obrada Vaših podataka ograniči ako je ispunjen jedan od sledećih slučajeva: <br>

            (a)    ako osporavate tačnost podataka, u roku koji nam omogućava proveru tačnosti podataka o ličnosti;<br>
            (b)    ako je obrada nezakonita, a Vi se protivite brisanju podataka o ličnosti i umesto brisanja zahtevate ograničenje upotrebe podataka; <br>
            (c)    ako nam više nisu potrebni podaci o ličnosti za ostvarivanje svrhe obrade, ali ste ih zatražili u cilju podnošenja, ostvarivanja ili odbrane pravnog zahteva; <br>
            (d)    ako ste podneli prigovor na obradu koja se vrši na osnovu legitimnog interesa rukovaoca, a u toku je procenjivanje da li pravni osnov za obradu od strane N Sport preteže nad Vašim interesima. <br>

            Pravo na prigovor – ako smatrate da je to opravdano u odnosu na posebnu situaciju u kojoj se nalazite, imate pravo da u svakom trenutku podnesete prigovor na obradu Vaših podataka o ličnosti koja se vrši na osnovu legitimnog interesa rukovaoca, uključujući i profilisanje koje se zasniva na tim odredbama.

            Pravo na prenosivost podataka – imate pravo da podatke o ličnosti koje ste nam prethodno dostavili dobijete od nas u strukturisanom, uobičajeno korišćenom i elektronski čitljivom obliku i pravo da ove podatke prenesete drugom rukovaocu bez ometanja, ako je obrada zasnovana na pristanku ili ugovoru i ako se vrši automatizovano. Takođe, imate pravo da ovi podaci budu neposredno preneti drugom rukovaocu, ako je to tehnički izvodljivo.

            Pravo na opoziv pristanka – ako se obrada vrši na osnovu Vašeg pristanka, imate pravo na opoziv pristanka u bilo koje vreme, pri čemu opoziv pristanka ne utiče na dopuštenost obrade na osnovu pristanka pre opoziva.
            Pravo na podnošenje pritužbe – imate pravo da podnesete pritužbu povodom obrade Vaših podataka o ličnosti Povereniku za informacije od javnog značaja i zaštitu podataka o ličnosti čiji su kontakt podaci:

            Poverenik za informacije od javnog značaja i zaštitu podataka o ličnosti <br>
            Bulevar kralja Aleksandra 15, Beograd 11120 <br>
            Radno vreme: od ponedeljka do petka od 7:30h do 15:30h <br>
            Tel: +381 11 3408 900 <br>
            Faks: +381 11 3343 379 <br>
            Email: office@poverenik.rs <br>
            Sajt: www.poverenik.rs

            <br><br>XIII BEZBEDNOST VAŠIH PODATAKA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 42.</p>

            Bezbednost Vaših podataka nam je veoma važna. Zbog toga N Sport preduzima mere fizičke, tehničke i elektronske zaštite kako bi sprečilo slučajno ili nezakonito uništenje, gubitak, izmene, neovlašćeno otkrivanje ili pristup podacima o ličnosti. Ove mere su usmerene na lica kako van naše organizacije tako i unutar nje jer je pristup Vašim podacima ograničen samo na one osobe čija zaduženja nužno zahtevaju takav pristup i koje su poučene o zaštiti podataka o ličnosti.

            Nažalost ne postoje neprobojni sigurnosni sistemi, pa tako ne možemo da garantujemo da bezbednost sistema nad kojima imamo neposrednu kontrolu nikada neće biti ugrožena. U slučaju povrede podataka o ličnosti preduzećemo sve raspoložive mere i o tome obavestiti nadležne organe u skladu sa propisima, kao i pojedince o čijim podacima je reč, ukoliko je to moguće.

            <br><br> X POVERLJIVOST PODATAKA PRAVNIH LICA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 43.</p>

            Podaci o pravnim licima koje prikuplja N Sport d.o.o. jesu podaci koji se inače prikupljaju u svrhu poslovanja među pravnim licima, a zarad stupanja u poslovni odnos.
                                        
            <br><br><p class='col-12 p-0 font-weight-bold'>Član 44.</p>

            Ugovorne strane su saglasne da sve pribavljene podatke koje je druga ugovorna strana – oravno lice, označila kao poverljive, u vezi sa zaključivanjem ili realizacijom korisničkog odnosa, čuvaju kao poverljive tokom trajanja korisničkog odnosa, kao i u roku od dve godine nakon njegovog prestanka.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 45.</p>

            N Sport prikuplja podatke o korisniku koji je pravno lice, potrebne za zasnivanje korisničkog odnosa, pružanje kvalitetne usluge i obezbeđivanje pravovremenog informisanja, u skladu sa dobrim poslovnim običajima.

            N Sport neće upotrebljavati poslovne podatke korisnika pravnog lica u marketinške ili bilo koje druge svrhe, bez saglasnosti korisnika.

            <br><br> XI ZAŠTITA AUTORSKOG PRAVA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 46.</p>

            N Sport d.o.o. ima isključivo autorsko pravo na N Sport Internet servisu i na svim pojedinačnim elementima koji je čine, kao što su tekst, vizuelni i audio elementi, vizuelni identitet, korisnički interfejs, kod, podaci i baze podataka, programski kod i drugi elementi servisa.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 47.</p>

            Neovlašćeno korišćenje bilo kog dela ili Internet servisa u celini, bez izričite prethodne dozvole u pisanoj formi izdate od N Sport d.o.o. kao nosioca isključivog autorskog prava, smatraće se povredom autorskog prava N Sport d.o.o. i podložno je pokretanju svih postupaka u punoj zakonskoj meri.

            <br><br> XII PRIVREMENA I TRAJNA SUSPENZIJA PRUŽANJA USLUGA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 48.</p>

            U slučaju da korisnik blagovremeno ne izvrši uplatu naknade za korišćenje usluga, N Sport zadržava pravo da mu bez prethodnog obaveštenja privremeno suspenduje pružanje usluga u trajanju do deset kalendarskih dana.
            Ukoliko korisnik tokom perioda suspenzije izvrši uplatu, N Sport će mu omogućiti ponovno korišćenje usluga.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 49.</p>

            U slučaju da korisnik do isteka perioda suspenzije ne izvrši uplatu, N Sport će korisniku trajno suspendovati pružanje usluga, raskinuti korisnički odnos i pokrenuti odgovarajući sudski postupak.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 50.</p>

            N Sport može privremeno suspendovati pružanje usluga korisniku ukoliko postoji osnovana sumnja da postoji bilo kakva nedopuštena ili prevarna radnja koja može naneti štetu N Sportu, korisniku ili trećem licu.

            N Sport je dužan da odmah po nastupanju slučaja iz prethodnog stava obavesti korisnika o suspenziji pružanja usluga i razloga zbog kojih je do toga došlo.

            Suspenzija pružanja usluga u slučaju iz stava 1. ovog člana ne može trajati duže od deset dana.

            Ukoliko nakon upozorenja korisnik nastavi sa nedozvoljenim ponašanjem, N Sport ima pravo da mu trajno suspenduje pružanje usluga, raskine korisnički odnos i pokrene odgovarajući sudski postupak.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 51.</p>

            N Sport može privremeno suspendovati pružanje usluga korisniku radi otklanjanja tehničkih kvarova ili obavljanja drugih neophodnih radova na svojoj infrastrukturi.

            N Sport će blagovremeno obavestiti korisnika o terminima isključenja radi planiranih radova na infrastrukturi.

            <br><br>  XIII ODGOVORNOST I OGRANIČENJA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 52.</p>

            Korisnik samostalno odgovara za svoje postupke prilikom korišćenja Interneta, N Sport Internet servisa, kao i za sadržaj svoje komunikacije sa trećim licima i sadržaje koje učini javno dostupnim putem Interneta uključujući i N Sport Internet servis.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 53.</p>

            N Sport ne odgovara za štetu koju korisnik ili treće lice pretrpi usled:

            - Smanjenog propusnog opsega, otežanog prenosa ili povremenih privremenih prekida u pružanju usluga, koji su uzrokovani interferencijom, atmosferskim prilikama, fizičkim preprekama i uopšte razlozima koji uzrokuju varijabilnost kojoj je podložna tehnologija žičnog ili bežičnog povezivanja;
            - Preopterećenja, kašnjenja ili grešaka u funkcionisanju delova Interneta na koje N Sport objektivno ne može da utiče;
            - Neovlašćenog korišćenja Internet usluga sa strane korisnika;
            - Vršenja svih vrsta finansijskih i ostalih poslovnih transakcija od strane korisnika na Internetu;
            - Privremene ili trajne suspenzije pružanja Internet usluga propisanih odredbama ovih Opštih uslova ili zakonom;
            - Korišćenja nestandardne opreme od strane korisnika;
            - Preseljenja korisnika na drugu lokaciju sa koje nije moguć pristup uslugama N Sporta;
            - Dejstva više sile ili drugih uzroka koji su van kontrole N Sporta;
            - Trajnog prestanka obavljanja delatnosti pružanja Internet usluga N Sporta.

            <br><br> XIV PRESTANAK KORISNIČKOG ODNOSA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 54.</p>

            Korisnički odnos između N Sporta i korisnika može prestati na osnovu jednostranog otkaza svake strane.

            N Sport može jednostrano i bez otkaznog roka raskinuti odnos sa korisnikom u sledećim slučajevima:

            - Ako korisnik ne izmiri svoja dugovanja do isteka perioda privremene suspenzije iz ovih Opštih uslova;
            - Ako korisnik nastavi sa nedozvoljenim ponašanjem nakon privremene suspenzije i upozorenja N Sporta;
            - Ako dođe do smrti ili gubitka poslovne sposobnosti korisnika koji je fizičko lice ili pokretanja stečajnog, likvidacionog ili drugog postupka koji može dovesti do prestanka postojanja korisnika koji je pravno lice;
            - Ako N Sport donese poslovnu odluku da prestane sa pružanjem Internet usluga ili prestane sa obavljanjem svoje delatnosti.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 55.</p>

            Korisnik može jednostrano raskinuti korisnički odnos sa N Sportom ukoliko nije saglasan sa promenama ovih Opštih uslova. Zahtev za raskidanje korisničkog odnosa po ovom osnovu korisnik je dužan da dostavi N Sportu u roku od osam dana od dana prijema obaveštenja o promeni Opštih uslova.

            Korisnik je dužan da u slučaju prestanka korisničkog odnosa, bez obzira na osnov prestanka, odmah, a najkasnije u roku od 8 dana, izmiri sva dugovanja prema N Sportu nastala po osnovu pruženih usluga.

            <br><br> XV REŠAVANJE SPOROVA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 56.</p>

            Korisnik i N Sport su saglasni da se odredbe ovih Opštih uslova imaju tumačiti na način koji doprinosi izvršenju ugovornih obaveza na obostranu korist.

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 57.</p>

            Korisnik i N Sport su saglasni da sve eventualne nesporazume koji nastanu tokom trajanja korisničkog odnosa pokušaju da reše mirnim putem. Ukoliko u tome ne uspeju, nadležan će biti sud u Beogradu, odnosno u mestu prebivališta, odnosno boravišta potrošača u Republici Srbiji, uz primenu prava Republike Srbije.

            <br><br> XVI IZMENE OPŠTIH USLOVA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 58.</p>

            N Sport zadržava pravo da vrši izmene i dopune ovih Opštih uslova u skladu sa izmenjenim uslovima poslovanja i u skladu sa svojom poslovnom politikom.

            N Sport se obavezuje da korisnika na pogodan način obavesti o izmenama ovih Opštih uslova, osam dana pre stupanja izmena na snagu.

            <br><br> XVII VAŽENJE OPŠTIH USLOVA

            <br><br><p class='col-12 p-0 font-weight-bold'>Član 59.</p>

            Ovi Opšti uslovi stupaju na snagu osmog dana od dana objavljivanja i primenjivaće se od 01.11.2019. godine.

            Stupanjem na snagu ovih Opštih uslova, prestaju da važe Opšti uslovi od 25.04.2018. godine.
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
        })
    </script>
</body>
</html>