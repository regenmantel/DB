<div class="container p-4">
    <form method="POST">
        <div class="row">
            <div class="col-md-3 col-xs-6">
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">AG Limit</span>
                    <input type="text" name="agLimit" class="form-control" placeholder="10" <?php if (isset($_POST["agLimit"])) {
                                                                                                echo "value='" . htmlspecialchars($_POST["agLimit"]) . "'";
                                                                                            } ?> aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Gewünschtes AG-Limit</span>
                    <input type="text" name="gAgLimit" class="form-control" placeholder="20" <?php if (isset($_POST["gAgLimit"])) {
                                                                                                    echo "value='" . htmlspecialchars($_POST["gAgLimit"]) . "'";
                                                                                                } ?> aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
            </div>

            <div class="col-md-3 col-xs-6">
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="input-group input-group-sm mb-3">
                    <label class="input-group-text" for="inputGroupSelect01">Münzflagge</label>
                    <select name="flag" class="form-select" id="inputGroupSelect01">
                        <?php if (isset($_POST["flag"])) {
                            echo "<option value='" . htmlspecialchars($_POST["flag"]) . "'>" . htmlspecialchars($_POST["flag"]) . "%</option>";
                        } ?>
                        <option value="0">0%</option>
                        <option value="24">24%</option>
                        <option value="23">23%</option>
                        <option value="22">22%</option>
                        <option value="20">20%</option>
                        <option value="18">18%</option>
                        <option value="16">16%</option>
                        <option value="14">14%</option>
                        <option value="12">12%</option>
                        <option value="10">10%</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-xs-6">
            </div>

            <div class="col-md-3 col-xs-6">
            </div>
            <div class="col-md-3 col-xs-6">
            </div>
            <div class="col-md-3 col-xs-6">
                <div class="input-group input-group-sm mb-3">
                    <label class="input-group-text" for="inputGroupSelect01">Basispreis</label>
                    <select name="basicPrice" class="form-select" id="inputGroupSelect01">
                        <?php if (isset($_POST["basicPrice"])) {
                            echo "<option value='" . htmlspecialchars($_POST["basicPrice"]) . "'>" . htmlspecialchars($_POST["basicPrice"]) . "%</option>";
                        } ?>
                        <option value="100">100%</option>
                        <option value="90">90%</option>
                        <option value="80">80%</option>
                        <option value="70">70%</option>
                        <option value="60">60%</option>
                        <option value="50">50%</option>
                        <option value="40">40%</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="flagBooster" <?php if (isset($_POST["flagBooster"])) {
                                                                                            echo "checked";
                                                                                        } ?>>
                    <label class="form-check-label" for="flexCheckDefault">
                        Flaggenbooster
                    </label>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="erlass" <?php if (isset($_POST["erlass"])) {
                                                                                        echo "checked";
                                                                                    } ?>>
                    <label class="form-check-label" for="flexCheckDefault">
                        Erlass des Adels (-10%)
                    </label>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
            </div>
            <div class="col-4">
            </div>
            <div class="col-4 d-flex justify-content-center">
                <button class="mt-2 btn btn-primary" type="submit">Berechnen</button>
            </div>
            <div class="col-4">
            </div>
            <div class="col-12 text-center mt-4">
                <?php
                if (isset($_POST["agLimit"]) && isset($_POST["gAgLimit"])) {
                    $agLimit = intval($_POST["agLimit"]);
                    $gAgLimit = intval($_POST["gAgLimit"]);
                    $flag = intval($_POST["flag"]);
                    $basicPrice = intval($_POST["basicPrice"]);

                    if ($flag != 0 and isset($_POST["flagBooster"]) == "on") {
                        $flag = (100 - ($flag * 2)) / 100;
                    } elseif ($flag != 0) {
                        $flag = (100 - $flag) / 100;
                    } else {
                        $flag = 1;
                    }
                    if (isset($_POST["erlass"]) == "on") {
                        $reduzierung = ($flag - 0.1);
                    } elseif ($flag != 1) {
                        $reduzierung = $flag;
                    } else {
                        $reduzierung = 1;
                    }

                    $ergebnis = ($gAgLimit - $agLimit) * (($agLimit + $gAgLimit) + 1) / 2;
                    $holz = $ergebnis * 28000 * ($basicPrice / 100) * $reduzierung;
                    $holz = number_format($holz, 0, ',', '.');
                    $stone = $ergebnis * 30000 * ($basicPrice / 100) * $reduzierung;
                    $stone = number_format($stone, 0, ',', '.');
                    $iron = $ergebnis * 25000 * ($basicPrice / 100) * $reduzierung;
                    $iron = number_format($iron, 0, ',', '.');
                    $ergebnis = number_format($ergebnis);

                    echo "
                    Du benötigst für ein <img src='assets/images/inno/units/snob.png' alt='Münzen' width='20px' height='20px'/> Limit von $agLimit  auf $gAgLimit AGs genau $ergebnis Goldmünzen <img src='assets/images/inno/material/gold_big.png' alt='Münzen' width='20px' height='20px'/>
                    <br><br>
                    Rohstoffe die gebraucht werden
                    <br> <img src='assets/images/inno/material/wood.png' alt='Holz' width='20px' height='20px'/> = $holz -
                    <img src='assets/images/inno/material/stone.png' alt='Lehm' width='20px' height='20px'/> = $stone -
                    <img src='assets/images/inno/material/iron.png' alt='Eisen' width='20px' height='20px'/> = $iron";
                }
                ?>
            </div>
        </div>
    </form>
</div>
<div class="placeholder">
    <br></br>
</div>