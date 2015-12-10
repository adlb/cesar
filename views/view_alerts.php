<?php if (count($obj['alerts'])>0) { ?>
    
    <div class="row" style="margin-bottom:20px">
        <div class="col-md-3 col-md-offset-9 bg-warning" style="border: 3px solid red;height:45px;text-align:center;color:#F00;font-weight:600;font-size:2em;">
            <a href="" onclick="$('#modal-alerts').modal('show');" style="text-decoration:underline;color:#F00;">
                <?php t(':URGENT_MESSAGE')?>
            </a>&nbsp;&nbsp;
            <i class="fa fa-exclamation-triangle"></i>
        </div>
    </div>
    <!--<button type="button" class="btn btn-danger" style="width:100%;margin-bottom:10px" onclick="$('#modal-alerts').modal('show');"><?php t(':URGENT_MESSAGE')?></button>
    -->
<?php } ?>