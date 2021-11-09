<?php

// processa a requisição do formulário para vincular a uma conta playfab
add_action('admin_post_bind_playfab_account', 'ipf_bind_playfab_account');

function ipf_bind_playfab_account()
{
    $user_id = get_current_user_id();
    if (!$user_id)
        return;

    $ipf_playfab_account_bind = isset($_POST['_wpnonce']) ? wp_verify_nonce($_POST['_wpnonce'], 'ipf_playfab_account_bind_' . $user_id) : null;

    $arr_errors = null;

    if ($ipf_playfab_account_bind) {

        $ipf_email = isset($_POST['Email']) ? $_POST['Email'] : null;

        if (!$ipf_email)
            return;

        $ipf_title_id = ipf_get_option('ipf_title_id');
        $ipf_secret_key = ipf_get_option('ipf_secret_key');
        if (!$ipf_title_id || !$ipf_secret_key)
            return;

        // https://85776.playfabapi.com/Admin/GetUserAccountInfo?Email=lafteste@teste.com
        $get_user_info_url = 'https://' . $ipf_title_id . '.playfabapi.com/Admin/GetUserAccountInfo?Email=' . $ipf_email;
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'X-SecretKey'   => $ipf_secret_key
            )
        );

        $response = wp_remote_get($get_user_info_url, $args);
        $playfab_user_info = json_decode($response['body'], true);

        if ($playfab_user_info['status']  === 'OK') {
            $ipf_new_playfab_id = $playfab_user_info['data']['UserInfo']['PlayFabId'];
            update_user_meta($user_id, 'ipf_playfabid', $ipf_new_playfab_id);
        } else {
            $arr_errors = array();
            if (isset($playfab_user_info['errorMessage']))
                $arr_errors['errorMessage'] = $playfab_user_info['errorMessage'];
            if (isset($playfab_user_info['errorDetails']))
                $arr_errors['errorDetails'] = $playfab_user_info['errorDetails'];
        }
    }

    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : null;
    if ($arr_errors)
        $_SESSION['playfab_bind_account_error_messages'] = $arr_errors;
    else
        unset($_SESSION['playfab_bind_account_error_messages']);

    if (!$redirect_to)
        return;

    wp_safe_redirect(esc_url(urldecode($redirect_to)));
    exit;
}

// formulário para vincular a uma conta playfab
add_action('woocommerce_order_details_after_order_table', 'ipf_no_playfab_account_bounded', 10);

function ipf_no_playfab_account_bounded($order)
{
    if ($order->get_status() !== 'processing')
        return;

    $user_id = get_current_user_id();
    if (!$user_id)
        return;

    $ipf_playfabid = get_user_meta($user_id, 'ipf_playfabid', true);

    $user_info = get_userdata($user_id);
    $user_email = $user_info->user_email;

    if (!$ipf_playfabid) {
        echo '<h3>' . __('Não encontramos nenhuma conta Playfab associada à conta da loja.', 'ipf') . '</h3>';
        echo '<p>' . __('Digite o e-mail da conta Playfab para associá-la à conta da loja.') . '</p>';
        ipf_form_playfab_account_bind();
    } else {
        $ipf_title_id = ipf_get_option('ipf_title_id');
        $ipf_secret_key = ipf_get_option('ipf_secret_key');
        if ($ipf_title_id && $ipf_secret_key) {

            // https://85776.playfabapi.com/Admin/GetUserAccountInfo?Email=lafteste@teste.com
            $get_user_info_url = 'https://' . $ipf_title_id . '.playfabapi.com/Admin/GetUserAccountInfo?Email=' . $user_email;
            $args = array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type'  => 'application/json',
                    'X-SecretKey'   => $ipf_secret_key
                )
            );

            $response = wp_remote_get($get_user_info_url, $args);
            $playfab_user_info = json_decode($response['body'], true);

            if (isset($_SESSION['playfab_bind_account_error_messages'])) {
                echo '<p>';
                echo __('Não foi possível vincular a sua conta ao e-mail informado.', 'ipf');
                echo ' ' . __('Ocorreram os seguintes erros:', 'ipf');
                echo '</p>';
                echo '<ul>';

                foreach ($_SESSION['playfab_bind_account_error_messages'] as $k => $v) {
                    if ($k === 'errorMessage')
                        echo '<li>' . $v . '</li>';
                    if ($k === 'errorDetails') {
                        foreach ($v as $k_item => $v_items) {
                            if (!is_array($v_items)) {
                                echo '<li>' . $v_items . '</li>';
                            } else {
                                echo '<li>' . $k_item . ':</li>';
                                echo '<ul>';
                                foreach ($v_items as $v_item) {
                                    echo '<li>' . $v_item . '</li>';
                                }
                                echo '</ul>';
                            }
                        }
                    }
                }
                echo '</ul>';
                unset($_SESSION['playfab_bind_account_error_messages']);
            }

            if ($playfab_user_info['status']  !== 'OK') {
                echo '<h4 class="warning">' . __($playfab_user_info['errorMessage']) . '</h4>';
            } else {
                echo '<h3>' . sprintf(__('Sua conta da loja está vinculada à conta "%s" da Playfab.', 'ipf'), $playfab_user_info['data']['UserInfo']['TitleInfo']['DisplayName']) . '</h3>';
                echo '<p><a href="#" id="toggle-rebind-playfab-account-form">' . __('Vincular a outra conta.', 'ipf') . '</a></p>';
                echo '<div id="rebind-playfab-account-form" style="display: none;">';
                ipf_form_playfab_account_bind();
                echo '</div>';
            }
        }
    } ?>

<?php
}

// exibe form para vincular a uma conta playfab
function ipf_form_playfab_account_bind()
{
    $user_id = get_current_user_id();

    if (!$user_id)
        return;

    $user_info = get_userdata($user_id);
    $user_email = $user_info->user_email;
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $curr_url = urlencode($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

?>
    <form id="bind-playfab-account" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <?php wp_nonce_field('ipf_playfab_account_bind_' . $user_id); ?>
        <input type="hidden" name="action" value="bind_playfab_account" />
        <input type="hidden" name="redirect_to" value="<?php echo $curr_url; ?>" />
        <label for="Email">
            <input type="email" name="Email" placeholder="<?php _e('Seu e-mail', 'ipf'); ?>" value="<?php echo $user_email; ?>" required>
        </label>
        <button><?php _e('Enviar', 'ipf'); ?></button>
    </form>
    <?php
}
// adiciona a moeda virtual no playfab
add_action('admin_post_playfab_add_currency', 'playfab_add_currency');

function playfab_add_currency()
{
    $user_id = get_current_user_id();
    if (!$user_id)
        return;

    $ipf_playfab_add_currency_nonce = isset($_POST['_wpnonce']) ? wp_verify_nonce($_POST['_wpnonce'], 'ipf_playfab_add_currency_' . $user_id) : null;

    // echo $ipf_playfab_add_currency_nonce;
    // return;

    if ($ipf_playfab_add_currency_nonce) {

        $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : null;
        $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
        $virtual_currency = isset($_POST['virtual_currency']) ? $_POST['virtual_currency'] : null;

        if (!$order_id || !$amount || !$virtual_currency)
            return;

        $ipf_title_id = ipf_get_option('ipf_title_id');
        $ipf_secret_key = ipf_get_option('ipf_secret_key');
        $ipf_playfabid = get_user_meta($user_id, 'ipf_playfabid', true);

        if (!$ipf_title_id || !$ipf_secret_key || !$ipf_playfabid)
            return;

        // https://85776.playfabapi.com/Server/AddUserVirtualCurrency?Amount=6&PlayFabId=528A43129799B8BE&VirtualCurrency=GC&OrderId=1
        $get_user_info_url = 'https://' . $ipf_title_id . '.playfabapi.com/Server/AddUserVirtualCurrency?Amount=' . $amount . '&PlayFabId=' . $ipf_playfabid . '&VirtualCurrency=' . $virtual_currency . '&OrderId=' . $order_id;
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type'  => 'application/json',
                'X-SecretKey'   => $ipf_secret_key
            )
        );

        $response = wp_remote_get($get_user_info_url, $args);
        $playfab_user_info = json_decode($response['body'], true);

        if ($playfab_user_info['status']  === 'OK') {
            $ipf_redeemed_order_value = sprintf(__('O pedido já foi resgatado em %s', 'ipf'), current_time('d-m-Y H:i:s'));
            $ipf_redeemed_order = update_post_meta($order_id, 'ipf_redeemed_order', $ipf_redeemed_order_value);

            $order = wc_get_order($order_id);
            $order->set_status('completed');
            $order->save();
        }
    }

    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : null;

    if (!$redirect_to)
        return;

    wp_safe_redirect(esc_url(urldecode($redirect_to)));
    exit;
}


// exibe form para resgatar a moeda no playfab
add_action('woocommerce_order_details_after_order_table', 'ipf_form_add_currency', 10);

function ipf_form_add_currency($order)
{
    $user_id = get_current_user_id();
    if (!$user_id)
        return;

    $ipf_redeemed_order = get_post_meta($order->id, 'ipf_redeemed_order', true);
    if ($ipf_redeemed_order) {
        echo $ipf_redeemed_order;
        return;
    }

    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $curr_url = urlencode($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    foreach ($order->get_items('line_item') as $item) {
        $ipf_product_qty = $item->get_quantity();
        $ipf_product_id = $item->get_product()->get_id();
        $ipf_product_virtual_currency_name = get_post_meta($ipf_product_id, 'ipf_product_virtual_currency_name', true);
        $ipf_product_virtual_currency_qty = get_post_meta($ipf_product_id, 'ipf_product_virtual_currency_qty', true);
        $amount = $ipf_product_qty * $ipf_product_virtual_currency_qty;
        $amount = $amount > 1 ? $amount : 1;
        // ipf_debug(current_time('d-m-Y H:i:s'));
        // ipf_debug($ipf_product_virtual_currency_name);
    ?>
        <form id="playfab-add-currency" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <?php wp_nonce_field('ipf_playfab_add_currency_' . $user_id); ?>
            <input type="hidden" name="action" value="playfab_add_currency" />
            <input type="hidden" name="redirect_to" value="<?php echo $curr_url; ?>" />
            <input type="hidden" name="order_id" value="<?php echo $order->id; ?>" />
            <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
            <input type="hidden" name="virtual_currency" value="<?php echo $ipf_product_virtual_currency_name; ?>" />
            <button><?php _e('Resgatar', 'ipf'); ?></button>
        </form>
<?php
    }
}
