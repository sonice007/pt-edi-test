<?php
    //MyReport.view.php
    use \koolreport\widgets\koolphp\Table;

?>
<html>
    <head>
        <title>MyReport</title></title>
    </head>
    <body>
        <h1>MyReport</h1>
        <h3>List all Partner</h3>
        <form action="<?=base_url()?>welcome" method="POST">
            <div class="row">
                <div class="col-md-6">
                </div>
                <div class="col-md-1" style="text-align: right;">Filter</div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="filter_id" value="<?php if(isset($_POST['filter_id'])) if($_POST['filter_id'] != null) echo $_POST['filter_id'];?>" placeholder="Masukan ID">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="filter_status" id="filter_status">
                        <?php if(isset($_POST['filter_status'])):?>
                            <option value="1" <?php if($_POST['filter_status'] == 1) echo "selected";?>>Aktif</option>
                            <option value="3" <?php if($_POST['filter_status'] == 3) echo "selected";?>>Tidak Aktif</option>
                        <?php else:?>
                            <option value="1">Aktif</option>
                            <option value="3">Tidak Aktif</option>
                        <?php endif;?>
                    </select>
                </div>
                <div class="col-md-1" style="text-align: left;">
                    <input type="submit" class="btn btn-default" value="Cari">
                </div>
            </div>
        </form>
        <?php
            Table::create(array(
                "dataStore"=>$this->dataStore("partner"),
                "class"=>array(
                    "table"=>"table table-hover"
                )
            ));
        ?>
    </body>
</html>