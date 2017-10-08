<?php
$name = isset($userlogin['name']) && $userlogin['name'] != '' ? $userlogin['name'] : 'User Forex';
if (isset($patnerShowPanel)) {
    $res = _localApi('account', 'list_simple', array($userlogin['email']));
//echo_r($res);
    $account = isset($res['data']) ? $res['data'] : array();
    $total_members = 0;
    foreach ($account as $acc) {
        $account_id = $acc['accountid'];
        $res0 = _localApi('account', 'list_by_partner', array($account_id));
        $members = isset($res0['data']) ? $res0['data'] : array();
        //echo_r($res0);
        if (count($members)) {
            $total_members+=count($members);
        }
    }
//echo_r($userlogin);
    $res0 = _localApi('account', 'partner_revenue', array($userlogin['email'], date("Ymd his")));
    $revenue = $this->param['revenue'] = isset($res0['data']) ? $res0['data'] : 0;
//echo_r($res0); 
    ?>
    <div class="row">
        <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
                <a href="#" class="list-group-item active partition-blue"> View Members <i class="fa fa-15x fa-arrow-circle-right pull-right"></i> </a>
                <li class="list-group-item "> <a href="#" class="block text-center"><h5><strong><?= $total_members; ?> Member(s)</strong></h5></a> </li>
            </ul>
        </div>
        <div class="col-md-4 col-xs-6">
            <ul class="list-group text-dark panel-shadow">
                <a href="#" class="list-group-item active partition-blue"> Partner Revenue <i class="fa fa-15x fa-arrow-circle-right pull-right"></i> </a>
                <li class="list-group-item "> <a href="#" class="block text-center"><h5><strong>
                                <?= number_format($revenue, 2); ?> <!-- (equity)-->
                            </strong></h5></a> </li>
            </ul>
        </div>
        <div class="col-md-4 col-xs-6">

            <ul class="list-group text-dark panel-shadow">
                <a href="#" class="list-group-item active partition-blue"> Promotions <i class="fa fa-15x fa-arrow-circle-right pull-right"></i> </a>
                <li class="list-group-item "> <a href="<?= site_url('partner/promotion'); ?>#" class="block text-center"><h5><strong>Promotion Materials</strong></h5></a> </li>
            </ul>
        </div>
    </div>

    <div class="panel panel-white">
        <div class="panel-heading">
            <h4><strong>Link Affiliation</strong></h4>

            <?php
            foreach ($account as $row0) {
                $account_id = $row0['accountid'];
                if (strtoupper($row0['type']) == 'AGENT') {
                    ?>
                    <div class="well"><p><?= anchor(base_url('register/' . $account_id . '_' . url_title($name))); ?></p></div>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>

        </div>
    </div>
    <?php
}
?>