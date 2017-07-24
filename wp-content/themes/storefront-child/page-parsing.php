<?php

$anet = new AuthAPI();
$account = $anet->get_auth_user_profile(1812467702);

dd($account['payment_profile']);