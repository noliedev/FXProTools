<?php

$anet = new AuthAPI();
$accounts = $anet->get_all_users();

dd($accounts);