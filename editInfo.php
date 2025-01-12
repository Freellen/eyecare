<?php
require_once'php/core/init.php';
$user = new User();
$override = new OverideData();
$pageError = null;$successMessage = null;$errorM = false;$errorMessage = null;$accessLevel=0;
$total_orders=0;$pending=0;$confirmed=0;$received=0;$other_test=null;
$info = $override->get('checkup_record','id',$_GET['p']);
$patientInfo = $override->get('patient','id',$info[0]['patient_id']);
if($user->isLoggedIn()){
    if($user->data()->access_level == 2){
        if(Input::exists('post')){
            if(Input::get('medRec')){
                $validate = new validate();
                $validate = $validate->check($_POST, array(
                    'patient_name' => array(
                        'required' =>true,
                    ),
                ));
                if ($validate->passed()) {
                   // if(Input::get('other_test')){$other_test=Input::get('other_test');}else{$other_test=$info[0]['other_test'];}
                    try {
                        $user->updateRecord('checkup_record', array(
                            'CC' => Input::get('cc'),
                            'OH' => Input::get('oh'),
                            'GH' => Input::get('gh'),
                            'FOH' => Input::get('foh'),
                            'FGH' => Input::get('fgh'),
                            'NPC' => Input::get('nfc'),
                            'EOM' => Input::get('eom'),
                            'pupils' => Input::get('pupils'),
                            'confrontation' => Input::get('confrontation'),
                            'vision' => '',
                            'V_RE' => Input::get('v_re'),
                            'V_LE' => Input::get('v_le'),
                            'PH_RE' => Input::get('ph_re'),
                            'PH_LE' => Input::get('ph_le'),
                            'UN_RE' => Input::get('un_re'),
                            'UN_LE' => Input::get('un_le'),
                            'PD' => Input::get('pd'),
                            'PH' => '',
                            'ref_OD_sphere' => Input::get('ref_od_sphere'),
                            'ref_cyl' => Input::get('ref_cyl'),
                            'ref_axis' => Input::get('ref_axis'),
                            'ref_va' => Input::get('ref_va'),
                            'ref_add' => Input::get('ref_add'),
                            'add_ref_OD_sphere' => Input::get('add_ref_od_sphere'),
                            'add_ref_cyl' => Input::get('add_ref_cyl'),
                            'add_ref_axis' => Input::get('add_ref_axis'),
                            'add_ref_va' => Input::get('add_ref_va'),
                            'add_ref_add' => Input::get('add_ref_add'),
                            'rx_OD_sphere' => Input::get('rx_od_sphere'),
                            'rx_cyl' => Input::get('rx_cyl'),
                            'rx_axis' => Input::get('rx_axis'),
                            'rx_va' => Input::get('rx_va'),
                            'rx_add' => Input::get('rx_add'),
                            'rx_va_2' => Input::get('rx_va_2'),
                            'add_rx_OS_sphere' => Input::get('add_rx_os_sphere'),
                            'add_rx_cyl' => Input::get('add_rx_cyl'),
                            'add_rx_axis' => Input::get('add_rx_axis'),
                            'add_rx_va' => Input::get('add_rx_va'),
                            'add_rx_add' => Input::get('add_rx_add'),
                            'add_rx_va_2' => Input::get('add_rx_va_2'),
                            'external_ocular_exam' => Input::get('ext_oc_exam'),
                            'IOP' => Input::get('iop'),
                            'IOP_RE' => Input::get('iop_re'),
                            'IOP_LE' => Input::get('iop_le'),
                            'IOP_time' => Input::get('iop_time'),
                            'IOP_POST_IOP' => '',
                            'IOP_POST_dilation' => Input::get('iop_post_dilation'),
                            'IOP_POST_RE' => Input::get('iop_post_re'),
                            'IOP_POST_LE' => Input::get('iop_post_le'),
                            'IOP_POST_time' => Input::get('iop_post_time'),
                            'mydriatic_agent_used' => Input::get('mydriatic_agent_used'),
                            'internal_exam' => Input::get('internal_exam'),
                            'f_od' => Input::get('f_od'),
                            'f_os' => Input::get('f_os'),
                            'f_vessels' => Input::get('f_vessels'),
                            'f_macula' => Input::get('f_macula'),
                            'f_retina' => Input::get('f_retina'),
                            'diagnosis' => '',
                            'management' => '',
                            'distance_glasses' => Input::get('distance_glasses'),
                            'reading_glasses' => Input::get('reading_glasses'),
                            'lens' => Input::get('lens'),
                            'other_note' => Input::get('other_note'),
                            'other_test' => '',
                            'patient_id' => Input::get('patient_name'),
                            'doctor_id' => $user->data()->id,
                            'branch_id' => $user->data()->branch_id
                        ),$_GET['p']);
                        //get check up ID
                        $checkup_id =$override->getNews('checkup_record','patient_id',Input::get('patient_name'),'checkup_date',date('Y-m-d'));

                        $getMedicine = array(Input::get('medicine'),Input::get('other_medicine'),Input::get('other_medicine_1'),Input::get('other_medicine_2'));
                        $quantity = array(Input::get('quantity'),Input::get('other_quantity'),Input::get('other_quantity_1'),Input::get('other_quantity_2'));
                        $dosage = array(Input::get('dosage'),Input::get('other_dosage'),Input::get('other_dosage_1'),Input::get('other_dosage_2'));
                        $eyes = array(Input::get('eyes'),Input::get('other_eyes'),Input::get('other_eyes_1'),Input::get('other_eyes_2'));
                        $day= array(Input::get('day'),Input::get('other_day'),Input::get('other_day_1'),Input::get('other_day_2'));
                        $days= array(Input::get('days'),Input::get('other_days'),Input::get('other_days_1'),Input::get('other_days_2'));$f=0;
                        foreach($getMedicine as $getMed){
                            if($getMed == null){

                            }else{
                                $user->createRecord('prescription',array(
                                    'medicine_id' => $getMed,
                                    'quantity' => $quantity[$f],
                                    'dosage' => $dosage[$f],
                                    'eyes' => $eyes[$f],
                                    'no_day' => $day[$f],
                                    'days_group' => $days[$f],
                                    'given_date' => date('Y-m-d'),
                                    'patient_id' => Input::get('patient_name'),
                                    'doctor_id' => $user->data()->id,
                                    'branch_id' => $user->data()->branch_id,
                                    'checkup_id' => $checkup_id[0]['id']
                                ));
                            }$f++;
                        }

                        $p=$override->get('patient','id',Input::get('patient_name'));
                        if(Input::get('other_test')){
                            foreach(Input::get('other_test') as $test){
                                $user->createRecord('test_performed',array(
                                    'test_id' => $test,
                                    'date_performed'=>date('Y-m-d'),
                                    'patient_id' => Input::get('patient_name'),
                                    'doctor_id' => $user->data()->id,
                                    'branch_id' => $user->data()->branch_id
                                ));
                                $testPerformed = $override->get('test_list','id',$test);
                                if($p[0]['health_insurance']){
                                    $totalTest +=$testPerformed[0]['insurance_price'];
                                }else{
                                    $totalTest +=$testPerformed[0]['cost'];
                                }
                            }
                        }
                        $successMessage = 'Patient Information Successful Saved';
                        $redirect = 'editInfo.php?id='.$_GET['id'].'&p='.$_GET['p'].'&n='.$successMessage;
                        Redirect::to($redirect);

                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $pageError = $validate->errors();
                }
            }
        }
    }else{Redirect::to('index.php');}
}else{Redirect::to('index.php');}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- META SECTION -->
    <title> Eye Clinic | Edit Info </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
    <!-- EOF CSS INCLUDE -->
</head>
<body class="x-dashboard">
<!-- START PAGE CONTAINER -->
<div class="page-container">
<!-- PAGE CONTENT -->
<div class="page-content">
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

<?php include 'menuBar.php'?>
<div class="x-content">
<div id="main-tab">
<div class="x-content-title">
    <h1>Edit Information</h1>

    <div class="pull-right">
        <a href="editInfo.php?id=<?=$_GET['id']?>&p=<?=$_GET['p']?>&n=" class="btn btn-default">REFRESH </a>
        <button class="btn btn-default">TODAY: <?=date('d-M-Y')?></button>
    </div>
</div>
<div class="row stacked">
<div class="col-md-12">
<div class="x-chart-widget">

<div class="x-chart-widget-content">
<div class="x-chart-widget-content-head">
    <!--<h4>PATIENT ON QUEUE : <?=$override->getNo('wait_list')?></h4>-->
</div>

<div class="col-md-offset-2 col-md-8">
<div class="panel panel-default">
<?php if($successMessage || $_GET['n']){?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>Well done!&nbsp;</strong> <?=$successMessage.''.$_GET['n']?>
    </div>
<?php }elseif($errorMessage){?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>Oops Error!&nbsp;</strong> <?=$errorMessage?>
    </div>
<?php }elseif($pageError){?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <strong>Oops Error!&nbsp;</strong> <?php foreach($pageError as $error){echo $error.' , ';}?>
    </div>
<?php }?><br>
<div class="panel-body">
<?php if($_GET['id'] == 0){?>
    <h3>Edit Examination Details for <span style="color: green"><?=$patientInfo[0]['firstname'].' '.$patientInfo[0]['lastname']?></span> performed on <span style="color: green"><?=$info[0]['checkup_date'].' '?></span><span class="pull-right">PID : <span style="color: green"><?=$info[0]['patient_id']?></span></span></h3>
    <h3>&nbsp;</h3>
    <form role="form" class="form-horizontal" method="post">
    <div class="form-group">
        <label class="col-md-1 control-label">Patient:&nbsp;&nbsp;</label>
        <div class="col-md-11">
            <select name="patient_name" class="form-control select" data-live-search="true">
                <option value="<?=$patientInfo[0]['id']?>"><?=$patientInfo[0]['firstname'].' '.$patientInfo[0]['lastname'].' '.$patientInfo[0]['phone_number']?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1 control-label">CC : &nbsp;</label>
        <div class="col-md-11">
            <textarea name="cc" class="form-control" rows="5"><?=$info[0]['CC']?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="oh" type="text" class="form-control" placeholder="OH: " value="<?=$info[0]['OH']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="gh" type="text" class="form-control" placeholder="GH: " value="<?=$info[0]['GH']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="foh" type="text" class="form-control" placeholder="FOH: " value="<?=$info[0]['FOH']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="fgh" type="text" class="form-control" placeholder="FGH: " value="<?=$info[0]['FGH']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="nfc" type="text" class="form-control" placeholder="NPC: " value="<?=$info[0]['NPC']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="eom" type="text" class="form-control" placeholder="EOM: " value="<?=$info[0]['EOM']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="pupils" type="text" class="form-control" placeholder="Pupils: " value="<?=$info[0]['pupils']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="confrontation" type="text" class="form-control" placeholder="Confrontation: " value="<?=$info[0]['confrontation']?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <label></label>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Right</th>
                        <th>Left</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>DISTANCE  V/A</strong></td>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>VISION</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>PH</strong></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <td><input name="v_re" type="text" class="form-control" value="<?=$info[0]['V_RE']?>" disabled/></td>
                                </tr>
                                <tr>
                                    <td><input name="v_re" type="text" class="form-control" value="<?=$info[0]['PH_RE']?>" disabled/></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered">
                                <tr>
                                    <td><input name="ph_re" type="text" class="form-control" value="<?=$info[0]['V_LE']?>" disabled/></td>
                                </tr>
                                <tr>
                                    <td><input name="ph_le" type="text" class="form-control" value="<?=$info[0]['PH_LE']?>" disabled/></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>NEAR  V/A</strong></td>
                        <td><strong>UNAIDED  V/A</strong></td>
                        <td><input name="un_re" type="text" class="form-control" value="<?=$info[0]['UN_RE']?>" disabled/></td>
                        <td><input name="un_le" type="text" class="form-control" value="<?=$info[0]['UN_LE']?>" disabled/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1"></label>
        <div class="col-md-4">
            <input name="pd" type="text" class="form-control" placeholder="PD: " value="<?=$info[0]['PD']?>">
        </div>
        <label class="col-md-1"></label>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <textarea name="ext_oc_exam" class="form-control" rows="5" placeholder="External Ocular Examination:"><?=$info[0]['external_ocular_exam']?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <label>Auto Ref</label>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Eye</th>
                        <th>Sph</th>
                        <th>Cyl</th>
                        <th>Axis</th>
                        <th>VA</th>
                        <th>Add</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Right</td>
                        <td><input name="ref_od_sphere" type="text" class="form-control" value="<?=$info[0]['ref_OD_sphere']?>"/></td>
                        <td><input name="ref_cyl" type="text" class="form-control" value="<?=$info[0]['ref_cyl']?>"/></td>
                        <td><input name="ref_axis" type="text" class="form-control" value="<?=$info[0]['ref_axis']?>"/></td>
                        <td><input name="ref_va" type="text" class="form-control" value="<?=$info[0]['ref_va']?>"/></td>
                        <td><input name="ref_add" type="text" class="form-control" value="<?=$info[0]['ref_add']?>"/></td>
                    </tr>
                    <tr>
                        <td>Left</td>
                        <td><input name="add_ref_od_sphere" type="text" class="form-control" value="<?=$info[0]['add_ref_OD_sphere']?>"/></td>
                        <td><input name="add_ref_cyl" type="text" class="form-control" value="<?=$info[0]['add_ref_cyl']?>"/></td>
                        <td><input name="add_ref_axis" type="text" class="form-control" value="<?=$info[0]['add_ref_axis']?>"/></td>
                        <td><input name="add_ref_va" type="text" class="form-control" value="<?=$info[0]['add_ref_va']?>"/></td>
                        <td><input name="add_ref_add" type="text" class="form-control" value="<?=$info[0]['add_ref_add']?>"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <label>RX</label>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Eye</th>
                        <th>Sph</th>
                        <th>Cyl</th>
                        <th>Axis</th>
                        <th>VA</th>
                        <th>Add</th>
                        <th>VA</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Right</td>
                        <td><input name="rx_od_sphere" type="text" class="form-control" value="<?=$info[0]['rx_OD_sphere']?>"/></td>
                        <td><input name="rx_cyl" type="text" class="form-control" value="<?=$info[0]['rx_cyl']?>"/></td>
                        <td><input name="rx_axis" type="text" class="form-control" value="<?=$info[0]['rx_axis']?>"/></td>
                        <td><input name="rx_va" type="text" class="form-control" value="<?=$info[0]['rx_va']?>"/></td>
                        <td><input name="rx_add" type="text" class="form-control" value="<?=$info[0]['rx_add']?>"/></td>
                        <td><input name="rx_va_2" type="text" class="form-control" value="<?=$info[0]['rx_va_2']?>"/></td>
                    </tr>
                    <tr>
                        <td>Left</td>
                        <td><input name="add_rx_os_sphere" type="text" class="form-control" value="<?=$info[0]['add_rx_OS_sphere']?>"/></td>
                        <td><input name="add_rx_cyl" type="text" class="form-control" value="<?=$info[0]['add_rx_cyl']?>"/></td>
                        <td><input name="add_rx_axis" type="text" class="form-control" value="<?=$info[0]['add_rx_axis']?>"/></td>
                        <td><input name="add_rx_va" type="text" class="form-control" value="<?=$info[0]['add_rx_va']?>"/></td>
                        <td><input name="add_rx_add" type="text" class="form-control" value="<?=$info[0]['add_rx_add']?>"/></td>
                        <td><input name="add_rx_va_2" type="text" class="form-control" value="<?=$info[0]['add_rx_va_2']?>"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop" type="text" class="form-control" placeholder="IOP: " value="<?=$info[0]['IOP']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_re" type="text" class="form-control" placeholder="RE: " value="<?=$info[0]['IOP_RE']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_le" type="text" class="form-control" placeholder="LE: " value="<?=$info[0]['IOP_LE']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_time" type="text" class="form-control" placeholder="Time: " value="<?=$info[0]['IOP_time']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_post_dilation" type="text" class="form-control" placeholder="IOP:POST Dilation " value="<?=$info[0]['IOP_POST_dilation']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_post_re" type="text" class="form-control" placeholder="RE: " value="<?=$info[0]['IOP_POST_RE']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_post_le" type="text" class="form-control" placeholder="LE: " value="<?=$info[0]['IOP_POST_LE']?>">
        </div>
        <label class="col-md-1"></label>
        <div class="col-md-2">
            <input name="iop_post_time" type="text" class="form-control" placeholder="Time: " value="<?=$info[0]['IOP_POST_time']?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <input type="text" name="mydriatic_agent_used" class="form-control" placeholder="Mydriatic Agent used:" value="<?=$info[0]['mydriatic_agent_used']?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <textarea name="internal_exam" class="form-control" rows="5" placeholder="Internal Examination Dilated/Undilated:"><?=$info[0]['internal_exam']?></textarea>
        </div>
    </div>
    <hr><h2>FUNDUS</h2>
    <div class="form-group">
        <label class="col-md-1 control-label">CD Ration:&nbsp;&nbsp;</label>
        <div class="col-md-offset-0 col-md-5">
            <input type="text" name="f_od" class="form-control" placeholder="OD:" value="<?=$info[0]['f_od']?>">
        </div>
        <label class="col-md-1 control-label"></label>
        <div class="col-md-offset-0 col-md-5">
            <input type="text" name="f_os" class="form-control" placeholder="OS:" value="<?=$info[0]['f_os']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1 control-label">Vessels:&nbsp;&nbsp;</label>
        <div class="col-md-offset-0 col-md-10">
            <input type="text" name="f_vessels" class="form-control" placeholder="" value="<?=$info[0]['f_vessels']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1 control-label">Macula:&nbsp;&nbsp;</label>
        <div class="col-md-offset-0 col-md-10">
            <input type="text" name="f_macula" class="form-control" placeholder="" value="<?=$info[0]['f_macula']?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-1 control-label">Peripheral retina:&nbsp;&nbsp;</label>
        <div class="col-md-offset-0 col-md-10">
            <textarea name="f_retina" class="form-control" rows="5" placeholder=""><?=$info[0]['f_retina']?></textarea>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-md-offset-1 col-md-10">
            <label style="color: #009900;font-weight: bolder">Previous Diagnosis: <?php foreach($override->get('diagnosis_prescription','checkup_id',$info[0]['id']) as $d){$dg=$override->get('diagnosis','id',$d['diagnosis_id']);echo$dg[0]['name'].' , ';}?></label>
        </div>
        <div class="col-md-offset-1 col-md-10">
            <select name="diagnosis[]" class="form-control select" multiple data-live-search="true" title="Diagnosis: ">
                <?php foreach($override->getData('diagnosis') as $diagnosis){?>
                    <option value="<?=$diagnosis['id']?>"><?=$diagnosis['name']?></option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-1 col-md-2">
            <label class="check"><input name="distance_glasses" type="checkbox" value="Distance Glasses" class="icheckbox"/> Distance Glasses</label>
        </div>
        <div class="col-md-2">
            <label class="check"><input name="reading_glasses" type="checkbox" value="Reading Glasses" class="icheckbox"/> Reading Glasses</label>
        </div>
        <label class="col-md-12"></label><label class="col-md-12"></label>

        <div class="form-group">
            <div class="col-md-offset-1 col-md-10">
                <label style="color: #009900;font-weight: bolder">Test Performed: <?php foreach($override->get('test_performed','checkup_id',$info[0]['id']) as $tst){$t=$override->get('test_list','id',$tst['test_id']);echo$t[0]['name'].' , ';}?></label>
            </div>
            <div class="col-md-offset-1 col-md-10">
                <select name="other_test[]" multiple class="form-control select" data-live-search="true" title="Procedures Performed" >
                    <?php foreach($override->get('test_list','branch_id',$user->data()->branch_id) as $test){?>
                        <option value="<?=$test['id']?>"><?=$test['name']?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-1 col-md-10">
                <textarea name="other_note" class="form-control" rows="5" placeholder="Other Note:"><?=$info[0]['other_note']?></textarea>
            </div>
        </div><hr>
        <h4><strong>Medicine Prescription</strong></h4>
        <div class="form-group">
            <div class="col-md-offset-1 col-md-4">
                <label class="check"><input type="radio" class="" name="get_med" id="single" value="single" checked/> Single</label>
            </div>
            <div class="col-md-4 col-md-offset-0">
                <label class="check"><input type="radio" class="" name="get_med" id="multiple" value="multiple"/> Multiple</label>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-0 col-md-3">
                <select name="medicine" class="form-control select" data-live-search="true">
                    <option value="">Select Medicine</option>
                    <?php foreach($override->getMedicine('medicine','quantity') as $medicine){?>
                        <option value="<?=$medicine['id']?>"><?=$medicine['name']?></option>
                    <?php }?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="quantity" class="form-control select" >
                    <option value="">Quantity</option>
                    <?php $x=1;while($x < 10){?>
                        <option value="<?=$x?>"><?=$x?></option>
                        <?php $x++;}?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="dosage" class="form-control select" >
                    <option value="">FREQUENCY</option>
                    <option value="OD 1 times a day">OD 1 times a day</option>
                    <option value="BID 2 times a day">BID 2 times a day</option>
                    <option value="TID 3 times a day">TID 3 times a day</option>
                    <option value="QID 4times a day">QID 4times a day</option>
                    <option value="1 hourly">1 hourly</option>
                    <option value="2 after two hours">2 after two hours</option>
                    <option value="3 after three hours">3 after three hours</option>
                    <option value="4 after four hours">4 after four hours</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="eyes" class="form-control select" >
                    <option value="BOTH EYES">BOTH EYES</option>
                    <option value="RIGHT EYES">RIGHT EYES</option>
                    <option value="LEFT EYES">LEFT EYES</option>
                    <option value="ORAL">ORAL</option>
                    <option value="TROPICAL APPLICATION">TROPICAL APPLICATION</option>
                    <option value="APPLIED">APPLIED</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="number" name="day" class="form-control" placeholder="No.">
            </div>
            <div class="col-md-2">
                <select name="days" class="form-control select" >
                    <option value="DAY">DAYS</option>
                    <option value="WEEK">WEEK</option>
                    <option value="MONTH">MONTH</option>
                    <option value="YEAR">YEAR</option>
                </select>
            </div>
        </div>
        <div id="waitM" style="display:none;" class="col-md-offset-5 col-md-1"><img src='img/owl/AjaxLoader.gif' width="32" height="32" /><br>Loading..</div>
        <div id="other_med"></div><br><br>
        <div class="pull-right">
            <input type="submit" name="medRec" value="Submit" class="btn btn-success">
        </div>
    </form>
<?php }?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--<div id="second-tab"></div>
<div id="third-tab"></div>
<div id="fourth-tab"></div>-->
</div>
<div class="x-content-footer">
    Copyright © 2018 Family Eye Care. All rights reserved
</div>
</div>
<!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->

<!-- MESSAGE BOX-->
<?php include 'signout.php'?>
<!-- END MESSAGE BOX-->

<!-- START PRELOADS -->
<audio id="audio-alert" src="audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="audio/fail.mp3" preload="auto"></audio>
<!-- END PRELOADS -->

<!-- START SCRIPTS -->
<!-- START PLUGINS -->
<script type="text/javascript" src="js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap.min.js"></script>
<!-- END PLUGINS -->

<!-- THIS PAGE PLUGINS -->
<script type='text/javascript' src='js/plugins/icheck/icheck.min.js'></script>
<script type="text/javascript" src="js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>

<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<!-- END THIS PAGE PLUGINS -->

<!-- START TEMPLATE -->

<script type="text/javascript" src="js/plugins.js"></script>
<script type="text/javascript" src="js/actions.js"></script>
<!-- END TEMPLATE -->

<script>
    $(function(){
        //Spinner
        $(".spinner_default").spinner()
        $(".spinner_decimal").spinner({step: 0.01, numberFormat: "n"});
        //End spinner

        //Datepicker
        $('#dp-2').datepicker();
        $('#dp-3').datepicker({startView: 2});
        $('#dp-4').datepicker({startView: 1});
        //End Datepicker
    });
</script>
<script>
    $(document).ready(function(){
        $('#eye').change(function(){
            var getEye = $(this).val();
            $('#wait').show();
            $.ajax({
                url:"process.php?content=eyes",
                method:"GET",
                data:{getEye:getEye},
                dataType:"text",
                success:function(data){
                    $('#other_eye').html(data);
                    $('#wait').hide();
                }
            });

        });
        $('#multiple').change(function(){
            var getMed = $(this).val();
            $('#waitM').show();
            $.ajax({
                url:"process.php?content=multiple",
                method:"GET",
                data:{getMed:getMed},
                dataType:"text",
                success:function(data){
                    $('#other_med').html(data);
                    $('#waitM').hide();
                }
            });
        });
        $('#single').change(function(){
            var getMed = $(this).val();
            $('#waitM').show();
            $.ajax({
                url:"process.php?content=multiple",
                method:"GET",
                data:{getMed:getMed},
                dataType:"text",
                success:function(data){
                    $('#other_med').html(data);
                    $('#waitM').hide();
                }
            });
        });
        $('#lens_power').change(function(){
            var getCat = $(this).val();
            $.ajax({
                url:"process.php?content=power",
                method:"GET",
                data:{cat_id:getCat},
                dataType:"text",
                success:function(data){
                    $('#p').html(data);
                }
            });
        });
        $('#other_lens_power').change(function(){
            var getCat = $(this).val();
            $.ajax({
                url:"process.php?content=other_power",
                method:"GET",
                data:{cat_id:getCat},
                dataType:"text",
                success:function(data){
                    $('#op').html(data);
                }
            });
        });
    });
</script>

<!-- END SCRIPTS -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','../../../../www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-36783416-1', 'auto');
    ga('send', 'pageview');
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter25836617 = new Ya.Metrika({
                    id:25836617,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "../../../../mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/25836617" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>

</html>